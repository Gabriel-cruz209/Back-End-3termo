<?php

namespace App\Filament\Resources\StudentMovements;

use App\Filament\Resources\StudentMovements\Pages\CreateStudentMovement;
use App\Filament\Resources\StudentMovements\Pages\EditStudentMovement;
use App\Filament\Resources\StudentMovements\Pages\ListStudentMovements;
use App\Filament\Resources\StudentMovements\Schemas\StudentMovementForm;
use App\Filament\Resources\StudentMovements\Tables\StudentMovementsTable;
use App\Models\StudentMovement;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentMovementResource extends Resource
{
    protected static ?string $model = StudentMovement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationLabel = 'Movimentações';
    protected static ?string $modelLabel = 'Movimentação';
    protected static string|UnitEnum|null $navigationGroup = 'Fluxo de Acesso';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return StudentMovementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentMovementsTable::configure($table);
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
            'index' => ListStudentMovements::route('/'),
            'create' => CreateStudentMovement::route('/create'),
            'edit' => EditStudentMovement::route('/{record}/edit'),
        ];
    }
}
