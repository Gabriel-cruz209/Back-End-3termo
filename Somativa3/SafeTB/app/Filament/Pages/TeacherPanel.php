<?php

namespace App\Filament\Pages;

use App\Models\Authorization;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class TeacherPanel extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static string | UnitEnum | null $navigationGroup = 'Professor';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'professor';

    protected string $view = 'filament.pages.teacher-panel';

    protected ?string $heading = 'Painel do Professor';

    protected ?string $subheading = 'Verifique e responda as autorizacoes enviadas a voce pelos alunos.';

    protected Width | string | null $maxContentWidth = Width::Full;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user?->isProfessor() || $user?->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isProfessor() ?? false;
    }

    public function approveAuthorization(int $authorizationId): void
    {
        $authorization = $this->baseQuery()
            ->whereKey($authorizationId)
            ->where('status', 'aguardando_professor')
            ->firstOrFail();

        $authorization->update([
            'status' => 'aguardando_portaria',
            'teacher_validated_at' => now(),
            'teacher_validated_by' => Auth::id(),
        ]);

        Notification::make()
            ->title('Autorizacao aprovada')
            ->success()
            ->send();
    }

    public function rejectAuthorization(int $authorizationId): void
    {
        $authorization = $this->baseQuery()
            ->whereKey($authorizationId)
            ->where('status', 'aguardando_professor')
            ->firstOrFail();

        $authorization->update([
            'status' => 'recusada_professor',
            'teacher_validated_at' => now(),
            'teacher_validated_by' => Auth::id(),
            'cancellation_reason' => 'Recusada pelo professor no painel.',
        ]);

        Notification::make()
            ->title('Autorizacao recusada')
            ->danger()
            ->send();
    }

    public function getPendingAuthorizations(): Collection
    {
        return $this->baseQuery()
            ->where('status', 'aguardando_professor')
            ->latest('authorization_date')
            ->latest('scheduled_time')
            ->limit(5)
            ->get();
    }

    public function getRecentAnswers(): Collection
    {
        return $this->baseQuery()
            ->whereIn('status', ['aguardando_portaria', 'recusada_professor', 'concluida', 'cancelada'])
            ->latest('teacher_validated_at')
            ->latest('updated_at')
            ->limit(3)
            ->get();
    }

    public function getPendingCount(): int
    {
        return $this->baseQuery()
            ->where('status', 'aguardando_professor')
            ->count();
    }

    public function getAnsweredTodayCount(): int
    {
        return $this->baseQuery()
            ->whereDate('teacher_validated_at', now())
            ->count();
    }

    private function baseQuery()
    {
        $query = Authorization::query()
            ->with(['student', 'professorUser', 'schoolClass'])
            ->whereNotNull('professor_user_id');

        $user = Auth::user();

        if ($user?->isProfessor()) {
            $query->where('professor_user_id', $user->id);
        }

        return $query;
    }
}
