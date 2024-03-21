<?php

namespace App\Livewire\Button;

use Livewire\Component;

class Submit extends Component
{
    public $text;

    public function render()
    {
        return view('livewire.button.submit');
    }
}
