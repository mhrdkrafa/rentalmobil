<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\VehicleBlackoutDate;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleAvailabilityController extends Controller
{
    /**
     * Check availability for a specific month
     */
    public function check(Request $request, Vehicle $vehicle)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get bookings
        $bookings = Booking::where('vehicle_id', $vehicle->id)
            ->whereNotIn('status', [BookingStatus::Cancelled->value, BookingStatus::Rejected->value])
            ->where('start_date', '<=', $endDate->format('Y-m-d'))
            ->where('end_date', '>=', $startDate->format('Y-m-d'))
            ->get(['start_date', 'end_date']);

        // Get blackout dates
        $blackouts = VehicleBlackoutDate::where('vehicle_id', $vehicle->id)
            ->where('start_date', '<=', $endDate->format('Y-m-d'))
            ->where('end_date', '>=', $startDate->format('Y-m-d'))
            ->get(['start_date', 'end_date', 'reason']);

        $bookedDates = [];
        
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_date)->max($startDate);
            $end = Carbon::parse($booking->end_date)->min($endDate);
            
            for ($date = $start; $date->lte($end); $date->addDay()) {
                $bookedDates[$date->format('Y-m-d')] = 'booked';
            }
        }

        foreach ($blackouts as $blackout) {
            $start = Carbon::parse($blackout->start_date)->max($startDate);
            $end = Carbon::parse($blackout->end_date)->min($endDate);
            
            for ($date = $start; $date->lte($end); $date->addDay()) {
                $bookedDates[$date->format('Y-m-d')] = 'maintenance';
            }
        }

        return response()->json([
            'vehicle_id' => $vehicle->id,
            'year' => $year,
            'month' => $month,
            'booked_dates' => $bookedDates
        ]);
    }
}
