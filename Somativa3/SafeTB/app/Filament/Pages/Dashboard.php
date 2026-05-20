<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Dashboard';

    protected ?string $subheading = 'Bem-vindo ao Sistema de Autorizacao e Fluxo Escolar';

    public function mount(): void
    {
        if (Auth::user()?->isProfessor()) {
            $this->redirect(TeacherPanel::getUrl());
        }

        if (Auth::user()?->isPortaria()) {
            $this->redirect(GatePanel::getUrl());
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isAdminAreaUser() ?? false;
    }

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Dashboard';
    }
}
