<?php

namespace App\Filament\Resources\SchoolClasses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SchoolClassesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('course.name')
                    ->searchable()
                    ->label('Curso'),
                TextColumn::make('shift')
                    ->label('Turno'),
                TextColumn::make('year')
                    ->label('Ano'),
                TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Total de alunos'),
                TextColumn::make('teachers_count')
                    ->counts('teachers')
                    ->label('Total de professores'),
                ToggleColumn::make('is_active')
                    ->label('Status'),
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
