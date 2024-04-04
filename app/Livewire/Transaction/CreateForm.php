<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\TransactionForm;
use App\Models\Customer;
use App\Models\TransactionDocument;
use App\Models\TransactionDocumentTemplate;
use App\Models\TransactionSubType;
use App\Models\TransactionType;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CreateForm extends Component
{
    public TransactionForm $form;
    public CustomerForm $customer;
    public $transactionType = [];
    public $transactionDocumentTemplate = [];

    public function mount()
    {
        $this->transactionType = TransactionType::with(["subTypes"])->get()->toArray();
        $this->transactionDocumentTemplate = [];
    }

    public function changeDocument()
    {
        $documentTemplates = TransactionDocumentTemplate::withWhereHas("subTypes", function ($q) {
            $q->where("transaction_document_template_details.transaction_sub_type_id", $this->form->transaction_sub_type_id);
        })->with(["documents"])->get();
        $this->form->documents_id = $documentTemplates->pluck("documents")->collapse()->where("pivot.is_required", 1)->pluck("id")->toArray();
        $this->transactionDocumentTemplate = $documentTemplates->toArray();
    }

    #[On('transaction-customer-change')]
    public function changeCustomer()
    {
        $this->customer = new CustomerForm($this, "customer");
        $this->form->customer_id ? $this->customer->setCustomer(Customer::where("id", $this->form->customer_id)->first()) : "";
    }

    public function save()
    {
        $this->form->store();
        $this->customer->resetCustom();
        $this->dispatch("transactionRefreshTable");
        $this->js("$('select#customerId').val('').trigger('change')    ;$('#TransactionCreateModal').modal('hide')");
        Toaster::success('Transaksi berhasil dibuat');
        // $this->redirect("/customer");
    }
    public function render()
    {
        return view('livewire.transaction.create-form', ["transactionTypes" => $this->transactionType, "customers" => Customer::all()]);
    }
}
