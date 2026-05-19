<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\StudentMovement;
use Carbon\Carbon;

class MovementsChart extends ChartWidget
{
    protected ?string $heading = 'Fluxo de Alunos (Últimos 7 dias)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = StudentMovement::selectRaw('DATE(occurred_at) as date, type, count(*) as total')
            ->where('occurred_at', '>=', now()->subDays(7))
            ->groupBy('date', 'type')
            ->get();

        $dates = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
        
        $entradas = $dates->map(function ($date) use ($data) {
            return $data->where('date', $date)->where('type', 'entrada')->first()?->total ?? 0;
        });

        $saidas = $dates->map(function ($date) use ($data) {
            return $data->where('date', $date)->where('type', 'saida')->first()?->total ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Entradas',
                    'data' => $entradas->toArray(),
                    'borderColor' => '#10b981',
                ],
                [
                    'label' => 'Saídas',
                    'data' => $saidas->toArray(),
                    'borderColor' => '#f59e0b',
                ],
            ],
            'labels' => $dates->map(fn ($date) => Carbon::parse($date)->format('d/m'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
