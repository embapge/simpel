<?php

namespace App\Livewire\Components;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceGraphCard extends Component
{
    public string $chartId;
    public $series;
    public string $categories;

    public function mount($chartId)
    {
        $this->chartId = $chartId;
        $invoices = DB::table("invoices")->select(DB::raw('DATE_FORMAT(issue_date, "%Y-%m") AS "monthYear", COUNT("id") AS "total"'))->groupBy(DB::raw('DATE_FORMAT(issue_date, "%Y-%m")'))->orderBy(DB::raw('DATE_FORMAT(issue_date, "%Y-%m")'))->get();
        $this->categories = $invoices->map(fn ($transaction) => Carbon::parse($transaction->monthYear)->translatedFormat("F Y"))->prepend("");
        $this->series = $invoices->map(fn ($transaction) => $transaction->total)->prepend(0);
    }

    public function render()
    {
        return view('livewire.components.invoice-graph-card');
    }
}
