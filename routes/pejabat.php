
<?php

use App\Http\Controllers\Pejabat\ApprovalInboxController;
use App\Http\Controllers\Pejabat\DispositionReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super_admin,kepala_bagian,kasubbag'])
    ->prefix('pejabat')
    ->name('pejabat.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Approval Inbox
        |--------------------------------------------------------------------------
        */
        Route::get('/approvals', [ApprovalInboxController::class, 'index'])
            ->name('approvals.index');

        Route::post('/approvals/{type}/{id}', [ApprovalInboxController::class, 'decide'])
            ->name('approvals.decide');

        /*
        |--------------------------------------------------------------------------
        | Disposition Reviews
        |--------------------------------------------------------------------------
        */
        Route::prefix('reviews')
            ->name('reviews.')
            ->group(function () {

                Route::get('/', [DispositionReviewController::class, 'index'])
                    ->name('index');

                Route::get('/{disposition}', [DispositionReviewController::class, 'show'])
                    ->name('show');

                Route::post('/{disposition}/approve', [DispositionReviewController::class, 'approve'])
                    ->name('approve');

                Route::post('/{disposition}/reject', [DispositionReviewController::class, 'reject'])
                    ->name('reject');
            });
    });
