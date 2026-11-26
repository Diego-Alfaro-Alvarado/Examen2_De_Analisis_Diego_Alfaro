<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'precio_por_persona' => fake()->numberBetween(10000, 60000),
            'duracion_horas' => fake()->numberBetween(2, 8),
        ];
    }
}
