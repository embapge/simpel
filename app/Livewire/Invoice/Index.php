<?php

namespace App\Livewire\Invoice;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.invoice.index');
    }
}
