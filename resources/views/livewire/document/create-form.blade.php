<div>
    <div class="modal fade" id="documentCreateModal" tabindex="-1" style="display: none;" aria-hidden="true" data-bs-focus="false" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel4">Create</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <label for="name" class="form-label">Nama Dokumen<span class="text-danger">*</span></label>
                        <input type="text" id="name" wire:model='form.name' class="form-control @error('form.name') is-invalid @enderror" placeholder="NPWP">
                        @error('form.name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-xl-6 mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="" id="" rows="2" class="form-control @error('form.description') is-invalid @enderror" wire:model='form.description' placeholder="Ini merupakan dokumen untuk mengurus surat x"></textarea>
                        @error('form.description')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        
                    </div>
                </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Close
              </button>
              <button wire:click="save" type="button" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
      
      