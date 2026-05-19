<?php

namespace App\Filament\Resources\Authorizations\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use App\Models\Student;
use Filament\Forms\Get;
use Filament\Forms\Set;

class AuthorizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if (!$state) return;
                        $student = Student::find($state);
                        if ($student) {
                            $set('school_class_id', $student->school_class_id);
                            if ($student->schoolClass) {
                                $set('course_id', $student->schoolClass->course_id);
                            }
                        }
                    }),
                Select::make('school_class_id')
                    ->relationship('schoolClass', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Turma'),
                Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Curso'),
                Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Professor'),
                Select::make('type')
                    ->options([
                        'entrada' => 'Entrada',
                        'saida' => 'Saída',
                    ])
                    ->required()
                    ->label('Tipo'),
                DatePicker::make('authorization_date')
                    ->required()
                    ->default(now())
                    ->label('Data'),
                TimePicker::make('scheduled_time')
                    ->required()
                    ->label('Horário previsto'),
                Textarea::make('reason')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Motivo'),
                Toggle::make('has_absence')
                    ->label('Com falta?'),
                TextInput::make('absence_count')
                    ->numeric()
                    ->default(0)
                    ->label('Número de faltas'),
                Select::make('status')
                    ->options([
                        'rascunho' => 'Rascunho',
                        'aguardando_professor' => 'Aguardando Professor',
                        'aprovada_professor' => 'Aprovada Professor',
                        'recusada_professor' => 'Recusada Professor',
                        'aguardando_portaria' => 'Aguardando Portaria',
                        'validada_portaria' => 'Validada Portaria',
                        'concluida' => 'Concluída',
                        'cancelada' => 'Cancelada',
                    ])
                    ->required()
                    ->default('rascunho'),
                Select::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->default(auth()->id())
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->label('Criado por'),
            ]);
    }
}
