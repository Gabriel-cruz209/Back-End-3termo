<?php

namespace App\Observers;

use App\Models\Pedido;

class PedidoObserver
{
    public function saving(Pedido $pedido)
    {
        // Calcula o valor total baseado nos itens
        $pedido->valor_total = $pedido->calcularValorTotal();
    }
}