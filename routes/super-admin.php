<?php
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','role:super_admin'])
    ->prefix('admin')->name('super-admin.')
    ->group(function () {
        Route::get('users', [UserController::class,'index'])->name('users.index');
        Route::get('users/{user}/edit', [UserController::class,'edit'])->name('users.edit');
        Route::put('users/{user}/roles', [UserController::class,'updateRoles'])->name('users.roles.update');
        Route::get('roles', [RoleController::class,'index'])->name('roles.index');
        Route::get('roles/create', [RoleController::class,'create'])->name('roles.create');
        Route::post('roles', [RoleController::class,'store'])->name('roles.store');
        Route::get('activity-log', [ActivityLogController::class,'index'])->name('activity-log.index');
    });
