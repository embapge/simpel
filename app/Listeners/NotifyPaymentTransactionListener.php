<?php

namespace App\Listeners;

use App\Events\MidtransTransactionStatusEvent;
use App\Mail\NotifyPaymentTransactionMail;
use App\Models\Invoice;
use App\Notifications\MidtransStatusChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyPaymentTransactionListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MidtransTransactionStatusEvent $event): void
    {
        $event->payment->createdBy->notify(new MidtransStatusChange($event->payment));
        // Mail::to($event->payment->invoice->customer_email)->send(new NotifyPaymentTransactionMail($event->payment->invoice));
        // Mail::to("barata@ciptamedianusa.net")->send(new NotifyPaymentTransactionMail($event->payment->invoice));
    }
}
