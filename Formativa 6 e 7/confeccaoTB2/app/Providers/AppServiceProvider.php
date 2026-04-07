<?php

namespace App\Providers;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Observers\ItemPedidoObserver;
use App\Observers\PedidoObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::before(function ($user, $ability){
            return $user->hasRole('Admin') ? true : null; 
        });
    }
}
