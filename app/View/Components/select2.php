<?php

namespace App\View\Components;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select2 extends Component
{
    public $datas;
    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    public function render()
    {
        return view('components.select2');
    }
}
