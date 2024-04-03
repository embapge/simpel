<?php

namespace App\Livewire\Forms;

use App\Models\TransactionService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionServiceForm extends Form
{
    public $id;
    public $name;
    public $price;
    public $description;

    public function mount()
    {
        $this->resetCustom();
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

    public function resetCustom()
    {
        $this->fill([
            "id" => "",
            "name" => "",
            "price" => "",
            "description" => "",
        ]);
    }
}
