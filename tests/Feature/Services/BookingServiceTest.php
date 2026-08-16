<?php

namespace Tests\Feature\Services;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VehicleCategory $category;
    protected Vehicle $vehicle;
    protected Customer $customer;
    protected AvailabilityService $availabilityService;
    protected BookingService $bookingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = VehicleCategory::factory()->create();
        $this->vehicle = Vehicle::factory()->create([
            'category_id' => $this->category->id,
            'price_per_day' => 200000,
            'price_per_day_with_driver' => 350000,
            'min_dp_percentage' => 30,
        ]);
        $this->customer = Customer::factory()->create();
        $this->availabilityService = new AvailabilityService();
        $this->bookingService = new BookingService($this->availabilityService);
    }

    public function test_calculates_correct_total_price_without_driver()
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'customer_id' => $this->customer->id,
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'with_driver' => false,
        ];

        $booking = $this->bookingService->createBooking($data);

        // 3 days inclusive
        $this->assertEquals(3, $booking->total_days);
        $this->assertEquals(200000, $booking->price_per_day);
        $this->assertEquals(600000, $booking->total_price);
        $this->assertEquals(180000, $booking->dp_amount);
    }

    public function test_calculates_correct_total_price_with_driver()
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'customer_id' => $this->customer->id,
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'with_driver' => true,
            'driver_id' => null, // Assuming driver is assigned later
        ];

        $booking = $this->bookingService->createBooking($data);

        // 2 days inclusive
        $this->assertEquals(2, $booking->total_days);
        $this->assertEquals(350000, $booking->price_per_day);
        $this->assertEquals(700000, $booking->total_price);
        $this->assertEquals(210000, $booking->dp_amount);
    }

    public function test_prevents_double_booking_race_condition_via_overlapping_dates()
    {
        $data1 = [
            'vehicle_id' => $this->vehicle->id,
            'customer_id' => $this->customer->id,
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'with_driver' => false,
        ];

        // First booking succeeds
        $this->bookingService->createBooking($data1);

        $data2 = [
            'vehicle_id' => $this->vehicle->id,
            'customer_id' => $this->customer->id,
            'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'with_driver' => false,
        ];

        // Should throw Exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Kendaraan tidak tersedia pada tanggal yang dipilih.');
        
        $this->bookingService->createBooking($data2);
    }
}
