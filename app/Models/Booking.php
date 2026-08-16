<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'customer_id', 'vehicle_id', 'driver_id', 'with_driver', 'start_date', 'end_date', 'pickup_location', 'dropoff_location', 'total_days', 'price_per_day', 'total_price', 'dp_amount', 'paid_amount', 'status', 'payment_status', 'notes', 'admin_notes', 'cancelled_reason', 'confirmed_by', 'confirmed_at',
    ];

    protected $casts = [
        'with_driver' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => BookingStatus::class,
        'payment_status' => PaymentStatus::class,
        'confirmed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BookingDocument::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function notificationLogs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }
}
