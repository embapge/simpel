<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\TransactionForm;
use App\Livewire\Forms\TransactionServiceForm;
use App\Models\Transaction;
use App\Models\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Livewire\Transaction\Detail as DetailInitialize;
use Illuminate\Support\Arr;
use Masmerise\Toaster\Toaster;

#[Layout('components.layout')]
class Detail extends Component
{
    public Transaction $transaction;
    public TransactionForm $form;
    public CustomerForm $customer;
    public $transactionServices = [];
    public $number_display = "";
    public $parentCheckbox = false;
    public $services;
    public $checkedServices = [];
    public $editMode = false;

    public function mount(Transaction $transaction)
    {
        $this->form->setTransaction($transaction);
        $this->customer->setCustomer($transaction->customer);
        $this->transactionServices = $transaction->services->isNotEmpty() ? $transaction->services->map(fn ($service, $idx) => (new TransactionServiceForm($this, "transactionService"))->setService($service)) : collect([]);
        $this->form->calculate();
        // $this->transactionServices = collect([]);
    }

    public function addService()
    {
        $this->transactionServices->push((new TransactionServiceForm($this, "transactionService")));
    }

    public function removeService($idx)
    {
        $this->transactionServices->pull($idx);
    }

    public function modeEdit()
    {
        $this->editMode = !$this->editMode;
    }

    public function checkboxParent()
    {
        if ($this->parentCheckbox) {
            $this->checkedServices = Arr::pluck($this->transactionServices, "id");
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

        if (count($this->checkedServices) > 0 && count($this->checkedServices) < count($this->transactionServices)) {
            $this->js("$('#parentCheck').prop('indeterminate', true)");
        } else if (count($this->checkedServices) == count($this->transactionServices)) {
            $this->js("$('#parentCheck').prop('checked', true)");
        } else {
            $this->js("$('#parentCheck').prop('checked', false)");
        }
    }

    public function save()
    {
        foreach ($this->transactionServices as $service) {
            if (!$service->id) {
                $service->store($this->transaction);
            } else {
                $service->patch();
            }
        }

        $this->form->calculate();
        $this->reset("editMode");
        $this->mount($this->transaction);
        Toaster::success("Data berhasil diubah");
    }

    public function destroyService($id)
    {
        try {
            $this->transactionServices->where("id", $id)->first()->destroy();
            $this->transactionServices = $this->transactionServices->whereNotNull("id");
            $this->form->calculate();
            Toaster::success("Data layanan berhasil dihapus");
        } catch (\Throwable $th) {
            Toaster::error("Data layanan tidak ditemukan");
        }
    }
}
