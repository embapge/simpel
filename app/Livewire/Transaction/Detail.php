<?php

namespace App\Livewire\Transaction;

use App\Enums\TransactionHistoriesStatus;
use App\Http\Resources\TransactionPackage;
use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\DocumentForm;
use App\Livewire\Forms\InvoiceForm;
use App\Livewire\Forms\TransactionDocumentForm;
use App\Livewire\Forms\TransactionForm;
use App\Livewire\Forms\TransactionHistoriesForm;
use App\Livewire\Forms\TransactionServiceForm;
use App\Livewire\Forms\TransactionSubTypeForm;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\TransactionType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
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
    public Collection $documents;
    public InvoiceForm $invoice;
    public Collection $histories;
    public $documentsModel = [];
    public Collection $transactionServices;
    public Collection $transactionDocuments;
    public Collection $transactionHistories;
    public $number_display = "";
    public $parentCheckbox = false;
    public $services;
    public $checkedServices = [];
    public $checkedDocument = [];
    public $checkedHistories = [];
    public $editService = false;
    public $editDocument = false;
    public $editDetailDocument = false;
    public $editTransaction = false;
    public $editHistories = false;
    public $generateable;

    public function mount(Transaction $transaction)
    {
        $transaction->load(["documents", "services", "histories", "invoices"]);
        $this->form->setTransaction($transaction);
        $this->customer->setCustomer($transaction->customer);
        $this->transactionServices = $transaction->services->isNotEmpty() ? $transaction->services->map(fn ($service, $idx) => (new TransactionServiceForm($this, "transactionService"))->setService($service)) : collect([]);
        $this->transactionDocuments = $transaction->documents->isNotEmpty() ? $transaction->documents->map(function ($document, $idx) use ($transaction) {
            return (new TransactionDocumentForm($this, "transactionDocuments." . $idx))->setTransactionDocument($transaction, $document);
        }) : collect([]);
        $this->transactionHistories = $transaction->histories->isNotEmpty() ? $transaction->histories->map(function ($history, $idx) use ($transaction) {
            return (new TransactionHistoriesForm($this, "transactionHistories." . $idx))->setHistory($history);
        }) : collect([]);
        $this->subType->setSubType($this->transaction->subType);
        // $this->form->calculate();
        $this->checkGenerateable();
        $this->documents = collect([]);
        $this->histories = collect([]);
        $this->documentsModel = Document::whereNotIn("id", $this->transactionDocuments->pluck("document.id"))->get();
    }

    // Transaction
    public function saveTransaction()
    {
        $this->form->patch();
        $this->editTransactionMode();
        Toaster::success("Data berhasil diubah");
    }

    public function generateNumber()
    {
        if ($this->form->number_display == "DRAFT") {
            Toaster::warning("Mohon menunggu untuk nomor transaksi, dikarenakan masuk ke dalam antrian");
            $this->form->generateNumber();
        }
    }
    // End Transaction

    // TransactionServices
    public function addService()
    {
        $this->transactionServices->push((new TransactionServiceForm($this, "transactionService")));
    }

    public function removeService($idx)
    {
        $this->transactionServices->pull($idx);
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

    public function destroyService($id)
    {
        try {
            $this->transactionServices->where("id", $id)->first()->destroy();
            $this->transactionServices = $this->transactionServices->filter(fn ($service) => $service->id != $id)->values();
            $this->form->calculate();
            Toaster::success("Data layanan berhasil dihapus");
        } catch (\Throwable $th) {
            Toaster::error("Data layanan tidak ditemukan");
        }
    }
    // End TransactionServices

    // Document
    public function storeDocument()
    {
        foreach ($this->transactionDocuments as $transactionDocument) {
            $transactionDocument->upload();
        }
        $this->mount($this->transaction);
        $this->editDocument = false;
        $this->dispatch("document-updated");
        Toaster::success("File berhasil ditambahkan");
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
            $this->dispatch("document-updated");
            $this->js('$("input#transactionDocument' . $this->transaction->documents->where("id", $id)->keys()[0] . 'file").val("")');
            Toaster::success("Dokumen berhasil dikembalikan");
        } catch (\Throwable $th) {
            Toaster::error("Terjadi Kesalahan");
        }
    }

    public function emptyDocuments()
    {
        if (!count($this->checkedDocument)) {
            Toaster::error("Silahkan pilih dokumen untuk mengkosongkan");
            return;
        }

        foreach ($this->transactionDocuments->whereIn("document.id", $this->checkedDocument) as $document) {
            $document->empty();
        }

        $this->dispatch("document-updated");
        $this->editDocumentMode();
        Toaster::success("Dokumen berhasil dikosongkan");
    }

    public function destroyDocuments()
    {
        if (!count($this->checkedDocument)) {
            Toaster::error("Silahkan pilih dokumen untuk menghapus");
            return;
        }

        foreach ($this->transactionDocuments->whereIn("document.id", $this->checkedDocument) as $document) {
            $document->destroy();
        }
        $this->transactionDocuments = $this->transactionDocuments->whereNotIn("document.id", $this->checkedDocument);
        $this->dispatch("document-updated");
        $this->editDocumentMode();
        Toaster::success("Dokumen berhasil dihapus");
    }
    // Detail Document
    public function addDetailDocument()
    {
        $this->documents->push((new TransactionDocumentForm($this, "document." . $this->documents->keys()->last() ?? 0))->setTransactionDocument($this->transaction));
        $this->dispatch("document-updated");
    }

    public function saveDetailDocument()
    {
        foreach ($this->documents as $document) {
            $document->validate();
            if ($document->store()) {
                $this->transaction->refresh();
                $this->transactionDocuments->push($document);
            }
        }

        $this->documents = collect([]);
        $this->dispatch("document-updated");
        $this->editDetailDocumentMode();
        Toaster::success("Dokumen berhasil dibuat");
    }

    public function removeDetailDocument($idx)
    {
        $this->documents->pull($idx);
        $this->documents->values();
        $this->dispatch("document-updated");
    }
    // End Detail Document

    // End Document

    // Histories
    public function addHistory()
    {
        $this->histories->push(["status" => "", "description" => "", "type" => ""]);
    }

    public function removeHistory($idx)
    {
        $this->histories->pull($idx);
        $this->histories->values();
        Toaster::success("Data berhasil dihapus");
    }

    public function storeHistory()
    {
        $this->validate(["histories.*.status" => "required", "histories.*.type" => "required"],["histories.*.status.required" => "Status harus diisi", "histories.*.type.required" => "Tipe harus diisi"]);

        try {
            foreach ($this->histories as $iHistory => $history) {
                $this->transactionHistories->push((new TransactionHistoriesForm($this, "transactionHistories." . $this->transactionHistories->keys()->last() ?? 0))->store($this->transaction, TransactionHistoriesStatus::from($history["status"]), $history["description"], $history["type"]));
            }
            $this->histories = collect([]);
            $this->editHistories = false;
            Toaster::success("History transaksi berhasil ditambahkan");
        } catch (\Throwable $th) {
            //throw $th;
            Toaster::error($th->getMessage());
        }

    }
    
    public function sendHistories()
    {
        $this->form->sendHistories();
        Toaster::success("Aktifitas transaksi berhasil dikirimkan ke pelanggan.");
    }
    // End Histories

    // Edit Mode
    public function editServiceMode()
    {
        $this->transactionServices = $this->transactionServices->whereNotNull("id");
        $this->editService = !$this->editService;
    }

    public function editDocumentMode()
    {
        $this->reset("checkedDocument");
        $this->editDocument = !$this->editDocument;
    }

    public function editDetailDocumentMode()
    {
        $this->documents = collect([]);
        $this->editDetailDocument = !$this->editDetailDocument;
    }

    public function editTransactionMode()
    {
        $this->editTransaction = !$this->editTransaction;
    }

    public function editHistoryMode()
    {
        $this->histories = collect([]);
        $this->editHistories = !$this->editHistories;
    }
    // End Edit Mode

    // Invoice
    public function generateInvoice()
    {
        $this->invoice->store($this->transaction);
        $this->transaction->refresh();
        $this->dispatch("invoice-refresh-table");
        Toaster::success("Invoice berhasil di generate");
    }
    // End Invoice

    public function render()
    {
        return view('livewire.transaction.detail');
    }

    // Event
    #[On('echo:generate.number.transaction.{form.id},TransactionFindNumberEvent')]
    public function notifyNumberGenerated()
    {
        $this->transaction->refresh();
        $this->transaction->load(["documents", "services", "histories", "invoices"]);
        $this->form->setTransaction($this->transaction);
        Toaster::success("Nomor transaksi telah di generate");
    }

    #[On('document-updated')]
    public function checkGenerateable()
    {
        $this->generateable = $this->transaction->documents->whereNotNull("pivot.date")->whereNotNull("pivot.file")->count() == $this->transaction->documents->count() && $this->transaction->number_display !== "DRAFT";
    }

    #[On('document-updated')]
    public function refreshDocument()
    {
        $this->documentsModel = Document::whereNotIn("id", $this->transactionDocuments->pluck("document.id"))->get();
    }
    // End Event

    // Function
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
    // End Function
}
