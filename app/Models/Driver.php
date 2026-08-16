<?php

namespace App\Models;

use App\Enums\DriverStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'license_number', 'status',
    ];

    protected $casts = [
        'status' => DriverStatus::class,
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
