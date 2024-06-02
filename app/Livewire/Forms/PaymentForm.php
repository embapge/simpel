<?php

namespace App\Livewire\Forms;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Enums\PaymentBank;
use Carbon\Carbon;
use Seld\PharUtils\Timestamps;

class PaymentForm extends Form
{
    public $id;
    public $invoice_id;
    public $amount = 0;
    public $expireTime = 120;
    public $created_at;

    public function setPayment($payment)
    {
        $this->fill($payment);
    }

    public function store(InvoiceForm $invoice)
    {
        $this->fill([
            "invoice_id" => $invoice->id,
            "amount" => $invoice->total,
        ]);

        $payment = Payment::create($this->all());

        $this->fill([
            "id" => $payment->id,
            "created_at" => $payment->created_at->format("Y-m-d H:i:s"),
        ]);

        return $this;
    }
}
