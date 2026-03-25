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
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('Pendente'),
                TextInput::make('valor_total')
                    ->numeric(),
            ]);
    }
}
