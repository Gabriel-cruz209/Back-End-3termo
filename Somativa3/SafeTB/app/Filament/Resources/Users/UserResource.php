<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Funcionarios e Acessos';

    protected static ?string $modelLabel = 'Funcionario';

    protected static ?string $pluralModelLabel = 'Funcionarios e Acessos';

    protected static string | UnitEnum | null $navigationGroup = 'Administracao';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->placeholder('usuario@escola.com')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('cpf')
                    ->label('CPF')
                    ->mask('999.999.999-99')
                    ->placeholder('000.000.000-00')
                    ->unique(ignoreRecord: true)
                    ->maxLength(14),
                TextInput::make('cep')
                    ->label('CEP')
                    ->mask('99999-999')
                    ->placeholder('00000-000')
                    ->maxLength(9),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->mask('(99) 99999-9999')
                    ->placeholder('(00) 00000-0000')
                    ->maxLength(15),
                TextInput::make('rm')
                    ->label('RM')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('role')
                    ->label('Tipo de acesso')
                    ->options([
                        'admin' => 'Administrador',
                        'aqv' => 'Secretaria',
                        'professor' => 'Professor',
                        'portaria' => 'Portaria',
                        'coordenacao' => 'Coordenacao',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
                CheckboxList::make('schoolClasses')
                    ->relationship('schoolClasses', 'name')
                    ->label('Turmas vinculadas')
                    ->helperText('Use este campo para funcionarios com acesso de professor.')
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                TextColumn::make('rm')
                    ->label('RM')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('role')
                    ->label('Acesso')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'aqv' => 'Secretaria',
                        'professor' => 'Professor',
                        'portaria' => 'Portaria',
                        'coordenacao' => 'Coordenacao',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'aqv', 'coordenacao' => 'info',
                        'professor' => 'success',
                        'portaria' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ]);
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
