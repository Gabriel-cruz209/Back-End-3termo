<?php

namespace App\Filament\Resources\Estoques\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EstoquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip('d/m/Y \à\s H:i')
                    ->color('gray'),

                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->icon('heroicon-m-cube')
                    ->iconColor('primary'),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state === 'Entrada' ? 'Entrada' : 'Saída')
                    ->icon(fn (string $state): string => match ($state) {
                        'Entrada' => 'heroicon-m-arrow-up-circle',
                        default => 'heroicon-m-arrow-down-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Entrada' => 'success',
                        default => 'danger',
                    })
                    ->searchable(),

                TextColumn::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable()
                    ->fontFamily('mono')
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn (string $state, $record): string => ($record->tipo === 'Entrada' ? '+' : '-') . $state . ' un.')
                    ->color(fn ($record): string => $record->tipo === 'Entrada' ? 'success' : 'danger'),

                TextColumn::make('observacao')
                    ->label('Observação')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->observacao)
                    ->placeholder('—')
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'Entrada' => 'Entrada',
                        'Saída' => 'Saída',
                    ]),
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
