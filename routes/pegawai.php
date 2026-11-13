<?php
use App\Http\Controllers\Pegawai\AssetLoanRequestController;
use App\Http\Controllers\Pegawai\MyDispositionController;
use App\Http\Controllers\Pegawai\RoomBookingRequestController;
use App\Http\Controllers\Pegawai\StockRequestController;
use App\Http\Controllers\Pegawai\TravelRequestController;
use App\Http\Controllers\Pegawai\VehicleRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix("pegawai")->name("pegawai.")->group(function () {
    Route::resource("asset-loans", AssetLoanRequestController::class);
    Route::resource("vehicle-requests", VehicleRequestController::class);
    Route::resource("room-bookings", RoomBookingRequestController::class);
    Route::resource("stock-requests", StockRequestController::class);
    Route::resource("travel-requests", TravelRequestController::class);
    Route::get("dispositions", [MyDispositionController::class, "index"])->name("dispositions.index");
    Route::get("dispositions/{disposition}", [MyDispositionController::class, "show"])->name("dispositions.show");
    Route::post("dispositions/{disposition}/update", [MyDispositionController::class, "updateStatus"])->name("dispositions.update");
});
