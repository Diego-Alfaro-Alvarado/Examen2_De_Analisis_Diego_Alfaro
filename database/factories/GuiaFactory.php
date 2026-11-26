<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuiaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'telefono' => fake()->numerify('########'),
        ];
    }
}
