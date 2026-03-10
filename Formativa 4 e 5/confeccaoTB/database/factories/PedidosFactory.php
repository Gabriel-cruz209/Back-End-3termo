<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedidos>
 */
class PedidosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_pedido'=> fake()->text,
            'valor_total'=> fake()->randomFloat(2, 10, 5000),
            'status'=> fake()->text,
            'metodo_pagamento'=> fake()->text,
            'observacoes'=> fake()->text
        ];
    }
}
