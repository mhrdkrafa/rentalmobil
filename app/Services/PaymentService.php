<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap Token for Midtrans
     */
    public function createSnapToken(Booking $booking, string $paymentType, float $amount): ?string
    {
        try {
            $orderId = $booking->booking_code . '-' . $paymentType . '-' . time();

            // Store payment record as pending
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_type' => $paymentType,
                'method' => 'gateway',
                'amount' => $amount,
                'gateway_transaction_id' => $orderId,
                'status' => 'pending',
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $amount,
                ],
                'customer_details' => [
                    'first_name' => $booking->customer->name,
                    'phone' => $booking->customer->phone,
                ],
                'item_details' => [
                    [
                        'id' => $booking->vehicle->id,
                        'price' => (int) $amount,
                        'quantity' => 1,
                        'name' => 'Sewa ' . $booking->vehicle->name . ' (' . strtoupper($paymentType) . ')',
                    ]
                ]
            ];

            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process Midtrans Webhook
     */
    public function handleWebhook(array $payload): bool
    {
        try {
            $notification = new Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            $payment = Payment::where('gateway_transaction_id', $orderId)->first();
            if (!$payment) {
                Log::warning('Midtrans Webhook: Order ID not found ' . $orderId);
                return false;
            }

            DB::beginTransaction();
            
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->status = 'pending';
                    } else {
                        $payment->status = 'verified';
                        $payment->verified_at = now();
                    }
                }
            } else if ($transaction == 'settlement') {
                $payment->status = 'verified';
                $payment->verified_at = now();
            } else if ($transaction == 'pending') {
                $payment->status = 'pending';
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $payment->status = 'failed';
            }

            $payment->save();

            // Update Booking status based on Payment
            if ($payment->status === 'verified') {
                $booking = $payment->booking;
                
                if ($payment->payment_type === 'dp') {
                    $booking->payment_status = 'dp_paid';
                    $booking->status = 'confirmed';
                } else if ($payment->payment_type === 'pelunasan') {
                    $booking->payment_status = 'paid_full';
                    $booking->status = 'confirmed';
                }
                
                $booking->save();
                
                // Send Notification
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->sendPaymentVerifiedNotification($booking);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Manual Transfer Payment
     */
    public function processManualPayment(Booking $booking, string $paymentType, float $amount, $file): Payment
    {
        $path = $file->store('payments', 'public');

        return Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => $paymentType,
            'method' => 'manual_transfer',
            'amount' => $amount,
            'proof_file_path' => $path,
            'status' => 'pending',
        ]);
    }
}
