<?php

namespace App\Filament\Resources\Insumos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InsumosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('nome')
                    ->label('Insumo')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Medium)
                    ->icon('heroicon-m-beaker')
                    ->iconColor('primary'),

                TextColumn::make('unidade_medida')
                    ->label('Unidade')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('preco_custo')
                    ->label('Preço de Custo')
                    ->money('BRL')
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->color('warning'),

                TextColumn::make('estoque')
                    ->label('Estoque')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match(true) {
                        $state <= 0  => 'danger',
                        $state <= 5  => 'warning',
                        default      => 'success',
                    })
                    ->formatStateUsing(fn ($state, $record): string =>
                        $state . ' ' . ($record->unidade_medida ?? 'un.')
                    ),

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
