<?php

namespace App\Filament\Resources\LessonSchedules\Pages;

use App\Filament\Resources\LessonSchedules\LessonScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLessonSchedules extends ListRecords
{
    protected static string $resource = LessonScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
