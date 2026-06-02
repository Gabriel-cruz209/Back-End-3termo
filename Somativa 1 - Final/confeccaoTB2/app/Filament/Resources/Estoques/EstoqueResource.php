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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EstoqueResource extends Resource
{
    protected static ?string $model = Estoque::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string | UnitEnum | null $navigationGroup = 'Estoque';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Estoque';

    protected static ?string $modelLabel = 'Movimentação de estoque';

    protected static ?string $pluralModelLabel = 'Estoque';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Movimentação de estoque')
                    ->schema([
                        Select::make('produto_id')
                            ->relationship('produto', 'nome')
                            ->label('Produto')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('tipo')
                            ->label('Tipo de movimentação')
                            ->options([
                                'Entrada' => 'Entrada (adicionar ao estoque)',
                                'Saída' => 'Saída (retirar do estoque)',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('quantidade')
                            ->label('Quantidade')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->helperText('Apenas números inteiros positivos, por exemplo: 10.'),

                        TextInput::make('observacao')
                            ->label('Observação / motivo')
                            ->maxLength(255)
                            ->placeholder('Ex.: compra do fornecedor, devolução, ajuste manual...')
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'md' => 2,
                    ])
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
            'index' => ListEstoques::route('/'),
            'create' => CreateEstoque::route('/create'),
            'view' => ViewEstoque::route('/{record}'),
            'edit' => EditEstoque::route('/{record}/edit'),
        ];
    }
}
