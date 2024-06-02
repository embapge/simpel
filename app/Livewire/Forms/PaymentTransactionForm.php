<?php

namespace App\Livewire\Forms;

use App\Enums\PaymentBank;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Services\PaymentService;
use App\Services\PaymentTransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Str;

class PaymentTransactionForm extends Form
{
    public $id;
    public $payment_id;
    public $response;

    // Optional for Display
    public $status;
    public $va_number;
    public $bank;
    public $expiry_time;
    public $transaction_time;
    public $settlement_time;

    public function setPaymentTransaction(PaymentTransaction $transaction)
    {
        $response = json_decode($transaction->response, true);
        $this->fill($transaction);
        $this->loadResponse($response);

        return $this;
    }

    public function store(PaymentForm $payment): bool
    {
        $this->payment_id = $payment->id;

        $payment = Payment::find($payment->id);

        $transaction = (new PaymentTransactionService)->store($payment);

        // Dimatikan sementara
        // $response = Http::withHeaders([
        //     "Accept" => "application/json",
        //     "Content-Type" => "application/json",
        //     "Authorization" => "Basic " . base64_encode("SB-Mid-server-XxY-ABGTXeiVQzKfFVZQvViL" . ":"),
        // ])->post('https://api.sandbox.midtrans.com/v2/charge', [
        //     "payment_type" => "bank_transfer",
        //     'transaction_details' => [
        //         'order_id' => $payment->id,
        //         'gross_amount' => $payment->amount,
        //     ], "bank_transfer" => [
        //         "bank" => "bca",
        //         "free_text" => [
        //             "inquiry" => [
        //                 [
        //                     "id" => "Perusahaan: " . Str::limit($paymentTransaction->payment->invoice->transaction->customer->name, 15),
        //                     "en" => "Company: " . Str::limit($paymentTransaction->payment->invoice->transaction->customer->name, 15)
        //                 ]
        //             ],
        //             "payment" => [
        //                 [
        //                     "id" => Str::limit($paymentTransaction->payment->invoice->transaction->customer->name, 15),
        //                     "en" => Str::limit($paymentTransaction->payment->invoice->transaction->customer->name, 15),
        //                 ]
        //             ]
        //         ],
        //     ], "custom_expiry" => [
        //         "order_time" => "{$payment->created_at} +0700",
        //         "expiry_duration" => $payment->expireTime,
        //         "unit" => "minute"
        //     ], "customer_details" => [
        //         "first_name" => $paymentTransaction->payment->invoice->transaction->customer->name,
        //         "last_name" => "",
        //         "email" => $paymentTransaction->payment->invoice->transaction->customer->email,
        //         "phone" => $paymentTransaction->payment->invoice->transaction->customer->phone_number,
        //     ],
        // ]);
        // $response = $response->json();
        // Dimatikan sementara

        $this->setPaymentTransaction($transaction);

        return true;
    }

    private function loadResponse($response)
    {
        if ($response['fraud_status'] === "deny") {
            return false;
        }

        $this->fill([
            "payment_id" => $response["transaction_id"],
            "expiry_time" => $response["expiry_time"],
            "transaction_time" => $response["transaction_time"] ?? null,
            "status" => $response["transaction_status"],
            "settlement_time" => $response["transaction_status"] == "settlement" ? $response["settlement_time"] : null,
        ]);

        if ($response['payment_type'] == "bank_transfer") {
            if (array_key_exists("permata_va_number", $response)) {
                // Permata
                $this->bank = PaymentBank::PERMATA;
                $this->va_number = $response["permata_va_number"];
            } else if (array_key_exists("biller_code", $response)) {
                // Mandiri
                $this->bank = PaymentBank::MANDIRI;
                $this->va_number = $response["biller_code"];
            } elseif (array_key_exists("va_numbers", $response)) {
                $this->fill([
                    "bank" => $response["va_numbers"][0]["bank"],
                    "va_number" => $response["va_numbers"][0]["va_number"],
                ]);
            }
        }
    }
}
