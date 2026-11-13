<?php

use App\Http\Controllers\Shared\DashboardController;
use App\Http\Controllers\Shared\GlobalSearchController;
use App\Http\Controllers\Shared\NotificationController;
use App\Http\Controllers\Shared\ProfileController;
use App\Http\Controllers\Shared\WorkTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/widget', [DashboardController::class, 'widget'])->name('dashboard.widget');
Route::get('/dashboard/counters', [DashboardController::class, 'refreshCounters'])->name('dashboard.counters');

Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
});

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::put('/notifications', [ProfileController::class, 'updateNotificationPreferences'])->name('notifications.update');
});

Route::prefix('search')->name('search.')->group(function () {
    Route::get('/', [GlobalSearchController::class, 'index'])->name('index');
    Route::get('/quick', [GlobalSearchController::class, 'search'])->name('quick');
    Route::get('/results', [GlobalSearchController::class, 'results'])->name('results');
});

Route::prefix('my-tasks')->name('work-tasks.')->group(function () {
    Route::get('/', [WorkTaskController::class, 'index'])->name('index');
    Route::get('/{id}', [WorkTaskController::class, 'show'])->name('show');
    Route::post('/{id}/accept', [WorkTaskController::class, 'accept'])->name('accept');
    Route::post('/{id}/start', [WorkTaskController::class, 'start'])->name('start');
    Route::post('/{id}/complete', [WorkTaskController::class, 'complete'])->name('complete');
    Route::post('/{id}/block', [WorkTaskController::class, 'block'])->name('block');
    Route::post('/{id}/cancel', [WorkTaskController::class, 'cancel'])->name('cancel');
});
