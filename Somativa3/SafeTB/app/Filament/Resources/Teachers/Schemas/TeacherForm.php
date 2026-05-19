<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('cpf')
                    ->maxLength(255),
                TextInput::make('cep')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('rm')
                    ->maxLength(255),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Usuário vinculado'),
                CheckboxList::make('schoolClasses')
                    ->relationship('schoolClasses', 'name')
                    ->label('Turmas'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
