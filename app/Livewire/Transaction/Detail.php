<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\TransactionForm;
use App\Livewire\Forms\TransactionServiceForm;
use App\Models\Transaction;
use App\Models\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout')]
class Detail extends Component
{
    public Transaction $transaction;
    public TransactionForm $form;
    public TransactionServiceForm $transactionServices;
    public CustomerForm $customer;
    public $transactionServicesWrapper = [];
    public $number_display = "23123231";
    public $parentCheckbox = false;
    public $services;
    public $checkedServices = [];
    public $editMode = false;

    public function mount(Transaction $transaction)
    {
        $this->form->setTransaction($transaction);
        $this->customer->setCustomer($transaction->customer);
        $this->transactionServicesWrapper = $transaction->services->map(fn ($service, $idx) => $this->transactionServices->setService($service));
        $this->services = collect();
        // $this->services = collect([["id" => "", "name" => "Bacott", "description" => "wayaw", "price" => 600000], ["id" => "u3204123204832", "name" => "Bacot123", "description" => "wayaw", "price" => 600000]]);
    }

    public function addService()
    {
        $this->services->push(["name" => "", "description" => "", "price" => 0]);
    }

    public function removeService($id)
    {
        $this->services->pull($id);
    }

    public function modeEdit()
    {
        $this->editMode = !$this->editMode;
    }

    public function checkboxParent()
    {
        if ($this->parentCheckbox) {
            $this->checkedServices = $this->form->services->pluck("id");
        } else {
            $this->checkedServices = [];
        }
    }

    public function clickService()
    {
        $this->checkboxDisplay();
    }

    public function render()
    {
        return view('livewire.transaction.detail');
    }

    public function checkboxDisplay()
    {
        $this->js("$('#parentCheck').prop('indeterminate', false)");
        $this->js("$('#parentCheck').prop('checked', false)");

        if (count($this->checkedServices) > 0 && count($this->checkedServices) < count($this->services)) {
            $this->js("$('#parentCheck').prop('indeterminate', true)");
        } else if (count($this->checkedServices) == count($this->services)) {
            $this->js("$('#parentCheck').prop('checked', true)");
        } else {
            $this->js("$('#parentCheck').prop('checked', false)");
        }
    }

    public function save()
    {
        foreach ($this->services as $service) {
            TransactionService::create([
                "transaction_id" => "9bb426c1-e74e-4556-a598-68e61bd0348b",
                "name" => $service["name"],
                "description" => $service["description"],
                "price" => $service["price"],
            ]);
        }
        $this->services = [];
    }
}
