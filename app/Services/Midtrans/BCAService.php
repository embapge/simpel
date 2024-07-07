<?php

namespace App\Services\Midtrans;

use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\TransactionHistoriesStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BCAService
{
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function createResponseTransaction($response)
    {
        $paymentTransaction = $this->payment->transaction()->create([
            "response" => json_encode($response),
        ]);

        $this->payment->refresh();

        $value = null;
        if ($response["transaction_status"] == "pending") {
            $value = PaymentStatus::PENDING;

            if (Str::contains(Str::lower($response["status_message"]), 'success')) {
                $this->payment->invoice->transaction->histories()->create([
                    "status" => TransactionHistoriesStatus::PROGRESS,
                    "date" => now(),
                    "type" => "payment-created",
                    "description" => "Menunggu pembayaran pelanggan dengan nomor BCA VA: {$response['va_numbers'][0]['va_number']} dan nominal sebesar Rp. " . number_format($response['gross_amount'], 0, ",", "."),
                ]);
            }
        } elseif ($response["transaction_status"] == "expire") {
            $value = PaymentStatus::CANCEL;

            $this->payment->invoice->transaction->histories()->create([
                "status" => TransactionHistoriesStatus::PROGRESS,
                "date" => now(),
                "type" => "payment-expired",
                "description" => "Pembayaran Expired",
            ]);
        } elseif ($response["transaction_status"] == "settlement") {
            $value = PaymentStatus::PAID;

            $this->payment->invoice->transaction->histories()->create([
                "status" => TransactionHistoriesStatus::PROGRESS,
                "date" => now(),
                "type" => "payment-paid",
                "description" => "Pembayaran berhasil dilakukan sebesar Rp. " . number_format($this->payment->amount, 0, ",", "."),
            ]);
        }

        $this->payment->update([
            "status" => $value ?? $this->payment->status,
        ]);

        return $paymentTransaction;
    }

    public function notificationMessage()
    {
        $message = null;

        $transaction = json_decode($this->payment->transaction->response, true);

        if ($transaction["transaction_status"] == "expire") {
            $message = "Invoice: {$this->payment->invoice->number_display} telah expired";
        } elseif ($transaction["transaction_status"] == "settlement") {
            $message = "Invoice: {$this->payment->invoice->number_display} telah melakukan pembayaran sebesar Rp." . number_format($this->payment->amount, 0, ",", ".");
        } elseif ($transaction["transaction_status"] == "cancel") {
            $message = "Invoice: {$this->payment->invoice->number_display} telah dibatalkan";
        } elseif ($transaction["transaction_status"] == "pending") {
            $message = "Invoice: {$this->payment->invoice->number_display} dengan pembayaran sebesar Rp." . number_format($this->payment->amount, 0, ",", ".") . "sedang dalam proses pembayaran pelanggan";
        } else {
            $message = "Invoice: {$this->payment->invoice->number_display} Undefined";
        }

        return $message;
    }

    public function getPayment()
    {
        return $this->payment;
    }
}
