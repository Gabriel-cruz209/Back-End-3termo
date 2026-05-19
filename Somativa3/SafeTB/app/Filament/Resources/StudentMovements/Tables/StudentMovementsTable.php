<?php

namespace App\Filament\Resources\StudentMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('authorization.id')
                    ->label('Autorização ID')
                    ->searchable(),
                TextColumn::make('student.name')
                    ->label('Aluno')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'saida' => 'warning',
                    }),
                TextColumn::make('occurred_at')
                    ->label('Data/Hora')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('validator.name')
                    ->label('Validado por'),
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
