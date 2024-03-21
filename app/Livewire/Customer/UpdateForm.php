<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Livewire\Attributes\Js;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateForm extends Component
{
    public CustomerForm $form;

    public function mount()
    {
        $this->form->resetCustom();
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

    #[On("customer-show")]
    public function show($customer)
    {
        $this->form->resetCustom();
        $this->form->setCustomer(Customer::with(["emails", "phones"])->where("id", $customer)->first());

        $this->js('
        flatpickr(document.querySelectorAll("#CustomerUpdateModal input[name=established]"), {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            defaultDate: "' . $this->form->established . '"
        }); 
        
        $("#CustomerUpdateModal").modal("show");');
    }

    public function update()
    {
        $this->form->patch();
        $this->dispatch("customerRefreshTable");
        $this->js("$('#CustomerUpdateModal').modal('hide')");
    }

    public function render()
    {
        return view('livewire.customer.update-form');
    }
}
