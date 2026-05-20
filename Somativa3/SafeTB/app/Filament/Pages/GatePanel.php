<?php

namespace App\Filament\Pages;

use App\Models\Authorization;
use App\Models\StudentMovement;
use App\Services\AuthorizationValidationService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class GatePanel extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static string | UnitEnum | null $navigationGroup = 'Portaria';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'portaria';

    protected string $view = 'filament.pages.gate-panel';

    protected ?string $heading = 'Painel da Portaria';

    protected ?string $subheading = 'Acompanhe e valide as autorizacoes enviadas para a portaria.';

    protected Width | string | null $maxContentWidth = Width::Full;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user?->isPortaria() || $user?->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isPortaria() ?? false;
    }

    public function confirmAuthorization(int $authorizationId): void
    {
        $authorization = Authorization::query()
            ->whereKey($authorizationId)
            ->where('status', 'aguardando_portaria')
            ->firstOrFail();

        app(AuthorizationValidationService::class)->validateAtGate($authorization, Auth::user());

        Notification::make()
            ->title('Autorizacao confirmada')
            ->success()
            ->send();
    }

    public function rejectAuthorization(int $authorizationId): void
    {
        $authorization = Authorization::query()
            ->whereKey($authorizationId)
            ->where('status', 'aguardando_portaria')
            ->firstOrFail();

        $authorization->update([
            'status' => 'cancelada',
            'gate_validated_at' => now(),
            'gate_validated_by' => Auth::id(),
            'canceled_at' => now(),
            'cancellation_reason' => 'Recusada pela portaria no painel.',
        ]);

        Notification::make()
            ->title('Autorizacao recusada')
            ->danger()
            ->send();
    }

    public function getPendingAuthorizations(): Collection
    {
        return Authorization::query()
            ->with(['student', 'professorUser', 'schoolClass'])
            ->where('status', 'aguardando_portaria')
            ->latest('authorization_date')
            ->latest('scheduled_time')
            ->limit(5)
            ->get();
    }

    public function getRecentValidations(): Collection
    {
        return StudentMovement::query()
            ->with(['student', 'validator', 'authorization'])
            ->latest('occurred_at')
            ->limit(3)
            ->get();
    }

    public function getPendingCount(): int
    {
        return Authorization::query()
            ->where('status', 'aguardando_portaria')
            ->count();
    }

    public function getConfirmedTodayCount(): int
    {
        return StudentMovement::query()
            ->whereDate('occurred_at', now())
            ->count();
    }
}
