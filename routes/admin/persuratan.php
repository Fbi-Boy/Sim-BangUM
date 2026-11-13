<?php
use App\Http\Controllers\Admin\Persuratan\IncomingLetterController;
use App\Http\Controllers\Admin\Persuratan\OutgoingLetterController;
use Illuminate\Support\Facades\Route;

Route::resource("incoming-letters", IncomingLetterController::class);
Route::post("incoming-letters/{incomingLetter}/dispositions", [IncomingLetterController::class,"storeDisposition"])->name("incoming-letters.dispositions.store");
Route::post("incoming-letters/{incomingLetter}/documents", [IncomingLetterController::class,"uploadDocument"])->name("incoming-letters.documents.upload");
Route::get("incoming-letters/{incomingLetter}/documents/{document}/download", [IncomingLetterController::class,"downloadDocument"])->name("incoming-letters.documents.download");
Route::delete("incoming-letters/{incomingLetter}/documents/{document}", [IncomingLetterController::class,"deleteDocument"])->name("incoming-letters.documents.delete");

Route::resource("outgoing-letters", OutgoingLetterController::class);
