<?php

namespace Database\Factories;

use App\Models\Guia;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalidaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tour_id' => Tour::factory(),
            'guia_id' => Guia::factory(),
            'fecha' => fake()->dateTimeBetween('+1 day', '+3 months')->format('Y-m-d'),
            'cupos_disponibles' => fake()->numberBetween(5, 30),
        ];
    }
}
