<?php
use App\Http\Controllers\Admin\Protokol\EventController;
use Illuminate\Support\Facades\Route;

Route::resource("events", EventController::class)->only(["index","create","store","show"]);
Route::post("events/{event}/agendas", [EventController::class,"storeAgenda"])->name("events.agendas.store");
Route::post("events/{event}/tasks", [EventController::class,"storeTask"])->name("events.tasks.store");
Route::post("tasks/{task}/complete", [EventController::class,"completeTask"])->name("tasks.complete");
