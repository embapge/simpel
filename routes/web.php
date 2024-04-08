<?php

use App\Http\Controllers\DocumentController;
use App\Livewire\Transaction\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get("/customer", function () {
        return view("customer.index");
    })->name("customer");

    Route::get("/document", function () {
        return view("document.index");
    })->name("document");

    Route::prefix('transaction')->group(function () {
        Route::get("/", function () {
            return view("transaction.index");
        })->name("transaction");

        Route::get("/{transaction}/detail", Detail::class)->name("transaction.detail");
    });

    Route::controller(DocumentController::class)->group(function () {
        Route::prefix("document")->group(function () {
            Route::name("document.")->group(function () {
                Route::post("preview", "stream")->name("preview");
            });
        });
    });
});
