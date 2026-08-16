<?php

namespace App\Http\Requests\Admin;

use App\Enums\FuelType;
use App\Enums\TransmissionType;
use App\Enums\VehicleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:vehicle_categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number'],
            'transmission' => ['required', new Enum(TransmissionType::class)],
            'fuel_type' => ['required', new Enum(FuelType::class)],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'price_per_day_with_driver' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'min_dp_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(VehicleStatus::class)],
            'location' => ['nullable', 'string', 'max:150'],
            'images.*' => ['nullable', 'image', 'max:2048'], // For multi-upload on create
        ];
    }
}