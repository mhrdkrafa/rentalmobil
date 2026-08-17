<?php
use App\Models\Booking;
$b = Booking::where('booking_code', 'RC-20260817-0001')->first();
if ($b) {
    $b->payment_status = 'paid_full';
    $b->status = 'completed';
    $b->save();
    $p = $b->payments()->where('status', 'pending')->latest()->first();
    if ($p) {
        $p->status = 'verified';
        $p->verified_at = now();
        $p->save();
    }
}
echo "OK\n";
