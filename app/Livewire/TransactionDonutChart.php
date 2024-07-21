<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Support\Str;

class TransactionDonutChart extends Component
{
    public string $chartId;
    public Collection $transactions;
    public $label;
    public $series;
    public function mount($chartId, $transactions)
    {
        $this->label = $transactions->map(fn ($transaction) => Str::of("$transaction->name")->explode(' ')->map(fn ($title) => \Illuminate\Support\Str::upper($title[0]))->join(''));
        $this->series = $transactions->map(fn ($transaction) => $transaction->amount);
    }

    public function render()
    {
        return view('livewire.transaction-donut-chart');
    }
}
