<?php

namespace Database\Factories;

use App\Enums\FuelType;
use App\Enums\TransmissionType;
use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => VehicleCategory::factory(),
            'name' => fake()->randomElement(['Toyota Avanza', 'Honda Brio', 'Mitsubishi Xpander', 'Toyota Innova Zenix']),
            'plate_number' => fake()->unique()->bothify('B #### ???'),
            'transmission' => fake()->randomElement(TransmissionType::cases())->value,
            'fuel_type' => fake()->randomElement(FuelType::cases())->value,
            'capacity' => fake()->randomElement([4, 5, 7]),
            'year' => fake()->numberBetween(2018, 2024),
            'price_per_day' => fake()->randomElement([300000, 400000, 500000, 800000]),
            'price_per_day_with_driver' => fake()->randomElement([450000, 550000, 650000, 1000000]),
            'deposit_amount' => 500000,
            'min_dp_percentage' => 30,
            'description' => fake()->sentence(),
            'location' => 'Garasi Pusat',
        ];
    }
}
