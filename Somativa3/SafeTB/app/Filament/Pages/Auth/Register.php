<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;

class Register extends BaseRegister
{
    public function getView(): string
    {
        return 'filament.pages.auth.register';
    }
}
