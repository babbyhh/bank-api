<?php

namespace Database\Factories;

use App\Models\Contas;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContasFactory extends Factory
{
    protected $model = Contas::class;

    public function definition()
    {
        return [
            'numero_conta' => $this->faker->unique()->numberBetween(100000, 999999),
            'saldo' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}

