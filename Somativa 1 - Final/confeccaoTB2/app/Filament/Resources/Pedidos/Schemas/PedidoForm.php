<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('clientes_id')
                    ->label('Cliente')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->label('Status')
                    ->required()
                    ->default('Pendente'),
                TextInput::make('valor_total')
                    ->label('Valor total')
                    ->numeric(),
            ]);
    }
}
