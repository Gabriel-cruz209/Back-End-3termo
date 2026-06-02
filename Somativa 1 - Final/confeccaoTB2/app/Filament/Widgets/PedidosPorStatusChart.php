<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;

class PedidosPorStatusChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected ?string $heading = 'Pedidos por status';

    protected ?string $description = 'Distribuição dos pedidos cadastrados no sistema.';

    protected string $color = 'info';

    protected ?string $maxHeight = '320px';

    protected ?string $pollingInterval = null;

    protected ?array $options = [
        'cutout' => '68%',
        'plugins' => [
            'legend' => [
                'position' => 'bottom',
            ],
        ],
    ];

    protected function getData(): array
    {
        $statuses = Pedido::query()
            ->selectRaw("COALESCE(NULLIF(status, ''), 'Sem status') as status_label, COUNT(*) as total")
            ->groupBy('status_label')
            ->orderByDesc('total')
            ->get();

        if ($statuses->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Pedidos',
                        'data' => [1],
                        'backgroundColor' => ['#e5e7eb'],
                        'borderWidth' => 0,
                    ],
                ],
                'labels' => ['Sem dados'],
            ];
        }

        $palette = [
            '#2563eb',
            '#16a34a',
            '#f59e0b',
            '#dc2626',
            '#7c3aed',
            '#0891b2',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos',
                    'data' => $statuses->pluck('total')->map(fn ($total): int => (int) $total)->all(),
                    'backgroundColor' => collect($statuses)
                        ->keys()
                        ->map(fn (int $index): string => $palette[$index % count($palette)])
                        ->all(),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $statuses->pluck('status_label')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
