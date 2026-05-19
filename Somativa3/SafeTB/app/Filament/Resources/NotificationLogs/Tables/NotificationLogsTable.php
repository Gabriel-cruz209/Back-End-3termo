<?php

namespace App\Filament\Resources\NotificationLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NotificationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sent_at')
                    ->label('Data/Hora')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('student.name')
                    ->label('Aluno')
                    ->searchable(),
                TextColumn::make('guardian.name')
                    ->label('Responsável')
                    ->searchable(),
                TextColumn::make('channel')
                    ->label('Canal')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'info',
                        'whatsapp_simulado' => 'success',
                        'log' => 'gray',
                    }),
                TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(50),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enviado' => 'success',
                        'erro' => 'danger',
                        'simulado' => 'info',
                    }),
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
