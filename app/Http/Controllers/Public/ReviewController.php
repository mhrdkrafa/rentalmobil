<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $bookingCode)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        // Ensure booking is completed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Hanya pesanan yang sudah selesai yang dapat direview.');
        }

        // Check if already reviewed
        if (Review::where('booking_id', $booking->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan review untuk pesanan ini.');
        }

        Review::create([
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'vehicle_id' => $booking->vehicle_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_published' => true, // default published, admin can unpublish
        ]);

        return back()->with('success', 'Terima kasih! Review Anda telah berhasil disimpan.');
    }
}
