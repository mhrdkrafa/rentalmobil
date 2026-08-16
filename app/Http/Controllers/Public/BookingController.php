<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreBookingRequest;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\BookingDocument;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function create(Vehicle $vehicle)
    {
        $vehicle->load('category');
        return view('public.booking.create', compact('vehicle'));
    }

    public function store(StoreBookingRequest $request, Vehicle $vehicle)
    {
        try {
            // Find or create customer
            $customer = Customer::updateOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'id_card_number' => $request->id_card_number,
                    'address' => $request->address,
                ]
            );

            // Prepare booking data
            $bookingData = $request->validated();
            $bookingData['vehicle_id'] = $vehicle->id;
            $bookingData['customer_id'] = $customer->id;

            // Use BookingService to create booking inside a transaction with locks
            $booking = $this->bookingService->createBooking($bookingData);

            // Handle Documents
            $this->storeDocument($booking, $request, 'ktp_file', 'ktp');
            
            if ($request->hasFile('sim_file')) {
                $this->storeDocument($booking, $request, 'sim_file', 'sim');
            }

            // Create Payment DP (unpaid initially)
            $booking->payments()->create([
                'payment_type' => 'dp',
                'method' => 'manual_transfer',
                'amount' => $booking->dp_amount,
                'status' => 'pending'
            ]);
            
            // Send Notification
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendNewBookingNotification($booking);

            return redirect()->route('public.booking.show', $booking->booking_code)
                ->with('success', 'Pemesanan berhasil dibuat! Silakan unggah dokumen KTP/SIM Anda.');
                
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Booking failed: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($bookingCode)
    {
        $booking = Booking::with(['vehicle.category', 'customer'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        return view('public.booking.show', compact('booking'));
    }

    protected function storeDocument(Booking $booking, Request $request, string $fileKey, string $type)
    {
        if ($request->hasFile($fileKey)) {
            $path = $request->file($fileKey)->store('documents/' . $booking->booking_code, 'local');
            
            BookingDocument::create([
                'booking_id' => $booking->id,
                'type' => $type,
                'file_path' => $path,
            ]);
        }
    }
}
