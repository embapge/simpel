<?php

namespace App\Livewire\Document;

use App\Livewire\Forms\DocumentForm;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CreateForm extends Component
{
    protected $listeners = ["documentResetForm" => '$this->form->resetCustom()'];
    public DocumentForm $form;

    public function mount()
    {
        $this->form->mount();
    }

    public function save()
    {
        $this->form->store();
        $this->form->resetCustom();
        $this->dispatch("documentRefreshTable");
        $this->js("$('#documentCreateModal').modal('hide')");
        Toaster::success('Dokumen berhasil dibuat');
    }

    public function render()
    {
        return view('livewire.document.create-form');
    }
}
