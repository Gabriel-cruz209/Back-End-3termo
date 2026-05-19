<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Authorization;
use App\Models\StudentMovement;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Autorizações Hoje', Authorization::whereDate('authorization_date', now())->count())
                ->description('Total de pedidos hoje')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            Stat::make('Aguardando Professor', Authorization::where('status', 'aguardando_professor')->count())
                ->description('Pendentes de aprovação')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Aguardando Portaria', Authorization::where('status', 'aguardando_portaria')->count())
                ->description('Prontos para validar')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),
            Stat::make('Movimentações Hoje', StudentMovement::whereDate('occurred_at', now())->count())
                ->description('Entradas e Saídas')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('primary'),
        ];
    }
}
