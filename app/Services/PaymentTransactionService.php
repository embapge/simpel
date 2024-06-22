<?php

namespace App\Services;

use App\Enums\InvoiceType;
use App\Events\MidtransTransactionStatusEvent;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Services\Midtrans\BCAService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentTransactionService
{
    private $paymentTransaction;

    public function setPaymentTransaction(PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->fresh();
        $this->paymentTransaction = $paymentTransaction;
        return $this;
    }

    public function store(Payment $payment)
    {
        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic " . base64_encode("SB-Mid-server-XxY-ABGTXeiVQzKfFVZQvViL" . ":"),
        ])->post('https://api.sandbox.midtrans.com/v2/charge', [
            "payment_type" => "bank_transfer",
            'transaction_details' => [
                'order_id' => $payment->id,
                'gross_amount' => $payment->amount,
            ], "bank_transfer" => [
                "bank" => "bca",
                "free_text" => [
                    "inquiry" => [
                        [
                            "id" => "Perusahaan: " . Str::limit($payment->invoice->customer_name, 15),
                            "en" => "Company: " . Str::limit($payment->invoice->customer_name, 15)
                        ]
                    ],
                    "payment" => [
                        [
                            "id" => Str::limit($payment->invoice->customer_name, 15),
                            "en" => Str::limit($payment->invoice->customer_name, 15),
                        ]
                    ]
                ],
            ], "custom_expiry" => [
                "order_time" => "{$payment->created_at} +0700",
                "expiry_duration" => 120,
                "unit" => "minute"
            ], "customer_details" => [
                "first_name" => $payment->invoice->customer_name,
                "last_name" => "",
                "email" => $payment->invoice->customer_email,
                "phone" => $payment->invoice->customer_phone_number,
            ],
        ]);

        $response = $response->json();

        if ($response["fraud_status"] === "deny") {
            $payment->delete();
            return false;
        }

        $payment->transaction()->create([
            "response" => json_encode($response)
        ]);

        $payment->fresh();

        MidtransTransactionStatusEvent::dispatch($payment);
        return $payment->transaction;
    }

    public function notification($response)
    {
        if ($response['fraud_status'] === "deny") {
            return false;
        }

        $payment = Payment::find($response["order_id"]);

        if (json_decode($payment->transaction->response, true)["transaction_status"] === $response["transaction_status"]) {
            return false;
        }

        if ($response['payment_type'] == "bank_transfer") {
            if (array_key_exists("permata_va_number", $response)) {
                // Permata
            } else if (array_key_exists("biller_code", $response)) {
                // Mandiri
            } elseif (array_key_exists("va_numbers", $response) && $response["va_numbers"][0]["bank"] == "bca") {
                (new BCAService(Payment::find($response["order_id"])))->createResponseTransaction($response);
            }
        }

        $payment->invoice->calculate();

        MidtransTransactionStatusEvent::dispatch($payment);
    }
}
