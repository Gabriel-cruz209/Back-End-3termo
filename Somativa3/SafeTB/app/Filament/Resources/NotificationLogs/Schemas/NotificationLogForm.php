<?php

namespace App\Filament\Resources\NotificationLogs\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;

class NotificationLogForm
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
                Select::make('guardian_id')
                    ->relationship('guardian', 'name')
                    ->label('Responsável'),
                Select::make('channel')
                    ->options([
                        'email' => 'E-mail',
                        'whatsapp_simulado' => 'WhatsApp (Simulado)',
                        'log' => 'Log',
                    ])
                    ->required()
                    ->label('Canal'),
                TextInput::make('recipient')
                    ->label('Destinatário'),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull()
                    ->label('Mensagem'),
                Select::make('status')
                    ->options([
                        'enviado' => 'Enviado',
                        'erro' => 'Erro',
                        'simulado' => 'Simulado',
                    ])
                    ->required()
                    ->label('Status'),
                DateTimePicker::make('sent_at')
                    ->label('Enviado em'),
                Textarea::make('error_message')
                    ->columnSpanFull()
                    ->label('Mensagem de erro'),
            ]);
    }
}
