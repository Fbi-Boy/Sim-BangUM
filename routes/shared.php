
<?php

use App\Http\Controllers\Shared\DashboardController;
use App\Http\Controllers\Shared\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [
    DashboardController::class,
    'index'
])->name('dashboard');

Route::get('/reports', [
    ReportController::class,
    'index'
])
->name('reports.index')
->middleware('role:super_admin,kepala_bagian,kasubbag,auditor');

Route::get('/dashboard/test-admin', function () {
    return 'OK admin';
})->middleware('role:super_admin');
