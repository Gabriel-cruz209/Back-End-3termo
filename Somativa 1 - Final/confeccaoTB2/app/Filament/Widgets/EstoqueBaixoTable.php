<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Produtos\ProdutoResource;
use App\Models\Produto;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class EstoqueBaixoTable extends TableWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Produtos com estoque baixo')
            ->description('Produtos com 5 unidades ou menos.')
            ->query(
                Produto::query()
                    ->where('estoque', '<=', 5)
                    ->orderBy('estoque')
                    ->orderBy('nome')
            )
            ->columns([
                TextColumn::make('nome')
                    ->label('Produto')
                    ->icon('heroicon-m-tag')
                    ->iconColor('primary')
                    ->weight(FontWeight::Medium)
                    ->searchable(),

                TextColumn::make('referencia')
                    ->label('Referência')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),

                TextColumn::make('estoque')
                    ->label('Estoque')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state): string => "{$state} un.")
                    ->sortable(),

                TextColumn::make('preco_venda')
                    ->label('Preço')
                    ->money('BRL')
                    ->color('success')
                    ->sortable(),
            ])
            ->recordUrl(fn (Produto $record): string => ProdutoResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading('Estoque em dia')
            ->emptyStateDescription('Nenhum produto está com estoque baixo.')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25])
            ->striped();
    }
}
