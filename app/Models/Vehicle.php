<?php

namespace App\Models;

use App\Enums\FuelType;
use App\Enums\TransmissionType;
use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'plate_number', 'transmission', 'fuel_type', 'capacity', 'year', 'price_per_day', 'price_per_day_with_driver', 'deposit_amount', 'min_dp_percentage', 'description', 'status', 'location',
    ];

    protected $casts = [
        'transmission' => TransmissionType::class,
        'fuel_type' => FuelType::class,
        'status' => VehicleStatus::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    public function blackoutDates(): HasMany
    {
        return $this->hasMany(VehicleBlackoutDate::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
