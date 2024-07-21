<?php

namespace App\Livewire\Components;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransactionBarChart extends Component
{
    public string $chartId;
    public Collection $label;
    public $data;

    public function mount($chartId)
    {
        $this->chartId = $chartId;
        $this->data = collect([]);
        $transactions = DB::table("transactions")->select("status", DB::raw("sum(total) as total"), DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"))->groupBy("status", DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))->get();
        foreach ($transactions->groupBy("status") as $iStatus => $status) {
            $this->data->push(["name" => Str::title($iStatus), "data" => $status->sortBy("period")->values()->pluck("total")->map(fn ($total) => round($total))]);
        }
        $this->label = $transactions->pluck("period")->unique()->sort()->values()->map(fn ($period) => Carbon::parse($period)->translatedFormat("F Y"));
        // $this->data = $this->data->toArray();
        // dd($this->data->toArray());
    }

    public function render()
    {
        return view('livewire.components.transaction-bar-chart');
    }
}
