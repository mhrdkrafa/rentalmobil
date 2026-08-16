<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name('male'),
            'phone' => fake()->unique()->numerify('08##########'),
            'license_number' => fake()->numerify('1###############'),
        ];
    }
}
