<?php

namespace App\Livewire\Verification;

use App\Livewire\Forms\VerificationDocumentForm;
use App\Livewire\Forms\VerificationEmailForm;
use App\Livewire\Forms\VerificationForm;
use App\Livewire\Forms\VerificationPhoneForm;
use App\Models\Verification;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class ModalDocument extends Component
{
    public $modalId;
    public VerificationForm $form;
    public Collection $documents;
    public Collection $emails;
    public Collection $phones;
    public $checkboxDocumentVerified = [];

    public function mount($id)
    {
        $this->fill([
            "modalId" => $id,
            "documents" => collect([])
        ]);
    }

    #[On("verification-show")]
    public function show(Verification $verification)
    {
        $verification->refresh();
        $verification->load(["emails", "phones", "documents"]);

        $this->form->setVerification($verification);
        $this->emails = $verification->emails->isNotEmpty() ? $verification->emails->map(fn ($email, $index) => (new VerificationEmailForm($this, "emails." . $index))->setEmail($email)) : collect([]);
        $this->phones = $verification->phones->isNotEmpty() ? $verification->phones->map(fn ($phone, $index) => (new VerificationPhoneForm($this, "phones." . $index))->setPhone($phone)) : collect([]);
        $this->documents = $verification->documents->isNotEmpty() ? $verification->documents->map(fn ($document, $index) => (new VerificationDocumentForm($this, "documents." . $index))->setDocument($document)) : collect([]);

        $this->js("$('#verificationDocumentModal').modal('show')");
    }

    public function verified()
    {
        foreach ($this->documents->whereIn("id", $this->checkboxDocumentVerified)->where("is_verified", 0) as $document) {
            $document->verified();
        }

        $this->reset("checkboxDocumentVerified");
        $this->dispatch("verificationRefreshTable");

        Toaster::success("Dokumen berhasil di verifikasi silahkan melanjutkan proses verifikasi data");
    }

    public function unverified()
    {
        foreach ($this->documents->whereIn("id", $this->checkboxDocumentVerified) as $document) {
            $document->unverified();
        }

        $this->reset("checkboxDocumentVerified");
        $this->dispatch("verificationRefreshTable");

        Toaster::success("Dokumen berhasil di verifikasi silahkan melanjutkan proses verifikasi data");
    }

    public function render()
    {
        return view('livewire.verification.modal-document');
    }
}
