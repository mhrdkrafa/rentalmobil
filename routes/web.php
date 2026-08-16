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
Route::get('/booking/create/{vehicle}', [\App\Http\Controllers\Public\BookingController::class, 'create'])->name('public.booking.create');
Route::post('/booking/store/{vehicle}', [\App\Http\Controllers\Public\BookingController::class, 'store'])->name('public.booking.store');

// Reviews
Route::post('/booking/{booking_code}/review', [\App\Http\Controllers\Public\ReviewController::class, 'store'])->name('public.booking.review');
Route::get('/booking/success/{bookingCode}', [\App\Http\Controllers\Public\BookingController::class, 'show'])->name('public.booking.show');

// Payment Flow
Route::get('/payment/checkout/{bookingCode}', [\App\Http\Controllers\Public\PaymentController::class, 'checkout'])->name('public.payment.checkout');
Route::post('/payment/manual/{bookingCode}', [\App\Http\Controllers\Public\PaymentController::class, 'payManual'])->name('public.payment.manual');
Route::get('/payment/invoice/{bookingCode}', [\App\Http\Controllers\Public\PaymentController::class, 'downloadInvoice'])->name('public.payment.invoice');

// Check Booking Status
Route::get('/cek-booking', [CheckBookingController::class, 'index'])->name('public.booking.check');
Route::post('/cek-booking', [CheckBookingController::class, 'check'])->name('public.booking.check.process');


// Public API
Route::get('/api/availability/{vehicle}', [VehicleAvailabilityController::class, 'check'])->name('api.availability.check');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
