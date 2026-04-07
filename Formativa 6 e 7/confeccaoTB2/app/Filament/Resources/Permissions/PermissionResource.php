<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\ViewPermission;
// use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Permissions\Schemas\PermissionInfolist;
// use App\Filament\Resources\Permissions\Tables\PermissionsTable;
use Filament\Tables\Columns\TextColumn;
// use App\Models\Permission;
use Spatie\Permission\Models\Permission;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Permissoes';

    protected static ?string $navigationLabel = 'Painel Permissões';


    protected static ? string $modelLabel = 'Criar Permissões';

    protected static ? string $pluralModelLabel = 'Permissões';

    protected static string|UnitEnum|null $navigationGroup = "Administração";

    protected static ?int $navigationSort = 2;


    public static function form(Schema $schema): Schema
    {
        // return PermissionForm::configure($schema);
        return $schema
        ->schema([
            TextInput::make('name')
            ->label('Nome de Permissao')
            ->required()
            ->unique(ignoreRecord:true)
            ->maxLength(255)
            ->columnSpanFull(),

            TextInput::make('guard_name')
            ->label('Nivel de Permissao')
            ->required()
            ->unique(ignoreRecord:true)
            ->maxLength(20)
            ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PermissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return PermissionsTable::configure($table);
        return $table
        ->columns([
            TextColumn::make('name')
            ->label('Nome de Permissao')
            ->searchable()
            ->sortable(),

            TextColumn::make('guard_name')
            ->label('Nivel de Permissao')
            ->searchable(),

            TextColumn::make('created_at')
            ->label('Criada em')
            ->dateTime('d/m/Y')
            ->sortable()
        ]);

        // ->actions([
        //     use Filament\Tables\Actions\EditAction::make();
        //     use Filament\Tables\Actions\DeleteAction::make();
        // ]);
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
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'view' => ViewPermission::route('/{record}'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
