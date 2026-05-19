<?php

namespace App\Filament\Resources\StudentMovements\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;

class StudentMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('authorization_id')
                    ->relationship('authorization', 'id')
                    ->required()
                    ->label('Autorização'),
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required()
                    ->label('Aluno'),
                Select::make('type')
                    ->options([
                        'entrada' => 'Entrada',
                        'saida' => 'Saída',
                    ])
                    ->required()
                    ->label('Tipo'),
                DateTimePicker::make('occurred_at')
                    ->required()
                    ->default(now())
                    ->label('Ocorrido em'),
                Select::make('validated_by')
                    ->relationship('validator', 'name')
                    ->required()
                    ->label('Validado por'),
                Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Observações'),
            ]);
    }
}
