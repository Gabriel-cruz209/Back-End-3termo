<?php

namespace App\Filament\Resources\LessonSchedules\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;

class LessonScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('school_class_id')
                    ->relationship('schoolClass', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Turma'),
                TextInput::make('lesson_number')
                    ->numeric()
                    ->required()
                    ->label('Número da aula'),
                TimePicker::make('start_time')
                    ->required()
                    ->label('Início'),
                TimePicker::make('end_time')
                    ->required()
                    ->label('Fim'),
                TextInput::make('duration_minutes')
                    ->numeric()
                    ->default(45)
                    ->label('Duração (minutos)'),
            ]);
    }
}
