<?php

namespace App\Filament\Resources\Authorizations\Pages;

use App\Filament\Resources\Authorizations\AuthorizationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthorizations extends ListRecords
{
    protected static string $resource = AuthorizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
