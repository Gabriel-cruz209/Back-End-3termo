<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Pedidos\PedidoResource;
use App\Models\Pedido;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PedidosRecentesTable extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pedidos recentes')
            ->description('Últimos pedidos cadastrados.')
            ->query(
                Pedido::query()
                    ->with('cliente')
                    ->latest()
            )
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->prefix('#')
                    ->fontFamily('mono')
                    ->weight(FontWeight::Bold)
                    ->sortable(),

                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Pendente' => 'warning',
                        'Finalizado' => 'success',
                        default => 'info',
                    }),

                TextColumn::make('valor_total')
                    ->label('Valor')
                    ->money('BRL')
                    ->weight(FontWeight::Bold)
                    ->color('success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Criado')
                    ->since()
                    ->dateTimeTooltip('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordUrl(fn (Pedido $record): string => PedidoResource::getUrl('view', ['record' => $record]))
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25])
            ->striped();
    }
}
