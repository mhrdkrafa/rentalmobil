<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Create a new booking transaction.
     * 
     * @param array $data Expected keys: vehicle_id, customer_id, start_date, end_date, with_driver, driver_id, pickup_location, dropoff_location, notes
     * @return Booking
     * @throws Exception
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // 1. Lock the vehicle row first to queue any concurrent booking attempts
            $vehicle = Vehicle::where('id', $data['vehicle_id'])->lockForUpdate()->firstOrFail();

            // 2. Check availability
            if (!$this->availabilityService->isAvailable($vehicle->id, $data['start_date'], $data['end_date'])) {
                throw new Exception('Kendaraan tidak tersedia pada tanggal yang dipilih.');
            }

            // 3. Calculate Days
            $start = Carbon::parse($data['start_date'])->startOfDay();
            $end = Carbon::parse($data['end_date'])->startOfDay();
            $totalDays = $start->diffInDays($end) + 1; // Inclusive of start and end date

            // 4. Determine Price
            $withDriver = $data['with_driver'] ?? false;
            if ($withDriver && empty($vehicle->price_per_day_with_driver)) {
                throw new Exception('Kendaraan ini tidak menyediakan layanan dengan supir.');
            }

            $pricePerDay = $withDriver ? $vehicle->price_per_day_with_driver : $vehicle->price_per_day;
            $totalPrice = $totalDays * $pricePerDay;
            $dpAmount = ($totalPrice * $vehicle->min_dp_percentage) / 100;

            // 5. Generate Booking Code
            $bookingCode = $this->generateBookingCode();

            // 6. Create Booking
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'customer_id' => $data['customer_id'],
                'vehicle_id' => $vehicle->id,
                'driver_id' => $withDriver ? ($data['driver_id'] ?? null) : null,
                'with_driver' => $withDriver,
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'pickup_location' => $data['pickup_location'] ?? null,
                'dropoff_location' => $data['dropoff_location'] ?? null,
                'total_days' => $totalDays,
                'price_per_day' => $pricePerDay,
                'total_price' => $totalPrice,
                'dp_amount' => $dpAmount,
                'paid_amount' => 0,
                'status' => BookingStatus::Pending,
                'payment_status' => PaymentStatus::Unpaid,
                'notes' => $data['notes'] ?? null,
            ]);

            return $booking;
        });
    }

    /**
     * Generate a unique booking code.
     * 
     * @return string
     */
    protected function generateBookingCode(): string
    {
        $datePrefix = 'RC-' . date('Ymd') . '-';
        
        // Find the latest booking code for today
        $latestBooking = Booking::where('booking_code', 'like', $datePrefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestBooking) {
            $number = 1;
        } else {
            $latestCode = $latestBooking->booking_code;
            $lastNumber = (int) substr($latestCode, -4);
            $number = $lastNumber + 1;
        }

        return $datePrefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate late fee based on actual end date
     * Grace period is 1 hour. Late fee is proportional per hour.
     */
    public function calculateLateFee(Booking $booking, string $actualEndDate): float
    {
        $expectedEnd = Carbon::parse($booking->end_date);
        $actualEnd = Carbon::parse($actualEndDate);

        if ($actualEnd->lessThanOrEqualTo($expectedEnd)) {
            return 0;
        }

        $hoursLate = $expectedEnd->diffInHours($actualEnd, false);

        // 1 hour grace period
        if ($hoursLate <= 1) {
            return 0;
        }

        // Daily rate / 24 * hours late
        $hourlyRate = $booking->price_per_day / 24;
        return round($hourlyRate * $hoursLate);
    }
}
