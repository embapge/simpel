<?php

use App\Http\Controllers\DocumentController;
use App\Livewire\Invoice\Detail as InvoiceDetail;
use App\Livewire\Invoice\Index as Invoice;
use App\Livewire\Verification\Index as Verification;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Transaction\Detail as TransactionDetail;
use App\Livewire\User\Index as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

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

        Route::get("/{transaction}/detail", TransactionDetail::class)->name("transaction.detail");
    });

    Route::prefix('invoice')->group(function () {
        Route::get("/", Invoice::class)->name("invoice");
        Route::get("/{invoice}/detail", InvoiceDetail::class)->name("invoice.detail");
        Route::get("/print", function () {
            return view("invoice.pdf");
        })->name("invoice.print");
    });

    Route::prefix('verification')->group(function () {
        Route::get("/", Verification::class)->name("verification");
    });

    Route::prefix('user')->group(function () {
        Route::get("/", User::class)->name("user");
    });

    Route::controller(DocumentController::class)->group(function () {
        Route::prefix("document")->group(function () {
            Route::name("document.")->group(function () {
                Route::post("preview", "stream")->name("preview");
            });
        });
    });
});
