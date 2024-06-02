<?php

namespace App\Livewire\Forms;

use App\Models\Invoice;
use App\Models\InvoiceService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InvoiceServiceForm extends Form
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

    public function setService(InvoiceService $service)
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
        $this->validate();
        InvoiceService::find($this->id)->update($this->only("name", "description", "price"));
    }

    public function store(Invoice $invoice)
    {
        $this->validate();
        $invoice->services()->create($this->only("name", "price", "description"));
    }

    public function destroy()
    {
        InvoiceService::find($this->id)->delete();
    }
}
