<?php

namespace App\Livewire\Transaction;

use App\Enums\TransactionHistoriesStatus;
use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\TransactionDocumentForm;
use App\Livewire\Forms\TransactionForm;
use App\Livewire\Forms\TransactionHistoriesForm;
use App\Models\Customer;
use App\Models\TransactionDocument;
use App\Models\TransactionDocumentTemplate;
use App\Models\TransactionSubType;
use App\Models\TransactionType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CreateForm extends Component
{
    public TransactionForm $form;
    public CustomerForm $customer;
    public $transactionType = [];
    public Collection $transactionDocumentTemplate;
    public $transactionDocuments = [];
    public $documents = [];

    public function mount()
    {
        $this->transactionType = TransactionType::with(["subTypes"])->get();
    }

    public function changeDocument()
    {
        $this->transactionDocumentTemplate = TransactionDocumentTemplate::withWhereHas("subTypes", function ($q) {
            $q->where("transaction_document_template_details.transaction_sub_type_id", $this->form->transaction_sub_type_id);
        })->with(["documents"])->get();

        $this->documents = $this->transactionDocumentTemplate->pluck("documents")->collapse()->where("pivot.is_required", 1)->pluck("id")->toArray();
    }

    #[On('transaction-customer-change')]
    public function changeCustomer()
    {
        $this->customer = new CustomerForm($this, "customer");
        $this->form->customer_id ? $this->customer->setCustomer(Customer::where("id", $this->form->customer_id)->first()) : "";
    }

    public function save()
    {
        $this->validate([
            'documents' => 'required|array',
            'form.customer_id' => 'required',
            'form.transaction_sub_type_id' => 'required',
        ], [
            "documents.required" => "Dokumen harus diisi",
            "form.customer_id.required" => "Pelanggan harus diisi",
            "form.transaction_sub_type_id.required" => "Jenis layanan harus diisi",
        ]);

        $transaction = $this->form->store();
        $this->transactionDocuments = collect($this->transactionDocumentTemplate)->pluck("documents")->collapse()->whereIn("id", $this->documents)->map(fn ($document) => (new TransactionDocumentForm($this, "transactionDocument"))->setTransactionDocument($transaction, $document)->store());
        (new TransactionHistoriesForm($this, "transactionHistories"))->store($transaction, TransactionHistoriesStatus::PROGRESS, "Admin memproses transaksi");
        // Reset
        $this->customer->resetCustom();
        $this->form->reset();
        $this->reset("transactionDocumentTemplate", "transactionDocuments");
        // End Reset

        $this->dispatch("transactionRefreshTable");
        $this->js("$('select#customerId').val('').trigger('change')    ;$('#TransactionCreateModal').modal('hide')");
        Toaster::success('Transaksi berhasil dibuat');
        $this->redirectRoute('transaction.detail', ["transaction" => $transaction->id]);
    }

    public function render()
    {
        $customer = new Customer();
        if (Auth::user()->role === "customer") {
            $customer = $customer->where("id", Auth::user()->customer->first()->id)->get();
        } else {
            $customer = $customer->all();
        }
        return view('livewire.transaction.create-form', ["transactionTypes" => $this->transactionType, "customers" => $customer]);
    }
}
