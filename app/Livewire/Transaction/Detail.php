<?php

namespace App\Livewire\Transaction;

use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\DocumentForm;
use App\Livewire\Forms\TransactionDocumentForm;
use App\Livewire\Forms\TransactionForm;
use App\Livewire\Forms\TransactionServiceForm;
use App\Livewire\Forms\TransactionSubTypeForm;
use App\Models\Transaction;
use App\Models\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Livewire\Transaction\Detail as DetailInitialize;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Masmerise\Toaster\Toaster;
use Livewire\WithFileUploads;

#[Layout('components.layout')]
class Detail extends Component
{
    use WithFileUploads;

    public Transaction $transaction;
    public TransactionForm $form;
    public TransactionSubTypeForm $subType;
    public CustomerForm $customer;
    public $documents = [];
    public $transactionServices = [];
    public $transactionDocuments = [];
    public $number_display = "";
    public $parentCheckbox = false;
    public $services;
    public $checkedServices = [];
    public $checkedDocument = [];
    public $editService = false;
    public $editDocument = false;
    public $editTransaction = false;
    public $generateable;

    public function mount(Transaction $transaction)
    {
        $transaction->load(["documents", "services", "histories"]);
        $this->form->setTransaction($transaction);
        $this->customer->setCustomer($transaction->customer);
        $this->transactionServices = $transaction->services->isNotEmpty() ? $transaction->services->map(fn ($service, $idx) => (new TransactionServiceForm($this, "transactionService"))->setService($service)) : collect([]);
        // $this->transactionDocuments = $transaction->documents->isNotEmpty() ? $transaction->documents->map(fn ($document, $idx) => [(new TransactionDocumentForm($this, "transactionDocuments"))->setTransactionDocument(document: $document)]) : collect([]);
        $this->transactionDocuments = $transaction->documents->isNotEmpty() ? $transaction->documents->map(function ($document, $idx) use ($transaction) {
            return (new TransactionDocumentForm($this, "transactionDocuments." . $idx))->setTransactionDocument($transaction, $document);
        }) : collect([]);

        $this->subType->setSubType($this->transaction->subType);
        $this->form->calculate();
        $this->generateable = $transaction->documents->whereNotNull("pivot.date")->whereNotNull("pivot.file")->isNotEmpty();
    }

    public function addService()
    {
        $this->transactionServices->push((new TransactionServiceForm($this, "transactionService")));
    }

    public function removeService($idx)
    {
        $this->transactionServices->pull($idx);
    }

    public function editServiceMode()
    {
        // dd($this->transactionServices);
        $this->transactionServices = $this->transactionServices->whereNotNull("id");
        $this->editService = !$this->editService;
    }

    public function editDocumentMode()
    {
        $this->editDocument = !$this->editDocument;
    }

    public function editTransactionMode()
    {
        $this->editTransaction = !$this->editTransaction;
    }

    public function storeDocument()
    {
        foreach ($this->transactionDocuments as $transactionDocument) {
            $transactionDocument->store();
        }
        $this->mount($this->transaction);
        $this->editDocument = false;

        Toaster::success("File berhasil ditambahkan");
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

    public function saveServices()
    {
        foreach ($this->transactionServices as $service) {
            if (!$service->id) {
                $service->store($this->transaction);
            } else {
                $service->patch();
            }
        }

        $this->form->calculate();
        $this->reset("editService");
        $this->mount($this->transaction);
        Toaster::success("Data berhasil diubah");
    }

    public function saveTransaction()
    {
        $this->form->patch();
        $this->editTransactionMode();
        Toaster::success("Data berhasil diubah");
    }

    public function updateDocuments()
    {
    }

    public function revertUploadDocument($id)
    {
        try {
            $transactionDocument = $this->transaction->documents->where("id", $id);
            if ($transactionDocument->first()->pivot->file) {
                $this->transactionDocuments->where("document.id", $id)->first()->fill(["file" => $transactionDocument->first()->pivot->file]);
            } else {
                $this->transactionDocuments->where("document.id", $id)->first()->reset("file");
            }
            $this->js('$("input#transactionDocument' . $this->transaction->documents->where("id", $id)->keys()[0] . 'file").val("")');
            Toaster::success("Dokumen berhasil dikembalikan");
        } catch (\Throwable $th) {
            Toaster::error("Terjadi Kesalahan");
        }
    }

    public function generateNumber()
    {
        $this->form->generateNumber();
        Toaster::success("Nomor Transaksi berhasil di generate");
    }

    public function generateInvoice()
    {
        $this->form->generateInvoice();
        Toaster::success("Invoice berhasil di generate");
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

    public function render()
    {
        return view('livewire.transaction.detail');
    }
}
