<?php

use App\Http\Controllers\Admin\Kendaraan\VehicleController;
use Illuminate\Support\Facades\Route;

Route::resource('vehicles', VehicleController::class);
Route::get('requests', [VehicleController::class, 'requests'])->name('requests.index');
Route::post('requests/{vehicleRequest}/approve', [VehicleController::class, 'approveRequest'])->name('requests.approve');
Route::post('requests/{vehicleRequest}/reject', [VehicleController::class, 'rejectRequest'])->name('requests.reject');
