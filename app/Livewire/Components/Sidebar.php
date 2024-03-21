<?php

namespace App\Livewire\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
