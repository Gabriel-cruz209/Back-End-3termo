<?php

namespace App\Filament\Resources\LessonSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('schoolClass.name')
                    ->label('Turma')
                    ->default('Todas'),
                TextColumn::make('lesson_number')
                    ->label('Aula'),
                TextColumn::make('start_time')
                    ->label('Início'),
                TextColumn::make('end_time')
                    ->label('Fim'),
                TextColumn::make('duration_minutes')
                    ->label('Duração'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
