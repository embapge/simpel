<?php

namespace App\Livewire\Verification;

use App\Livewire\Forms\VerificationDocumentForm;
use App\Livewire\Forms\VerificationEmailForm;
use App\Livewire\Forms\VerificationForm;
use App\Livewire\Forms\VerificationPhoneForm;
use App\Models\Verification;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layout')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.verification.index');
    }
}
