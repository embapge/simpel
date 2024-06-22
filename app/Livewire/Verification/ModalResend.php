<?php

namespace App\Livewire\Verification;

use App\Livewire\Forms\VerificationForm;
use App\Models\Verification;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class ModalResend extends Component
{
    public VerificationForm $form;
    public $modalId;
    public $message;

    public function mount($id)
    {
        $this->fill([
            "modalId" => $id
        ]);
    }

    #[On("show-resend-link")]
    public function show(Verification $verification)
    {
        $this->form->setVerification($verification);
        $this->js("$('#verificationResendLinkModal').modal('show')");
    }

    public function resendLink()
    {
        try {
            $this->form->resendLink($this->message);

            $this->dispatch("verificationRefreshTable");
            Toaster::success("Link berhasil dikirimkan ulang ke pelanggan");
        } catch (\Throwable $th) {
            Toaster::error($th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.verification.modal-resend');
    }
}
