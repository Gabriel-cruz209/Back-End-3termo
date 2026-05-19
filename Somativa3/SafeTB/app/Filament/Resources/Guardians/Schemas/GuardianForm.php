<?php

namespace App\Filament\Resources\Guardians\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;

class GuardianForm
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
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('cpf')
                    ->maxLength(255),
                TextInput::make('relationship')
                    ->maxLength(255)
                    ->label('Parentesco'),
                CheckboxList::make('students')
                    ->relationship('students', 'name')
                    ->label('Alunos vinculados'),
            ]);
    }
}
