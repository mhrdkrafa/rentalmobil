<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Enums\PaymentVerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'payment_type', 'method', 'amount', 'gateway_transaction_id', 'proof_file_path', 'status', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'payment_type' => PaymentType::class,
        'method' => PaymentMethod::class,
        'status' => PaymentVerificationStatus::class,
        'verified_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
