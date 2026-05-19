<?php

namespace App\Filament\Resources\Authorizations;

use App\Filament\Resources\Authorizations\Pages\CreateAuthorization;
use App\Filament\Resources\Authorizations\Pages\EditAuthorization;
use App\Filament\Resources\Authorizations\Pages\ListAuthorizations;
use App\Filament\Resources\Authorizations\Schemas\AuthorizationForm;
use App\Filament\Resources\Authorizations\Tables\AuthorizationsTable;
use App\Models\Authorization;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuthorizationResource extends Resource
{
    protected static ?string $model = Authorization::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Autorizações';
    protected static ?string $modelLabel = 'Autorização';
    protected static string|UnitEnum|null $navigationGroup = 'Fluxo de Acesso';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return AuthorizationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorizationsTable::configure($table);
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
            'index' => ListAuthorizations::route('/'),
            'create' => CreateAuthorization::route('/create'),
            'edit' => EditAuthorization::route('/{record}/edit'),
        ];
    }
}
