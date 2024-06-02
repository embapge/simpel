<?php

namespace App\Listeners;

use App\Events\InvoiceFindNumberEvent;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class GenerateNumberInvoiceListener implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    use InteractsWithQueue;

    public $tries = 1;
    public Invoice $invoice;

    public function __construct()
    {
        //
    }

    public function handle(InvoiceFindNumberEvent $event): void
    {
        $this->invoice = Invoice::find($event->invoiceId);
        (new InvoiceService)->setInvoice($this->invoice)->generateNumberDisplay($this->invoice->created_at->format("Y"));
    }

    public function failed(InvoiceFindNumberEvent $event, Throwable $exception): void
    {
        // ...
    }
}
