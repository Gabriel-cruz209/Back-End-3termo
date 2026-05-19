<?php

namespace App\Filament\Resources\StudentMovements\Pages;

use App\Filament\Resources\StudentMovements\StudentMovementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentMovement extends EditRecord
{
    protected static string $resource = StudentMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
