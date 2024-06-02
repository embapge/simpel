<?php

namespace App\Livewire;

use Livewire\Component;

class Select2 extends Component
{
    public $datas;

    public function mount($datas)
    {
        $this->datas = $datas;
    }

    public function render()
    {
        return view('livewire.select2');
    }
}
