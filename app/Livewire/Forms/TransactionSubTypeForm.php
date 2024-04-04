<?php

namespace App\Livewire\Forms;

use App\Models\TransactionSubType;
use App\Models\TransactionType;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionSubTypeForm extends Form
{
    public $id = "";
    public $name = "";
    public $description = "";
    public TransactionTypeForm $transactionType;

    public function setSubType(TransactionSubType $subType)
    {
        $this->fill([
            "id" => $subType->id,
            "name" => $subType->name,
            "description" => $subType->description,
        ]);
    }
}
