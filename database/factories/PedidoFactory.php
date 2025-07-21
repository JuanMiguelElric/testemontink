<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pedido;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'nome_cliente' => $this->faker->name(),
            'total' => $this->faker->randomFloat(2, 50, 500),
            'frete' => json_encode(['valor' => rand(10, 30), 'cep' => $this->faker->postcode()]),
            'status' => 0,
      
        ];
    }
}
