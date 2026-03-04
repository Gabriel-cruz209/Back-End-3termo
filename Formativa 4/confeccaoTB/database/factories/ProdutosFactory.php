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
            'nome'=>fake()->text,
            'descricao'=>fake()->text,
            'preco_venda'=>fake()->randomFloat(2, 10, 5000),
            'preco_custo'=>fake()->randomFloat(2, 10, 5000),
            'codigo_barras'=>fake()->text,
        ];
    }
}
