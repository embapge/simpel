<?php

namespace App\Livewire\User;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.user.index');
    }
}
