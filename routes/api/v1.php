<?php

use App\Http\Controllers\Api\V1\RegistrationController;
use App\Http\Controllers\Api\V1\TransactionTypeController;
use App\Http\Middleware\ValidateSignatureUrlMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/transaction-type', [TransactionTypeController::class, 'index'])->name('transaction.type');

Route::prefix("customer")->group(function () {
    Route::name("customer.")->group(function () {
        Route::controller(RegistrationController::class)->group(function () {
            Route::post('registration', "store")->name('registration');
            Route::middleware([ValidateSignatureUrlMiddleware::class])->group(function () {
                Route::post('registration/signature', "verification")->name('registration.verification');
                Route::post('registration/upload', "upload")->name('registration.upload');
            });
        });
    });
});
