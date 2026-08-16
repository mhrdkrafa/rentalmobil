<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\VehicleBlackoutDate;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Check if a vehicle is available for the given date range.
     * 
     * @param int $vehicleId
     * @param string|Carbon $startDate
     * @param string|Carbon $endDate
     * @return bool
     */
    public function isAvailable(int $vehicleId, $startDate, $endDate): bool
    {
        $start = Carbon::parse($startDate)->format('Y-m-d');
        $end = Carbon::parse($endDate)->format('Y-m-d');

        // Check if there are any non-cancelled bookings overlapping the dates
        $bookingExists = Booking::where('vehicle_id', $vehicleId)
            ->whereNotIn('status', [BookingStatus::Cancelled->value, BookingStatus::Rejected->value])
            ->where(function ($query) use ($start, $end) {
                // start_date <= $end AND end_date >= $start
                $query->where('start_date', '<=', $end)
                      ->where('end_date', '>=', $start);
            })
            ->exists();

        if ($bookingExists) {
            return false;
        }

        // Check if there are any blackout dates overlapping
        $blackoutExists = VehicleBlackoutDate::where('vehicle_id', $vehicleId)
            ->where(function ($query) use ($start, $end) {
                $query->where('start_date', '<=', $end)
                      ->where('end_date', '>=', $start);
            })
            ->exists();

        if ($blackoutExists) {
            return false;
        }

        return true;
    }
}
