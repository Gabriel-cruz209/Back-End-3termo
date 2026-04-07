<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
// use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
// use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = "Administração";

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Usuários';


    protected static ? string $modelLabel = 'Criar Usuários';

    protected static ? string $pluralModelLabel = 'Usuários';
    
    public static function canAcess(): bool{
        return auth()->user()?->hasRole('Estoque') ?? false && auth()->user()?->hasRole('Admin') ?? false;
    }
    protected static ?string $recordTitleAttribute = 'Usuarios';

    public static function form(Schema $schema): Schema
    {
       return $schema
       -> schema([
        TextInput::make('name')
        ->label('Nome')
        ->required(),

        TextInput::make('email')
        ->label('E-mail')
        ->email()
        ->required(),
        
        TextInput::make('password')
        ->label('Senha')
        ->password()
        ->required(fn (string $operation): bool => $operation === 'create')
        ->dehydrated(fn (?string $state) => filled($state))
        ->hiddenOn('view'),

        Select::make('roles')
        ->label('Cargo / Permissions')
        ->multiple()
        ->preload()
        ->searchable()
        ->relationship('roles', 'name')
       ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return UsersTable::configure($table);
        return $table
        ->columns([
            TextColumn::make('name')
            ->label('Nome')
            ->searchable()
            ->sortable(),

            TextColumn::make('email')
            ->label('Email')
            ->searchable()
            ->sortable(),

            TextColumn::make('password')
            ->label('Senha')
            ->searchable()
            ->sortable(),

            TextColumn::make('roles.name')
            ->label('Cargo')
            ->separator()
            ->searchable()
            ->sortable(),

            TextColumn::make('created_at')
            ->label('Criada em')
            ->dateTime('d/m/Y')
            ->sortable(),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
