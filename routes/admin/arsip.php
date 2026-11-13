<?php
use App\Http\Controllers\Admin\Arsip\ArchiveRecordController;
use Illuminate\Support\Facades\Route;

Route::get("classifications", [ArchiveRecordController::class,"classifications"])->name("classifications.index");
Route::post("classifications", [ArchiveRecordController::class,"storeClassification"])->name("classifications.store");
Route::resource("records", ArchiveRecordController::class)->only(["index","create","store","show"]);
Route::post("records/{record}/upload", [ArchiveRecordController::class,"upload"])->name("records.upload");
Route::get("records/{record}/download/{document}", [ArchiveRecordController::class,"download"])->name("records.download");
