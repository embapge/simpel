<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Events\MidtransTransactionStatusEvent;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Notifications\MidtransStatusChange;
use App\Services\Midtrans\BCAService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MidtransCheck extends Command
{
    protected $signature = 'midtrans:check {--queue=default}';

    protected $description = 'This command used checking transaction status';

    public function handle()
    {
        $payments = Payment::whereIn("status", ["pending", "in process"])->withWhereHas("transaction")->withWhereHas("invoice")->with(["createdBy"])->get();

        foreach ($payments as $payment) {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Basic " . base64_encode("SB-Mid-server-XxY-ABGTXeiVQzKfFVZQvViL" . ":"),
            ])->get("https://api.sandbox.midtrans.com/v2/{$payment->id}/status");

            $response = json_decode($response, true);

            if ($response["transaction_status"] == json_decode($payment->transaction->response, true)["transaction_status"]) {
                continue;
            }

            $payment = (new BCAService($payment))->createResponseTransaction($response)->getPayment();

            MidtransTransactionStatusEvent::dispatch($payment);
        }
    }
}
