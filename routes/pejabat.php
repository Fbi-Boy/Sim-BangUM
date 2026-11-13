<?php
use App\Http\Controllers\Pejabat\ApprovalInboxController;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth","role:super_admin,kepala_bagian,kasubbag"])
    ->prefix("pejabat")->name("pejabat.")
    ->group(function () {
        Route::get("approvals", [ApprovalInboxController::class,"index"])->name("approvals.index");
        Route::post("approvals/{type}/{id}", [ApprovalInboxController::class,"decide"])->name("approvals.decide");
    });
