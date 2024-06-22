<?php

namespace App\Livewire\Forms;

use App\Enums\TransactionStatus;
use App\Events\TransactionFindNumberEvent;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\TransactionSubType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public $id = "";
    public $customer_id = "";
    // public $documents_id = [];
    // public $services_id = [];
    // public $histories_id = [];
    public $transaction_sub_type_id = "";
    public $number_display = "";
    public $total_bill = "";
    public $total = "";
    public $total_payment = "";
    public $excess_payment = "";
    public $status = "";
    public $internal_note = "";
    public $transaction = "";
    public $transactionSubType = "";

    public function rules()
    {
        return [
            'customer_id' => "required",
            'transaction_sub_type_id' => "required",
            // 'documents_id' => ["array", "required"],
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Pelanggan harus diisi.',
            'transaction_sub_type_id.required' => 'Jenis transaksi harus diisi.',
            // 'documents_id.required' => 'Dokumen harus diisi.',
        ];
    }

    public function mount()
    {
        // $this->resetCustom();
    }

    public function setTransaction(Transaction $transaction)
    {
        $transaction->fresh();
        $this->transaction = $transaction;
        $this->fill([
            "id" => $transaction->id,
            "customer_id" => $transaction->customer_id,
            "transaction_sub_type_id" => $transaction->transaction_sub_type_id,
            // "documents_id" => $transaction->documents->pluck("id"),
            // "services_id" => $transaction->services->pluck("id"),
            // "histories_id" => $transaction->histories->pluck("id"),
            "number_display" => $transaction->number_display,
            "total_bill" => $transaction->total_bill,
            "total" => $transaction->total,
            "total_payment" => $transaction->total_payment,
            "excess_payment" => $transaction->excess_payment,
            "status" => $transaction->status,
            "internal_note" => $transaction->internal_note,
        ]);
    }

    // Pembuatan transaksi baru
    public function store()
    {
        $this->validate();
        $transaction = Transaction::create($this->only("customer_id", "transaction_sub_type_id", "internal_note"));
        // $transaction->documents()->attach($this->documents_id);
        $this->reset();
        return $transaction;
    }

    public function patch()
    {
        $this->transaction->update($this->only("customer_id", "transaction_sub_type_id", "number_display", "internal_note", "status"));
    }

    public function calculate()
    {
        $this->transaction->calculate();

        $this->fill([
            "total" => $this->transaction->total,
            "total_bill" => $this->transaction->total_bill,
            "total_payment" => $this->transaction->total_payment,
            "excess_payment" => $this->transaction->excess_payment,
        ]);
    }

    public function resetCustom()
    {
        $this->reset();
    }

    public function generateInvoice()
    {
        $this->transaction->invoices()->create([
            "customer_name" => $this->transaction->customer->name,
            "customer_pic_name" => $this->transaction->customer->pic_name,
            "customer_address" => $this->transaction->customer->address,
        ]);
    }

    public function generateNumber()
    {
        TransactionFindNumberEvent::dispatch($this->transaction->id);
    }
}
