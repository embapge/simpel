<?php

namespace App\Services\Midtrans;

use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BCAService
{
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function updateTransactionStatus($status)
    {
        $value = null;
        if ($status == "pending") {
            $value = PaymentStatus::PENDING;
        } elseif ($status == "expire" || $status == "cancel") {
            $value = PaymentStatus::CANCEL;
        } elseif ($status == "settlement") {
            $value = PaymentStatus::PAID;
        }

        $this->payment->update([
            "status" => $value,
        ]);

        return $this;
    }

    public function createResponseTransaction($response)
    {
        $this->payment->transaction()->create([
            "response" => json_encode($response),
        ]);

        $this->payment->refresh();

        $this->updateTransactionStatus($response["transaction_status"]);

        return $this;
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
