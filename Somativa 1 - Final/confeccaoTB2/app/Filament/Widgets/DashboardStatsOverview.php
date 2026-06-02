<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Insumo;
use App\Models\Pedido;
use App\Models\Produto;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Visão geral do sistema';

    protected ?string $description = 'Resumo dos cadastros e movimentações recentes.';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalPedidos = Pedido::query()->count();
        $pedidosHoje = Pedido::query()->whereDate('created_at', today())->count();
        $faturamento = (float) Pedido::query()->sum('valor_total');
        $faturamentoHoje = (float) Pedido::query()->whereDate('created_at', today())->sum('valor_total');
        $clientes = Cliente::query()->count();
        $clientesHoje = Cliente::query()->whereDate('created_at', today())->count();
        $produtos = Produto::query()->count();
        $estoqueTotal = (int) Produto::query()->sum('estoque');
        $estoqueBaixo = Produto::query()->where('estoque', '<=', 5)->count();
        $estoqueZerado = Produto::query()->where('estoque', '<=', 0)->count();
        $insumos = Insumo::query()->count();
        $fornecedores = Fornecedor::query()->count();

        return [
            Stat::make('Faturamento', $this->money($faturamento))
                ->description('Hoje: ' . $this->money($faturamentoHoje))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($this->vendasPorDia())
                ->color('success')
                ->icon('heroicon-m-currency-dollar'),

            Stat::make('Pedidos', $totalPedidos)
                ->description("Hoje: {$pedidosHoje} novos")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->pedidosPorDia())
                ->color('info')
                ->icon('heroicon-m-clipboard-document-list'),

            Stat::make('Clientes', $clientes)
                ->description("Hoje: {$clientesHoje} novos")
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary')
                ->icon('heroicon-m-user-group'),

            Stat::make('Produtos', $produtos)
                ->description("Estoque total: {$estoqueTotal} un.")
                ->descriptionIcon('heroicon-m-cube')
                ->color('gray')
                ->icon('heroicon-m-squares-2x2'),

            Stat::make('Estoque baixo', $estoqueBaixo)
                ->description("Zerados: {$estoqueZerado}")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($estoqueZerado > 0 ? 'danger' : 'warning')
                ->icon('heroicon-m-archive-box'),

            Stat::make('Operação', "{$insumos} insumos")
                ->description("{$fornecedores} fornecedores cadastrados")
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary')
                ->icon('heroicon-m-wrench-screwdriver'),
        ];
    }

    /**
     * @return array<int, int>
     */
    private function pedidosPorDia(int $days = 7): array
    {
        return collect(range($days - 1, 0))
            ->map(function (int $daysAgo): int {
                $date = now()->subDays($daysAgo);

                return Pedido::query()
                    ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                    ->count();
            })
            ->all();
    }

    /**
     * @return array<int, float>
     */
    private function vendasPorDia(int $days = 7): array
    {
        return collect(range($days - 1, 0))
            ->map(function (int $daysAgo): float {
                $date = now()->subDays($daysAgo);

                return (float) Pedido::query()
                    ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                    ->sum('valor_total');
            })
            ->all();
    }

    private function money(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
