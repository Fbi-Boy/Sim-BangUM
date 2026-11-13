<?php
use App\Http\Controllers\Admin\BarangDaerah\BmdAssetController;
use Illuminate\Support\Facades\Route;
Route::resource("assets", BmdAssetController::class)->only(["index","create","store","show"]);
