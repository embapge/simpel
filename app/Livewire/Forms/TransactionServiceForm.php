<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use App\Models\TransactionService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionServiceForm extends Form
{
    public $id;
    public $name;
    public $price;
    public $description;

    public function rules()
    {
        return [
            'name' => "required",
            'price' => "required",
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'price.required' => 'Harga harus diisi.',
        ];
    }

    public function setService(TransactionService $service)
    {
        $this->fill([
            "id" => $service->id,
            "name" => $service->name,
            "price" => $service->price,
            "description" => $service->description,
        ]);

        return $this;
    }

    public function patch()
    {
        TransactionService::find($this->id)->update($this->only("name", "description", "price"));
    }

    public function store(Transaction $transaction)
    {
        $transaction->services()->create($this->only("name", "price", "description"));
    }

    public function destroy()
    {
        TransactionService::find($this->id)->delete();
        $this->reset();
    }
}
