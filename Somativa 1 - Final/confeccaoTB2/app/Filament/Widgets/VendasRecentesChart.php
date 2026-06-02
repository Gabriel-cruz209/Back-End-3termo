<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;

class VendasRecentesChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected ?string $heading = 'Vendas recentes';

    protected ?string $description = 'Valor dos pedidos cadastrados nos últimos 14 dias.';

    protected string $color = 'success';

    protected ?string $maxHeight = '320px';

    protected ?string $pollingInterval = null;

    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
            ],
        ],
    ];

    protected function getData(): array
    {
        $days = collect(range(13, 0))
            ->map(fn (int $daysAgo) => now()->subDays($daysAgo));

        return [
            'datasets' => [
                [
                    'label' => 'Valor dos pedidos',
                    'data' => $days
                        ->map(fn ($date): float => (float) Pedido::query()
                            ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                            ->sum('valor_total'))
                        ->all(),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.16)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $days
                ->map(fn ($date): string => $date->format('d/m'))
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
