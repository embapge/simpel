<?php

namespace App\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout')]
class Index extends Component
{
    public int $totalCustomer;
    public int $totalTransaction;
    public int $totalInvoice;
    public int $totalPayment;

    public function mount()
    {
        $this->totalCustomer = Customer::all()->count();
        $this->totalTransaction = Transaction::all()->sum("total");
        $this->totalInvoice = Invoice::all()->sum("total");
        $this->totalPayment = Payment::all()->sum("amount");
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
