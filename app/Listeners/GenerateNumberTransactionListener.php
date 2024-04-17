<?php

namespace App\Listeners;

use App\Events\TransactionFindNumberEvent;
use App\Services\TransactionService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class GenerateNumberTransactionListener implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    use InteractsWithQueue;

    public $tries = 1;

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
    public function handle(TransactionFindNumberEvent $event): void
    {
        (new TransactionService)->setTransaction($event->transaction)->generateNumberDisplay($event->transaction->created_at->format("Y"));
    }

    public function failed(TransactionFindNumberEvent $event, Throwable $exception): void
    {
        // ...
    }
}
