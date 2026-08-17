<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.customer'])
            ->where('method', 'manual_transfer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        if ($payment->status->value !== 'pending') {
            return back()->with('error', 'Status pembayaran sudah tidak pending.');
        }

        try {
            DB::beginTransaction();

            if ($request->action === 'approve') {
                $payment->status = 'verified';
                $payment->verified_by = auth()->id();
                $payment->verified_at = now();
                $payment->save();

                $booking = $payment->booking;
                if ($payment->payment_type->value === 'dp') {
                    $booking->payment_status = 'dp_paid';
                    $booking->status = 'confirmed';
                } else if ($payment->payment_type->value === 'pelunasan') {
                    $booking->payment_status = 'paid_full';
                    $booking->status = 'confirmed';
                }
                $booking->save();
                
                // Send Notification
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->sendPaymentVerifiedNotification($booking);
                
                $message = 'Pembayaran berhasil diverifikasi.';
            } else {
                $payment->status = 'failed';
                $payment->verified_by = auth()->id();
                $payment->verified_at = now();
                $payment->save();
                
                $message = 'Pembayaran ditolak.';
            }

            DB::commit();
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
