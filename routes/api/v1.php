<?php

use App\Http\Controllers\Api\V1\RegistrationController;
use App\Http\Controllers\Api\V1\TransactionHistoryController;
use App\Http\Controllers\Api\V1\TransactionTypeController;
use App\Http\Middleware\ValidateSignatureUrlMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/transaction-type', [TransactionTypeController::class, 'index'])->name('transaction.type');

Route::prefix("customer")->group(function () {
    Route::name("customer.")->group(function () {

        Route::prefix("registration")->group(function () {
            Route::name("registration.")->group(function () {
                Route::controller(RegistrationController::class)->group(function () {
                    Route::post('/', "store")->name('registration');
                    Route::middleware([ValidateSignatureUrlMiddleware::class])->group(function () {
                        Route::post('signature', "verification")->name('verification');
                        Route::post('upload', "upload")->name('upload');
                    });
                });
            });
        });

        Route::prefix("transaction")->group(function () {
            Route::name("transaction.")->group(function () {

                Route::name("histories.")->group(function () {
                    Route::controller(TransactionHistoryController::class)->group(function () {
                        Route::get('{transaction}/histories', "show")->name('show');
                    });
                });
            });
        });
    });
});
