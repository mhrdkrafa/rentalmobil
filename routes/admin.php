<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleCategoryController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleImageController;
use App\Http\Controllers\Admin\PaymentController;

Route::prefix('admin')->middleware(['auth', 'role:admin,super_admin,staff'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('vehicle-categories', VehicleCategoryController::class)->names('admin.vehicle-categories');
    Route::resource('vehicles', VehicleController::class)->names('admin.vehicles');
    Route::get('vehicles/{vehicle}/images', [VehicleImageController::class, 'index'])->name('admin.vehicles.images.index');
    Route::post('vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])->name('admin.vehicles.images.store');
    Route::post('vehicle-images/{image}/primary', [VehicleImageController::class, 'setPrimary'])->name('admin.vehicles.images.primary');
    Route::delete('vehicle-images/{image}', [VehicleImageController::class, 'destroy'])->name('admin.vehicles.images.destroy');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('admin.payments.verify');

    // Bookings
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('admin.bookings.show');
    Route::post('bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('admin.bookings.status');
    Route::post('bookings/{booking}/assign-driver', [\App\Http\Controllers\Admin\BookingController::class, 'assignDriver'])->name('admin.bookings.assign-driver');
    Route::post('bookings/{booking}/complete', [\App\Http\Controllers\Admin\BookingController::class, 'complete'])->name('admin.bookings.complete');

    // Reviews
    Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('reviews/{review}/toggle', [\App\Http\Controllers\Admin\ReviewController::class, 'togglePublish'])->name('admin.reviews.toggle');

    // Admin & Super Admin Only Routes
    Route::middleware(['role:admin,super_admin'])->group(function () {
        // Settings & Notifications
        Route::get('settings/notifications', [\App\Http\Controllers\Admin\SettingController::class, 'notifications'])->name('admin.settings.notifications');
        Route::post('settings/notifications', [\App\Http\Controllers\Admin\SettingController::class, 'updateNotifications'])->name('admin.settings.notifications.update');
        Route::get('settings/notification-logs', [\App\Http\Controllers\Admin\NotificationLogController::class, 'index'])->name('admin.notification-logs.index');

        // Customers
        Route::get('customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers.index');
        Route::get('customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('admin.customers.show');
        Route::post('customers/{customer}/blacklist', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleBlacklist'])->name('admin.customers.blacklist');

        // Reports
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('admin.reports.pdf');
    });
});
