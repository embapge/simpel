<?php

namespace App\Livewire\Forms;

use App\Enums\TransactionHistoriesStatus;
use App\Models\Transaction;
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

    public function store(Transaction $transaction, TransactionHistoriesStatus $status = TransactionHistoriesStatus::VERIFICATION, string $description)
    {
        $history = $transaction->histories()->create([
            "date" => now(),
            "status" => $status,
            "description" => $description,
        ]);

        $this->fill($history);

        return $history;
    }
}
