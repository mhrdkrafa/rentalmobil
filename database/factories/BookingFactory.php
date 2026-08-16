<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $vehicle = Vehicle::factory()->create();
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(1, 5).' days');
        $totalDays = $startDate->diff($endDate)->days;
        $totalPrice = $vehicle->price_per_day * $totalDays;

        return [
            'booking_code' => 'RC-'.fake()->unique()->numerify('########-####'),
            'customer_id' => Customer::factory(),
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'price_per_day' => $vehicle->price_per_day,
            'total_price' => $totalPrice,
            'dp_amount' => $totalPrice * 0.3,
            'status' => BookingStatus::Confirmed->value,
            'payment_status' => PaymentStatus::DpPaid->value,
        ];
    }
}
