<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" tabindex="-1" style="display: none;"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Title">Pengiriman Link Upload Ulang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="" class="form-label">Keterangan</label>
                <textarea name="" id="" cols="30" rows="3" class="form-control" wire:model="message"></textarea>
                <small>Silahkan memasukkan alasan terkait pengiriman ulang pengajuan dokumen</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" wire:click="resendLink">Resend</button>
            </div>
        </div>
    </div>
</div>
