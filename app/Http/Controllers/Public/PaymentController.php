<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout($bookingCode)
    {
        $booking = Booking::with(['vehicle.category', 'customer'])->where('booking_code', $bookingCode)->firstOrFail();

        if ($booking->payment_status->value === 'paid_full') {
            return redirect()->route('public.booking.show', $bookingCode)->with('success', 'Pemesanan ini sudah lunas.');
        }

        // Determine what needs to be paid (DP or Full)
        if ($booking->payment_status->value === 'unpaid') {
            $paymentType = 'dp';
            $amountToPay = $booking->dp_amount;
        } else {
            $paymentType = 'pelunasan';
            $amountToPay = $booking->total_price - $booking->dp_amount;
        }

        // Generate Midtrans Snap Token
        $snapToken = $this->paymentService->createSnapToken($booking, $paymentType, $amountToPay);

        return view('public.payment.checkout', compact('booking', 'snapToken', 'paymentType', 'amountToPay'));
    }

    public function payManual(Request $request, $bookingCode)
    {
        $request->validate([
            'proof_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_type' => 'required|in:dp,pelunasan',
            'amount' => 'required|numeric'
        ]);

        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        $this->paymentService->processManualPayment(
            $booking,
            $request->payment_type,
            $request->amount,
            $request->file('proof_file')
        );

        return redirect()->route('public.booking.show', $bookingCode)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Tim kami akan segera melakukan verifikasi.');
    }

    public function downloadInvoice($bookingCode)
    {
        $booking = Booking::with(['customer', 'vehicle', 'payments' => function($query) {
            $query->where('status', 'verified');
        }])->where('booking_code', $bookingCode)->firstOrFail();

        if ($booking->payments->isEmpty()) {
            abort(404, 'Belum ada pembayaran yang terverifikasi untuk pesanan ini.');
        }

        $pdf = Pdf::loadView('pdf.invoice', compact('booking'));
        
        return $pdf->download('Invoice-' . $booking->booking_code . '.pdf');
    }
}
