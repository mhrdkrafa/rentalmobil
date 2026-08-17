<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $message;
    public $bookingId;
    public $type;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phone, string $message, ?int $bookingId = null, string $type = 'general')
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->bookingId = $bookingId;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint');

        if (!$token) {
            Log::warning('Fonnte Token is missing, unable to send WA.');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($endpoint, [
                'target' => $this->phone,
                'message' => $this->message,
            ]);

            $success = $response->successful() && isset($response->json()['status']) && $response->json()['status'] == true;
            
            NotificationLog::create([
                'booking_id' => $this->bookingId,
                'channel' => 'whatsapp',
                'type' => $this->type,
                'recipient' => $this->phone,
                'payload' => $this->message,
                'status' => $success ? 'sent' : 'failed',
                'response' => $response->body()
            ]);

            if (!$success) {
                // Fallback to Email
                Log::error('Fonnte API Error: ' . $response->body());
                
                $booking = \App\Models\Booking::with('customer')->find($this->bookingId);
                if ($booking && $booking->customer->email) {
                    \Illuminate\Support\Facades\Mail::to($booking->customer->email)
                        ->send(new \App\Mail\FallbackNotificationMail($this->message, $this->type));
                }
            }

        } catch (\Exception $e) {
            NotificationLog::create([
                'booking_id' => $this->bookingId,
                'channel' => 'whatsapp',
                'type' => $this->type,
                'recipient' => $this->phone,
                'payload' => $this->message,
                'status' => 'failed',
                'response' => $e->getMessage()
            ]);
            
            Log::error('Fonnte Exception: ' . $e->getMessage());
        }
    }
}
