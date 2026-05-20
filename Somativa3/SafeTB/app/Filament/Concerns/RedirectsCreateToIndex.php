<?php

namespace App\Filament\Concerns;

trait RedirectsCreateToIndex
{
    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl();
    }
}
