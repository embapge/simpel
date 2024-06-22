<?php

namespace App\Livewire\Forms;

use App\Models\VerificationEmail;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VerificationEmailForm extends Form
{
    public $id;
    public $verification_id;
    public $name;
    public $address;

    public function setEmail(VerificationEmail $email)
    {
        $this->fill($email->only("id", "verification_id", "name", "address"));
        return $this;
    }
}
