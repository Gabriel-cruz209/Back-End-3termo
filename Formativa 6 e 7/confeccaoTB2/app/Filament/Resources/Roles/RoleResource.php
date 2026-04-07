<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRole;
// use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Schemas\RoleInfolist;
// use App\Filament\Resources\Roles\Tables\RolesTable;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    public static function canAcess(): bool{
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static ?string $navigationLabel = 'Cargos';


    protected static ? string $modelLabel = 'Criar Cargos';

    protected static ? string $pluralModelLabel = 'Cargos';

    protected static string|UnitEnum|null $navigationGroup = "Administração";
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Cargo';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            TextInput::make('name')
            ->label('Cargo')
            ->required()
            ->unique(ignoreRecord:true)
            ->maxLength(255),

            Select::make('permissions')
            ->label('Permissão de Acesso')
            ->multiple()
            ->relationship('permissions','name' )
            ->preload()
            ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return RolesTable::configure($table);
        return $table
        ->columns([
            TextColumn::make('name')
            ->label('Nome do Cargo')
            ->searchable()
            ->sortable(),

            TextColumn::make('permissions')
            ->label('Permissões')
            ->formatStateUsing(fn ($state) => $state->pluck('name')->join(', '))
            ->searchable(),

            TextColumn::make('created_at')
            ->label('Criada em')
            ->dateTime('d/m/Y')
            ->sortable()
        ]);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
