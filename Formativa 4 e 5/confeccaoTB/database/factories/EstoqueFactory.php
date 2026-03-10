<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estoque>
 */
class EstoqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome_produto' => fake()->word(),
            'nome_fornecedor' => fake()->company(),
            'quantidade' => fake()->numberBetween(0, 100),
            'data_validade' => fake()->optional()->date(),
            'preco_custo' => fake()->randomFloat(2, 1, 1000),
        ];
    }
}
