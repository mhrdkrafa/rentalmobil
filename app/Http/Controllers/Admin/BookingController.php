<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'vehicle.category']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('q') && $request->q !== '') {
            $query->where('booking_code', 'like', '%' . $request->q . '%')
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->q . '%');
                  });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'vehicle.category', 'payments', 'documents', 'driver']);
        $availableDrivers = Driver::where('status', 'available')->get(); // Basic check, ideally checking schedule
        
        return view('admin.bookings.show', compact('booking', 'availableDrivers'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled'
        ]);

        $booking->status = $request->status;
        $booking->save();

        // Update vehicle status based on booking status
        if ($request->status === 'active') {
            $booking->vehicle->status = 'rented';
            $booking->vehicle->save();
        } else if (in_array($request->status, ['completed', 'cancelled'])) {
            $booking->vehicle->status = 'available';
            $booking->vehicle->save();
        }

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    public function assignDriver(Request $request, Booking $booking)
    {
        if (!$booking->with_driver) {
            return back()->with('error', 'Booking ini tidak menggunakan layanan supir.');
        }

        $request->validate([
            'driver_id' => 'required|exists:drivers,id'
        ]);

        $booking->driver_id = $request->driver_id;
        $booking->save();

        return back()->with('success', 'Supir berhasil ditugaskan.');
    }

    public function complete(Request $request, Booking $booking, BookingService $bookingService)
    {
        $request->validate([
            'actual_end_date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $booking->status = 'completed';
            $booking->vehicle->status = 'available';
            $booking->vehicle->save();

            // Calculate late fee
            $lateFee = $bookingService->calculateLateFee($booking, $request->actual_end_date);
            
            if ($lateFee > 0) {
                // We add it to the total price or record it.
                // In a real system, we'd probably create a new 'payment' record for the late fee
                // Here we just update the booking total price and mark it as partially paid to indicate debt
                $booking->total_price += $lateFee;
                $booking->payment_status = 'partial';
            }

            $booking->save();

            DB::commit();
            return back()->with('success', 'Booking diselesaikan.' . ($lateFee > 0 ? " Terdapat denda keterlambatan sebesar Rp " . number_format($lateFee, 0, ',', '.') : ""));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan booking: ' . $e->getMessage());
        }
    }
}
