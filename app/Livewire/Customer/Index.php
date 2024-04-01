<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        session()->flash('flash.banner', 'Yay it works!');
        return view('livewire.customer.index');
    }
}
