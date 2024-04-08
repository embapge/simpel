<?php

namespace App\Livewire\Customer;

use App\Enums\CustomerType;
use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;
use Riskihajar\Terbilang\Facades\Terbilang;

class CreateForm extends Component
{
    public CustomerForm $form;

    public function mount()
    {
        $this->form->mount();
    }

    public function addEmail()
    {
        $this->form->addEmail();
    }

    public function removeEmail($id)
    {
        $this->form->removeEmail($id);
    }

    public function addPhone()
    {
        $this->form->addPhone();
    }

    public function removePhone($id)
    {
        $this->form->removePhone($id);
    }

    // #[On("customer-show")]
    // public function show($customer)
    // {
    //     $this->form->resetCustom();
    //     $this->js("$('#CustomerUpdateModal').modal('show')");
    // }

    public function save()
    {
        $this->form->store();
        $this->form->resetCustom();
        $this->dispatch("customerRefreshTable");
        $this->js("$('#CustomerCreateModal').modal('hide')");
        Toaster::success('Customer berhasil dibuat');
    }

    public function render()
    {
        return view('livewire.customer.create-form', ["customers" => CustomerType::array()]);
    }
}
