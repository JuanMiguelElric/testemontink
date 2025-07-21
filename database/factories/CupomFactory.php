<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cupom;

class CupomFactory extends Factory
{
    protected $model = Cupom::class;

    public function definition()
    {
        return [
            'codigo' => strtoupper($this->faker->lexify('??????')),
            'desconto' => $this->faker->numberBetween(5, 50),
            'validade' => now()->addDays(rand(1, 30)),
        ];
    }
}
