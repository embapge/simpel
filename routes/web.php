<?php

use App\Http\Controllers\DocumentController;
use App\Livewire\Invoice\Detail as InvoiceDetail;
use App\Livewire\Invoice\Index as Invoice;
use App\Livewire\Transaction\Detail as TransactionDetail;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

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

        Route::get("/{transaction}/detail", TransactionDetail::class)->name("transaction.detail");
    });

    Route::prefix('invoice')->group(function () {
        Route::get("/", Invoice::class)->name("invoice");
        Route::get("/{invoice}/detail", InvoiceDetail::class)->name("invoice.detail");
        Route::get("/print", function () {
            return view("invoice.pdf");
        })->name("invoice.print");
    });

    Route::controller(DocumentController::class)->group(function () {
        Route::prefix("document")->group(function () {
            Route::name("document.")->group(function () {
                Route::post("preview", "stream")->name("preview");
            });
        });
    });

    // Route::get("test/mailable", function () {
    //     Mail::to("barata@ciptamedianusa.net")->send(new TestMail());
    //     dd("masuk");
    // });
});
