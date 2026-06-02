<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->label('Nome')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email(),
                TextInput::make('telefone')
                    ->label('Telefone')
                    ->tel(),
                TextInput::make('documento')
                    ->label('CPF / CNPJ'),
            ]);
    }
}
