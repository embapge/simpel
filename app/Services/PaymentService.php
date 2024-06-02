<?php

namespace App\Services;

use App\Enums\InvoiceType;
use App\Events\MidtransTransactionStatusEvent;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentService
{
    private Payment $payment;

    public function setPayment(Payment $payment)
    {
        $payment->refresh();
        $this->payment = $payment->load(["transaction", "invoice.transaction.customer"]);
        return $this;
    }

    public function store($request)
    {
        $payment = Payment::create($request);
        return $payment;
    }
}
