<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VehicleCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['City Car', 'MPV', 'SUV', 'Luxury', 'Minibus']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
