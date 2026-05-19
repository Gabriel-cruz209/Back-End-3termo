<?php

namespace App\Filament\Resources\Authorizations\Pages;

use App\Filament\Resources\Authorizations\AuthorizationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorization extends EditRecord
{
    protected static string $resource = AuthorizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
