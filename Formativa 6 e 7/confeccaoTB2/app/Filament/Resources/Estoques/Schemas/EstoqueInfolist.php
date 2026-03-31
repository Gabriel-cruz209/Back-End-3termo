<?php

namespace App\Filament\Resources\Estoques\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EstoqueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('produto.nome')
                    ->label('Produto'),

                TextEntry::make('tipo')
                    ->label('Tipo de Movimentação')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Entrada' => 'success',
                        'Saída'   => 'danger',
                        default   => 'gray',
                    }),

                TextEntry::make('quantidade')
                    ->label('Quantidade')
                    ->numeric(),

                TextEntry::make('observacao')
                    ->label('Observação')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
