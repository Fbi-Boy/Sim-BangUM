<?php
use App\Http\Controllers\Auditor\ReportViewerController;
use Illuminate\Support\Facades\Route;
Route::middleware(["auth","role:auditor,super_admin"])->prefix("auditor")->name("auditor.")->group(function () {
    Route::get("reports", [ReportViewerController::class,"index"])->name("reports.index");
    Route::get("reports/correspondence", [ReportViewerController::class,"correspondence"])->name("reports.correspondence");
    Route::get("reports/loans", [ReportViewerController::class,"loans"])->name("reports.loans");
    Route::get("reports/stock", [ReportViewerController::class,"stock"])->name("reports.stock");
    Route::get("reports/vehicles", [ReportViewerController::class,"vehicles"])->name("reports.vehicles");
    Route::get("reports/bookings", [ReportViewerController::class,"bookings"])->name("reports.bookings");
    Route::get("reports/travel", [ReportViewerController::class,"travel"])->name("reports.travel");
    Route::get("export/{type}", [ReportViewerController::class,"exportCsv"])->name("export");
});
