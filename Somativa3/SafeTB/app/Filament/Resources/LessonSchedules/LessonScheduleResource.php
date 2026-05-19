<?php

namespace App\Filament\Resources\LessonSchedules;

use App\Filament\Resources\LessonSchedules\Pages\CreateLessonSchedule;
use App\Filament\Resources\LessonSchedules\Pages\EditLessonSchedule;
use App\Filament\Resources\LessonSchedules\Pages\ListLessonSchedules;
use App\Filament\Resources\LessonSchedules\Schemas\LessonScheduleForm;
use App\Filament\Resources\LessonSchedules\Tables\LessonSchedulesTable;
use App\Models\LessonSchedule;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LessonScheduleResource extends Resource
{
    protected static ?string $model = LessonSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|UnitEnum|null $navigationGroup = 'Configurações';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Horários das Aulas';
    protected static ?string $modelLabel = 'Horário de Aula';
    protected static ?string $pluralModelLabel = 'Horários das Aulas';

    public static function form(Schema $schema): Schema
    {
        return LessonScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonSchedulesTable::configure($table);
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
            'index' => ListLessonSchedules::route('/'),
            'create' => CreateLessonSchedule::route('/create'),
            'edit' => EditLessonSchedule::route('/{record}/edit'),
        ];
    }
}
