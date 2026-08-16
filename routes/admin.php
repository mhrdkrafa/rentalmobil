<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleCategoryController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleImageController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('vehicle-categories', VehicleCategoryController::class);

Route::resource('vehicles', VehicleController::class);
Route::get('vehicles/{vehicle}/images', [VehicleImageController::class, 'index'])->name('vehicles.images.index');
Route::post('vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])->name('vehicles.images.store');
Route::delete('vehicle-images/{image}', [VehicleImageController::class, 'destroy'])->name('vehicles.images.destroy');
Route::put('vehicle-images/{image}/primary', [VehicleImageController::class, 'setPrimary'])->name('vehicles.images.primary');
