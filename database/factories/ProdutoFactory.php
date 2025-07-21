<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produto;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word(),
            'preco' => $this->faker->randomFloat(2, 10, 500),
            'variacoestype' => $this->faker->randomElement(['Tamanho', 'Cor', 'Modelo']),
            'variacoes' => json_encode([
                ['nome' => $this->faker->word(), 'quantidade' => rand(1, 100)]
            ]),
        ];
    }
}
