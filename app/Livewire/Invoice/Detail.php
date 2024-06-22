<?php

namespace App\Livewire\Invoice;

use App\Enums\InvoiceType;
use App\Events\MidtransTransactionStatusEvent;
use App\Livewire\Forms\InvoiceForm;
use App\Livewire\Forms\InvoiceServiceForm;
use App\Livewire\Forms\PaymentForm;
use App\Livewire\Forms\PaymentTransactionForm;
use App\Models\Invoice;
use App\Models\InvoiceService;
use App\Models\Payment;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layout')]
class Detail extends Component
{
    public Invoice $invoice;
    public InvoiceForm $form;
    public Transaction $transaction;
    public PaymentForm $payment;
    public PaymentTransactionForm $paymentTransaction;
    public $editInvoice = false;
    public $editService = false;
    public $generateable = false;
    public $parentCheckbox = false;
    public $tax = true;
    public $stamp = false;
    public $checkedServices = [];
    public Collection $invoiceServices;

    public function mount(Invoice $invoice)
    {
        $invoice->load(["services", "transaction" => ["customer" => ["emails", "phones"]], "payment.transaction"]);
        $this->invoice = $invoice;
        $this->form->setInvoice($invoice);
        $this->invoiceServices = $invoice->services->isNotEmpty() ? $invoice->services->map(fn (InvoiceService $service, $idx) => (new InvoiceServiceForm($this, "invoiceService.{$idx}"))->setService($service)) : collect([]);
        $this->tax = $invoice->is_tax ? true : false;
        if ($invoice->payment) {
            $this->payment->setPayment($invoice->payment);
            $this->paymentTransaction->setPaymentTransaction($invoice->payment->transaction);
        }
    }

    #[On('echo:payment.check.{invoice.id},MidtransTransactionStatusEvent')]
    public function refreshPayment()
    {
        $this->paymentTransaction->reset();
        $this->payment->reset();

        $this->invoice->fresh();
        if ($this->invoice->payment) {
            $this->invoice->payment->fresh();
            $this->payment->setPayment($this->invoice->payment);
            $this->paymentTransaction->setPaymentTransaction($this->invoice->payment->transaction);
        }
    }

    // Transaction
    public function saveInvoice()
    {
        $this->form->patch();
        $this->editInvoiceMode();
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

    // InvoiceServices
    public function addService()
    {
        $this->invoiceServices->push((new InvoiceServiceForm($this, "invoiceService")));
    }

    public function removeService($idx)
    {
        $this->invoiceServices->pull($idx);
    }

    public function saveServices()
    {
        foreach ($this->invoiceServices as $service) {
            if (!$service->id) {
                $service->store($this->form->invoice);
            } else {
                $service->patch();
            }
        }

        $this->form->calculate();
        $this->reset("editService");
        $this->mount($this->invoice);
        Toaster::success("Data berhasil diubah");
    }

    public function destroyService($id)
    {
        try {
            $this->invoiceServices->where("id", $id)->first()->destroy();
            $this->invoiceServices = $this->invoiceServices->filter(fn ($service) => $service->id != $id)->values();
            $this->form->calculate();
            Toaster::success("Data layanan berhasil dihapus");
        } catch (\Throwable $th) {
            Toaster::error("Data layanan tidak ditemukan");
        }
    }

    public function updateTax()
    {
        $this->form->updateTax(isTax: $this->tax);
    }
    // End InvoiceServices

    // Edit Mode
    public function editServiceMode()
    {
        $this->invoiceServices = $this->invoiceServices->whereNotNull("id");
        $this->editService = !$this->editService;
    }

    public function editInvoiceMode()
    {
        $this->editInvoice = !$this->editInvoice;
    }
    // End Edit Mode

    // Invoice
    public function generateInvoice()
    {
        // $this->form->generateInvoice();
        $this->invoice->store($this->invoice);
        $this->invoice->refresh();
        $this->invoice->load(["documents", "services", "histories", "invoices"]);
        $this->dispatch("invoice-refresh-table");
        Toaster::success("Invoice berhasil di generate");
    }

    public function print()
    {
        if (!$this->invoice->payment) {
            Toaster::error("Pembayaran tidak tersedia");
            return;
        }

        $pdf = Pdf::loadView('invoice.pdf', ["invoice" => $this->invoice, "response" => json_decode($this->invoice->payment->transaction->response, true)])->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Invoice-" . Carbon::parse($this->invoice->issue_date)->format("d-F-Y") . "-" . Str::replace(" ", "-", $this->invoice->customer_name) . "-" . Carbon::now()->format("Y-m-d-H:i:s") . ".pdf"
        );
    }
    // End Invoice

    // Payment
    public function payNow()
    {
        try {
            $this->payment->store($this->form);
            $this->paymentTransaction->store($this->payment);
        } catch (\Throwable $th) {
            Toaster::error($th->getMessage());
            return;
        }

        Toaster::success("Pembayaran Telah di generate");
    }
    // End Payment

    public function render()
    {
        return view('livewire.invoice.detail');
    }

    // Event
    #[On('echo:generate.number.invoice.{form.id},InvoiceFindNumberEvent')]
    public function notifyNumberGenerated()
    {
        $this->invoice->refresh();
        $this->invoice->load(["services"]);
        $this->form->setInvoice($this->invoice);
        Toaster::success("Nomor invoice telah di generate");
    }

    #[On('document-updated')]
    public function checkGenerateable()
    {
        $this->generateable = $this->invoice->documents->whereNotNull("pivot.date")->whereNotNull("pivot.file")->count() == $this->invoice->documents->count();
    }

    // End Event

    // Function
    public function checkboxParent()
    {
        if ($this->parentCheckbox) {
            $this->checkedServices = Arr::pluck($this->invoiceServices, "id");
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

        if (count($this->checkedServices) > 0 && count($this->checkedServices) < count($this->invoiceServices)) {
            $this->js("$('#parentCheck').prop('indeterminate', true)");
        } else if (count($this->checkedServices) == count($this->invoiceServices)) {
            $this->js("$('#parentCheck').prop('checked', true)");
        } else {
            $this->js("$('#parentCheck').prop('checked', false)");
        }
    }
    // End Function
}
