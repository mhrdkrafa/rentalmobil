<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\VehicleCatalogController;
use App\Http\Controllers\Api\VehicleAvailabilityController;

use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\CheckBookingController;

// Public Routes
Route::get('/', HomeController::class)->name('public.home');
Route::get('/katalog', [VehicleCatalogController::class, 'index'])->name('public.catalog.index');
Route::get('/katalog/{vehicle}', [VehicleCatalogController::class, 'show'])->name('public.catalog.show');

// Booking Flow
Route::get('/booking/create/{vehicle}', [BookingController::class, 'create'])->name('public.booking.create');
Route::post('/booking/store/{vehicle}', [BookingController::class, 'store'])->name('public.booking.store');
Route::get('/booking/success/{bookingCode}', [BookingController::class, 'show'])->name('public.booking.show');

// Check Booking Status
Route::get('/cek-booking', [CheckBookingController::class, 'index'])->name('public.booking.check');
Route::post('/cek-booking', [CheckBookingController::class, 'check'])->name('public.booking.check.process');


// Public API
Route::get('/api/availability/{vehicle}', [VehicleAvailabilityController::class, 'check'])->name('api.availability.check');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
