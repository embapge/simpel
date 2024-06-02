<?php

namespace App\Services\Midtrans;

use App\Models\Bank;
use App\Services\PaymentTransactionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class ProcessWebhookJob extends SpatieProcessWebhookJob
{
    public function handle()
    {
        // $this->webhookCall; // contains an instance of `WebhookCall`

        // perform the work here
        (new PaymentTransactionService)->notification($this->webhookCall['payload']);
    }
}
