<?php

namespace App\Livewire\Components;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Lazy;

class TransactionGraphChart extends Component
{
    public string $chartId;
    public $series;
    public string $categories;
    public function mount($chartId)
    {
        $transactions = DB::table("transactions")->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") AS "monthYear", COUNT("id") AS "total"'))->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))->get();
        $this->categories = $transactions->map(fn ($transaction) => Carbon::parse($transaction->monthYear)->translatedFormat("F Y"))->prepend("");
        $this->series = $transactions->map(fn ($transaction) => $transaction->total)->prepend(0);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="card text-center h-100">
            <div class="card-body">
                <h5 class="card-title">Chart Transaksi</h5>
                <p class="card-text">Menampilkan jumlah transaksi tiap bulannya</p>
                <a href="javascript:void(0)" class="btn btn-primary">Loading... </a>
            </div>
            <div class="card-footer text-muted">Mohon menunggu hingga menampilkan grafik</div>
        </div>
        HTML;
    }


    public function render()
    {
        return view('livewire.components.transaction-graph-chart');
    }
}
