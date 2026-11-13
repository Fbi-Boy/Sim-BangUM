<?php
use App\Http\Controllers\Pengemudi\TaskController;
use Illuminate\Support\Facades\Route;
Route::middleware(["auth","role:pengemudi,super_admin"])->prefix("pengemudi")->name("pengemudi.")->group(function () {
    Route::get("tasks", [TaskController::class,"index"])->name("tasks.index");
    Route::get("tasks/{task}", [TaskController::class,"show"])->name("tasks.show");
    Route::post("tasks/{task}/confirm", [TaskController::class,"confirm"])->name("tasks.confirm");
    Route::post("tasks/{task}/depart", [TaskController::class,"depart"])->name("tasks.depart");
    Route::post("tasks/{task}/return", [TaskController::class,"returnTrip"])->name("tasks.return");
});
