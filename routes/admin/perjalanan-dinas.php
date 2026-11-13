<?php
use App\Http\Controllers\Admin\PerjalananDinas\TravelRequestController;
use Illuminate\Support\Facades\Route;

Route::get("requests", [TravelRequestController::class,"index"])->name("requests.index");
Route::get("requests/{travelRequest}", [TravelRequestController::class,"show"])->name("requests.show");
Route::post("requests/{travelRequest}/approve", [TravelRequestController::class,"approve"])->name("requests.approve");
Route::post("requests/{travelRequest}/reject", [TravelRequestController::class,"reject"])->name("requests.reject");
