<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produtos>
 */
class ProdutosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome'=>fake()->text(50),
            'descricao'=>fake()->text(50),
            'preco_venda'=>fake()->randomFloat(2, 10, 5000),
            'preco_custo'=>fake()->randomFloat(2, 10, 5000),
            'codigo_barras'=>fake()->text(10),
        ];
    }
}
