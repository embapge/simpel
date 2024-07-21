<?php

namespace App\Livewire\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TotalTypeCard extends Component
{
    public Collection $transactions;
    public int $total = 0;
    public function mount()
    {
        $this->transactions = DB::table("transactions")
            ->leftJoin("transaction_sub_types", "transactions.transaction_sub_type_id", "=", "transaction_sub_types.id")
            ->leftJoin("transaction_types", "transaction_sub_types.transaction_type_id", "=", "transaction_types.id")
            ->groupBy("transactions.transaction_sub_type_id")
            ->select(["transaction_sub_types.name", DB::raw("COUNT(transaction_sub_type_id) as amount")])->get();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="">
                        <h5 class="card-title">Chart Donut Jenis Transaksi</h5>
                        <p class="card-text">Menampilkan keseluruhan transaksi berdasarkan tipe</p>
                        <a href="javascript:void(0)" class="btn btn-primary">Loading... </a>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">Mohon menunggu hingga menampilkan grafik</div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.components.total-type-card');
    }
}
