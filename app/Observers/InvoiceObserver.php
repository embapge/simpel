<?php

namespace App\Observers;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceObserver
{
    public function creating(Invoice $invoice): void
    {
        $invoice->due_date = Carbon::parse($invoice->issue_date)->addMonthNoOverflow()->format("Y-m-t H:i:s");
    }

    public function created(Invoice $invoice): void
    {
        //
    }

    public function updated(Invoice $invoice): void
    {
        //
    }

    public function deleted(Invoice $invoice): void
    {
        //
    }

    public function restored(Invoice $invoice): void
    {
        //
    }

    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
