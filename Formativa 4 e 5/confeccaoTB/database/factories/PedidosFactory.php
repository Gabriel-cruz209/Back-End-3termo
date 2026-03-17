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
            'numero_pedido'=> fake()->numberBetween(0, 100),
            'valor_total'=> fake()->randomFloat(2, 10, 5000),
            'status'=> fake()->boolean(),
            'metodo_pagamento'=> fake()->company(),
            'observacoes'=> fake()->text(100)
        ];
    }
}
