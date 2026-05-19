<?php

namespace App\Filament\Resources\SchoolClasses\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;

class SchoolClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome da turma'),
                Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Curso'),
                Select::make('shift')
                    ->options([
                        'manha' => 'Manhã',
                        'tarde' => 'Tarde',
                        'noite' => 'Noite',
                    ])
                    ->required()
                    ->label('Turno'),
                TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->label('Ano'),
                CheckboxList::make('teachers')
                    ->relationship('teachers', 'name')
                    ->label('Professores vinculados'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
