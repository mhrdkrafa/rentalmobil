<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'id_card_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'with_driver' => 'required|boolean',
            'pickup_location' => 'nullable|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'ktp_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sim_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'start_date.required' => 'Tanggal mulai sewa wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu.',
            'end_date.required' => 'Tanggal selesai sewa wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'ktp_file.required' => 'Dokumen KTP wajib diunggah.',
            'ktp_file.mimes' => 'Format KTP harus berupa gambar (JPG/PNG) atau PDF.',
            'ktp_file.max' => 'Ukuran KTP maksimal 2MB.',
            'sim_file.mimes' => 'Format SIM harus berupa gambar (JPG/PNG) atau PDF.',
            'sim_file.max' => 'Ukuran SIM maksimal 2MB.',
        ];
    }
}
