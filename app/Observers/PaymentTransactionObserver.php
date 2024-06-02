<?php

namespace App\Observers;

use App\Models\PaymentTransaction;

class PaymentTransactionObserver
{
    /**
     * Handle the PaymentTransaction "created" event.
     */
    public function created(PaymentTransaction $paymentTransaction): void
    {
        //
    }

    /**
     * Handle the PaymentTransaction "updated" event.
     */
    public function updated(PaymentTransaction $paymentTransaction): void
    {
        //
    }

    /**
     * Handle the PaymentTransaction "deleted" event.
     */
    public function deleted(PaymentTransaction $paymentTransaction): void
    {
        //
    }

    /**
     * Handle the PaymentTransaction "restored" event.
     */
    public function restored(PaymentTransaction $paymentTransaction): void
    {
        //
    }

    /**
     * Handle the PaymentTransaction "force deleted" event.
     */
    public function forceDeleted(PaymentTransaction $paymentTransaction): void
    {
        //
    }
}
