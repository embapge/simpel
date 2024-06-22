<div>
    <x-slot:title>
        Verification
    </x-slot>
    <div class="card p-3">
        <h3 class="card-header">Verification List</h3>
        <livewire:verification-table />
    </div>
    <div class="">
        <livewire:verification.modal-document id="verificationDocumentModal" />
    </div>
    <div class="">
        <livewire:verification.modal-resend id="verificationResendLinkModal" />
    </div>
</div>
