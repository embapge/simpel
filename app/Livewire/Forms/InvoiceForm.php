<?php

namespace App\Livewire\Forms;

use App\Enums\InvoiceType;
use App\Events\InvoiceFindNumberEvent;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InvoiceForm extends Form
{
    public Invoice $invoice;
    public $id = "";
    public $transaction_id;
    public $number_display;
    public $type = InvoiceType::PRO;
    public $subtotal = 0.00;
    public $total = 0.00;
    public $total_bill = 0.00;
    public $total_payment = 0.00;
    public $excess_payment = 0.00;
    public $tax = 0.00;
    public $stamp = 0.00;
    public $customer_name;
    public $customer_pic_name;
    public $customer_address;
    public $customer_email;
    public $customer_phone_number;
    public $issue_date;
    public $due_date;
    public $internal_note;
    public $is_tax = 1;

    public function rules()
    {
        return [
            'transaction_id' => "required",
            'type' => "required",
            'issue_date' => "required",
            'customer_name' => "required",
            'customer_pic_name' => "required",
            'customer_address' => "required",
            'customer_email' => "required",
            'customer_phone_number' => "required",
        ];
    }

    public function messages()
    {
        return [
            'transaction_id.required' => "Nomor transaksi harus diisi",
            'type.required' => "Tipe harus diisi",
            'issue_date.required' => "Tanggal Pembuatan harus diisi",
            'customer_name.required' => "Nama Perusahaan harus diisi",
            'customer_pic_name.required' => "Nama PIC harus diisi",
            'customer_address.required' => "Alamat perusahaan harus diisi",
            'customer_email.required' => "Email perusahaan harus diisi",
            'customer_phone_number.required' => "Nomor Telepon perusahaan harus diisi",
        ];
    }

    public function setInvoice(Invoice $invoice)
    {
        $this->fill($invoice);
        $this->invoice = $invoice;
    }

    public function patch()
    {
        $this->invoice->update($this->only("internal_note", "stamp"));
        $this->invoice->calculate();
        $this->fill($this->invoice);
    }

    public function store(Transaction $transaction)
    {
        $transaction->load(["customer", "services"]);
        $this->fill([
            "transaction_id" => $transaction->id,
            "customer_name" => $transaction->customer->name,
            "customer_pic_name" => $transaction->customer->pic_name,
            "customer_address" => $transaction->customer->address,
            "customer_email" => $transaction->customer->email,
            "customer_phone_number" => $transaction->customer->phone_number,
            "issue_date" => now(),
        ]);

        $this->validate();

        $invoice = $transaction->invoices()->create($this->all());

        foreach ($transaction->services as $service) {
            $invoice->services()->create([
                "name" => $service->name,
                "description" => $service->description,
                "price" => $service->price,
            ]);
        }

        $invoice->calculate();
    }

    public function generateNumber()
    {
        InvoiceFindNumberEvent::dispatchIf($this->invoice->total > 0, $this->invoice->id);
    }

    public function updateTax($isTax = true)
    {
        $this->invoice->update([
            "is_tax" => $isTax
        ]);
        $this->invoice->calculate();
        $this->setInvoice($this->invoice);
    }

    public function calculate()
    {
        $this->invoice->calculate();
        $this->invoice->refresh();
        $this->setInvoice($this->invoice);
    }
}
