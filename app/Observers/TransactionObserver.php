<?php

namespace App\Observers;

use App\Enums\TransactionHistoriesStatus;
use App\Livewire\Forms\TransactionHistoriesForm;
use App\Models\Transaction;
use Livewire\Component;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $transaction->histories()->create([
            "status" => TransactionHistoriesStatus::PROGRESS,
            "description" => "Admin sedang memproses transaksi",
        ]);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
