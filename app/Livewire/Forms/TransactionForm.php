<?php

namespace App\Livewire\Forms;

use App\Enums\TransactionStatus;
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
    public Transaction $transaction;
    public TransactionServiceForm $service;
    public $customer_id;
    public $documents;
    public $services;
    public $transaction_sub_type_id;
    public $transactionSubType;
    public $number_display;
    public $total_bill;
    public $total;
    public $total_payment;
    public $excess_payment;
    public $status;

    public function rules()
    {
        return [
            'customer_id' => "required",
            'transaction_sub_type_id' => "required",
            'documents' => ["array", "required"],
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Pelanggan harus diisi.',
            'transaction_sub_type_id.required' => 'Jenis transaksi harus diisi.',
            'documents.required' => 'Dokumen harus diisi.',
        ];
    }

    public function mount()
    {
        $this->resetCustom();
    }

    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction->load(["customer", "subType", "documents", "services"]);
        $this->fill([
            "number_display" => $this->transaction->number_display
        ]);
    }

    public function store()
    {
        $this->validate();
        $transaction = Transaction::create($this->only("customer_id", "transaction_sub_type_id"));
        $transaction->documents()->attach($this->documents);
    }

    public function resetCustom()
    {
        $this->reset(["transaction_sub_type_id", "number_display", "total_bill", "total", "total_payment", "excess_payment", "status", "documents"]);
        $this->documents = [];
        $this->customer_id = "";
        $this->services = collect([]);
    }
}
