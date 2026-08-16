<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bookings that have been pending (unpaid DP) for more than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limitTime = Carbon::now()->subHours(24);

        $bookings = Booking::where('status', BookingStatus::Pending->value)
            ->where('payment_status', PaymentStatus::Unpaid->value)
            ->where('created_at', '<', $limitTime)
            ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            $booking->update([
                'status' => BookingStatus::Cancelled->value,
                'cancelled_reason' => 'Otomatis dibatalkan oleh sistem karena melewati batas waktu 24 jam untuk pembayaran DP.',
            ]);
            $count++;
        }

        $this->info("Successfully cancelled {$count} pending bookings.");
    }
}
