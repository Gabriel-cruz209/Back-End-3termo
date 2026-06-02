<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Painel';

    protected static ?string $navigationLabel = 'Painel';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSquares2x2;

    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }
}
