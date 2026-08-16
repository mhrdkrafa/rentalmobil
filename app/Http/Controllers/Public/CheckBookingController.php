<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CheckBookingController extends Controller
{
    public function index()
    {
        return view('public.booking.check');
    }

    public function check(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
            'phone' => 'required|string',
        ]);

        $booking = Booking::with(['vehicle.category', 'customer'])
            ->where('booking_code', $request->booking_code)
            ->whereHas('customer', function ($query) use ($request) {
                $query->where('phone', $request->phone);
            })
            ->first();

        if (!$booking) {
            return back()->withInput()->with('error', 'Pesanan tidak ditemukan. Pastikan Kode Booking dan Nomor WhatsApp sudah benar.');
        }

        return redirect()->route('public.booking.show', $booking->booking_code);
    }
}
