<?php

namespace App\Filament\Resources\Clientes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Medium)
                    ->icon('heroicon-m-user')
                    ->iconColor('primary'),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('E-mail copiado!')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('gray')
                    ->placeholder('—'),

                TextColumn::make('telefone')
                    ->label('Telefone')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray')
                    ->placeholder('—'),

                TextColumn::make('documento')
                    ->label('CPF / CNPJ')
                    ->searchable()
                    ->fontFamily('mono')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->since()
                    ->dateTimeTooltip('d/m/Y \à\s H:i')
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nome')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label('Ver')->icon('heroicon-m-eye'),
                EditAction::make()->label('Editar')->icon('heroicon-m-pencil-square'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
