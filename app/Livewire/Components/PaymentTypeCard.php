<?php

namespace App\Livewire\Components;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class PaymentTypeCard extends Component
{
    public Collection $vendors;
    public function mount()
    {
        $this->vendors = collect([]);
        $payments = Payment::where("status", PaymentStatus::PAID)->withWhereHas("transaction")->get();
        $vendors = $payments->map(fn ($payment) => collect(["name" => paymentVendor(json_decode($payment->transaction->response, true)), "amount" => json_decode($payment->transaction->response, true)["gross_amount"]]));
        // dd($vendors->groupBy("name"));
        foreach ($vendors->groupBy("name") as $keyVendor => $vendor) {
            $this->vendors->push(["name" => Str::upper($keyVendor), "total" => $vendor->sum("amount")]);
        }
    }

    public function render()
    {
        return view('livewire.components.payment-type-card');
    }
}
