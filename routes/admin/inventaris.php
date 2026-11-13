<?php
use App\Http\Controllers\Admin\Inventaris\AssetController;
use App\Http\Controllers\Admin\Inventaris\SupplyItemController;
use Illuminate\Support\Facades\Route;

Route::resource("assets", AssetController::class);
Route::get("loans", [AssetController::class,"loans"])->name("loans.index");
Route::post("loans/{loan}/approve", [AssetController::class,"approveLoan"])->name("loans.approve");
Route::post("loans/{loan}/return", [AssetController::class,"returnLoan"])->name("loans.return");
Route::resource("supply-items", SupplyItemController::class)->only(["index","create","store"]);
Route::get("stock-requests", [SupplyItemController::class,"requests"])->name("stock-requests.index");
Route::post("stock-requests/{stockRequest}/approve", [SupplyItemController::class,"approveRequest"])->name("stock-requests.approve");
Route::post("stock-requests/{stockRequest}/issue", [SupplyItemController::class,"issueRequest"])->name("stock-requests.issue");
Route::post("stock-requests/{stockRequest}/reject", [SupplyItemController::class,"rejectRequest"])->name("stock-requests.reject");
