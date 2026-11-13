<?php

use App\Http\Controllers\Admin\Fasilitas\RoomController;
use Illuminate\Support\Facades\Route;

Route::resource('rooms', RoomController::class);
Route::get('bookings', [RoomController::class, 'bookings'])->name('bookings.index');
Route::post('bookings/{booking}/approve', [RoomController::class, 'approveBooking'])->name('bookings.approve');
Route::post('bookings/{booking}/reject', [RoomController::class, 'rejectBooking'])->name('bookings.reject');
