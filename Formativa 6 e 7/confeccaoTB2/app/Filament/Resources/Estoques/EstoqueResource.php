<?php

namespace App\Filament\Resources\Estoques;

use App\Filament\Resources\Estoques\Pages\CreateEstoque;
use App\Filament\Resources\Estoques\Pages\EditEstoque;
use App\Filament\Resources\Estoques\Pages\ListEstoques;
use App\Filament\Resources\Estoques\Pages\ViewEstoque;
use App\Filament\Resources\Estoques\Schemas\EstoqueInfolist;
use App\Filament\Resources\Estoques\Tables\EstoquesTable;
use App\Models\Estoque;
use BackedEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EstoqueResource extends Resource
{
    protected static ?string $model = Estoque::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Estoque';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Estoque';

    protected static ?string $modelLabel = 'Movimentação de Estoque';

    protected static ?string $pluralModelLabel = 'Estoque';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('produto_id')
                    ->relationship('produto', 'nome') // Busca o nome do produto automaticamente
                    ->label('Produto')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Select::make('tipo')
                    ->label('Tipo de Movimentação')
                    ->options([
                        'Entrada' => 'Entrada (Adicionar ao Estoque)',
                        'Saída' => 'Saída (Retirar do Estoque)',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->helperText('Apenas números inteiros positivos (ex: 10).'),

                TextInput::make('observacao')
                    ->label('Observação / Motivo')
                    ->maxLength(255)
                    ->placeholder('Ex: Compra do fornecedor, devolução, ajuste manual...')
                    ->columnSpanFull(),
            ]);
    }
    public static function infolist(Schema $schema): Schema
    {
        return EstoqueInfolist::configure($schema);
    }

     public static function table(Table $table): Table
    {
        return EstoquesTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Entrada' => 'success', // Verde para entrada
                        'Saída' => 'danger',    // Vermelho para saída
                        default => 'gray',
                    }),

                TextColumn::make('quantidade')
                    ->label('Qtd')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('observacao')
                    ->label('Observação')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc') // Mostra sempre os mais recentes primeiro
            ->filters([
                //
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
            'index'  => ListEstoques::route('/'),
            'create' => CreateEstoque::route('/create'),
            'view'   => ViewEstoque::route('/{record}'),
            'edit'   => EditEstoque::route('/{record}/edit'),
        ];
    }
}
