<?php

namespace App\Livewire\Forms;

use App\Enums\TransactionHistoriesStatus;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionHistoriesForm extends Form
{
    public $id;
    public $transaction_id;
    public $date;
    public TransactionHistoriesStatus $status;
    public $description;

    public function rules()
    {
        return [
            "transaction_id" => ["required"],
            "date" => ["required"],
            "status" => ["required"],
        ];
    }

    public function messages()
    {
        return [
            "transaction_id.required" => "Transaksi harus diisi",
            "date.required" => "Tanggal harus diisi",
            "status.required" => "Status harus diisi",
        ];
    }

    public function setHistory(TransactionHistory $history)
    {
        $this->fill([
            "id" => $history->id,
            "transaction_id" => $history->transaction_id,
            "date" => $history->date,
            "status" => TransactionHistoriesStatus::from($history->status),
            "description" => $history->description,
        ]);

        return $this;
    }

    public function store(Transaction $transaction, TransactionHistoriesStatus $status = TransactionHistoriesStatus::VERIFICATION, string $description)
    {
        $history = $transaction->histories()->create([
            "date" => now(),
            "status" => $status,
            "description" => $description,
        ]);

        $this->fill($history);

        return $this;
    }
}
