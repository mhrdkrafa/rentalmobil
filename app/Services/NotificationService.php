<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Setting;
use App\Jobs\SendWhatsAppNotification;

class NotificationService
{
    /**
     * Parse template with variables
     */
    public function parseTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    /**
     * Send new booking notification to customer
     */
    public function sendNewBookingNotification(Booking $booking)
    {
        $template = Setting::where('key', 'wa_template_new_booking')->value('value') 
            ?? "Halo {name}, pesanan rental mobil Anda dengan kode {booking_code} telah kami terima. Silakan lakukan pembayaran agar pesanan dapat diproses.";

        $message = $this->parseTemplate($template, [
            'name' => $booking->customer->name,
            'booking_code' => $booking->booking_code,
            'vehicle' => $booking->vehicle->name,
            'total' => 'Rp ' . number_format($booking->total_price, 0, ',', '.')
        ]);

        dispatch(new SendWhatsAppNotification(
            $booking->customer->phone, 
            $message, 
            $booking->id, 
            'new_booking'
        ));
    }

    /**
     * Send payment verified notification
     */
    public function sendPaymentVerifiedNotification(Booking $booking)
    {
        $template = Setting::where('key', 'wa_template_payment_verified')->value('value') 
            ?? "Halo {name}, pembayaran untuk pesanan {booking_code} telah diverifikasi. Terima kasih.";

        $message = $this->parseTemplate($template, [
            'name' => $booking->customer->name,
            'booking_code' => $booking->booking_code,
            'vehicle' => $booking->vehicle->name
        ]);

        dispatch(new SendWhatsAppNotification(
            $booking->customer->phone, 
            $message, 
            $booking->id, 
            'payment_verified'
        ));
    }
}
