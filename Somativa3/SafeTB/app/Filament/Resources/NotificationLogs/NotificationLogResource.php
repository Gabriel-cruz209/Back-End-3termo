<?php

namespace App\Filament\Resources\NotificationLogs;

use App\Filament\Resources\NotificationLogs\Pages\CreateNotificationLog;
use App\Filament\Resources\NotificationLogs\Pages\EditNotificationLog;
use App\Filament\Resources\NotificationLogs\Pages\ListNotificationLogs;
use App\Filament\Resources\NotificationLogs\Schemas\NotificationLogForm;
use App\Filament\Resources\NotificationLogs\Tables\NotificationLogsTable;
use App\Models\NotificationLog;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificationLogResource extends Resource
{
    protected static ?string $model = NotificationLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notificações';
    protected static ?string $modelLabel = 'Log de Notificação';
    protected static string|UnitEnum|null $navigationGroup = 'Fluxo de Acesso';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return NotificationLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotificationLogs::route('/'),
            'create' => CreateNotificationLog::route('/create'),
            'edit' => EditNotificationLog::route('/{record}/edit'),
        ];
    }
}
