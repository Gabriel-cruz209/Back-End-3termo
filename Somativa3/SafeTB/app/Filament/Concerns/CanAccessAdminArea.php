<?php

namespace App\Filament\Concerns;

trait CanAccessAdminArea
{
    public static function canAccess(): bool
    {
        return auth()->user()?->isAdminAreaUser() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
