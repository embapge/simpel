<?php

namespace App\Livewire\Forms;

use App\Models\VerificationPhone;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VerificationPhoneForm extends Form
{
    public $id;
    public $verification_id;
    public $name;
    public $number;

    public function setPhone(VerificationPhone $phone)
    {
        $this->fill($phone->only("id", "verification_id", "name", "number"));
        return $this;
    }
}
