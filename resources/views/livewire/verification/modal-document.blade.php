<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" tabindex="-1" style="display: none;"
    aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Title">Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="wrapper">
                    <div class="demo-inline-spacing mt-3">
                        <div class="list-group">
                            @foreach ($documents as $iDoc => $document)
                                <div
                                    class="list-group-item list-group-item-action flex-column align-items-start @if ($document->is_verified) active @endif">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex">
                                            <input class="form-check-input me-1" type="checkbox"
                                                wire:model="checkboxDocumentVerified" value="{{ $document->id }}">
                                            <h6 class="mb-0 mt-1">{{ $document->name }}</h6>
                                        </div>
                                        <small>{{ \Carbon\Carbon::parse($document->date)->translatedFormat('d F Y h:i:s A') }}</small>
                                    </div>
                                    <form action="{{ route('document.preview') }}" method="POST" target="__blank"
                                        class="my-2">
                                        @csrf
                                        <input type="hidden" name="path" value="{{ $document->file }}">
                                        <button type="submit"
                                            class="@if (!$document->is_verified) text-primary @endif">{{ Str::limit(Str::of($document->file)->explode('/')->last(),40) }}</button>
                                    </form>
                                    <small>{{ $document->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi' }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                @if ($form->status !== 'success')
                    <button type="button" class="btn btn-success" wire:click="verified">Verified</button>
                    <button type="button" class="btn btn-danger" wire:click="unverified">Unverified</button>
                @endif
            </div>
        </div>
    </div>
</div>
