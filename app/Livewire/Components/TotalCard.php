<?php

namespace App\Livewire\Components;

use Illuminate\Support\Optional;
use Livewire\Component;

class TotalCard extends Component
{
    public string $icon;
    public string $title;
    public string $total;
    public string $percentage;
    public bool $isCurrency = true;
    public function mount($icon, $title, $total, $isCurrency = true)
    {
        if ($isCurrency) {
            $this->total = "Rp. " . number_format($total, 0, ",", ".");
        }
    }

    public function render()
    {
        return view('livewire.components.total-card');
    }
}
