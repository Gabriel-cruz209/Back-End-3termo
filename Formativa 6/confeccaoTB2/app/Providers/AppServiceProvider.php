<?php

namespace App\Providers;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Observers\ItemPedidoObserver;
use App\Observers\PedidoObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pedido::observe(PedidoObserver::class);
        ItemPedido::observe(ItemPedidoObserver::class);
    }
}
