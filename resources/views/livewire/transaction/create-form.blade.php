<div>
    <div class="modal fade" id="transactionCreateModal" tabindex="-1" style="display: none;" aria-hidden="true"
        data-bs-focus="false" wire:ignore.self>
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-4 mb-3">
                            <label class="form-label" for="customer">Pelanggan <span
                                    class="text-danger">*</span></label>
                            <div class="">
                                <x-select2 class="form-select mb-1" :datas='$customers' wire:model='form.customer_id'
                                    name="form.customer_id" id="customerId" />
                                <x-alert-message name="form.customer_id" />
                            </div>
                        </div>
                        @if ($customer->name)
                            <div class="col-xl-4 mb-3">
                                <label class="form-label" for="pic_name">Nama PIC</label>
                                <input type="text" class="form-control" id="pic_name" name="pic_name"
                                    wire:model='customer.pic_name' disabled>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    wire:model='customer.email' disabled>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label class="form-label" for="phone_number">Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    wire:model='customer.phone_number' disabled>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label class="form-label" for="address">Alamat</label>
                                <textarea class="form-control" id="address" name="address" wire:model='customer.address' disabled rows="2"></textarea>
                            </div>
                        @endif
                    </div>
                    <div class="divider">
                        <div class="divider-text">
                            <i class="bx bx-folder-open"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="nav-align-top mb-4" wire:ignore>
                                <ul class="nav nav-pills mb-3" role="tablist">
                                    @foreach ($transactionTypes as $iType => $type)
                                        <li class="nav-item" wire:key="{{ $type->id }}">
                                            <button type="button"
                                                class="nav-link @if ($iType == 0) active @endif"
                                                role="tab" data-bs-toggle="tab"
                                                data-bs-target="#navs-pills-top-{{ $type->id }}"
                                                aria-controls="navs-pills-top-{{ $type->id }}" aria-selected="true">
                                                {{ Str::title($type->name) }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    @foreach ($transactionTypes as $iTypee => $type)
                                        <div class="tab-pane fade @if ($iTypee == 0) active show @endif"
                                            id="navs-pills-top-{{ $type->id }}" role="tabpanel"
                                            wire:key="{{ $type->id }}">
                                            <div class="demo-inline-spacing">
                                                <div class="list-group">
                                                    @foreach ($type->subTypes as $subType)
                                                        <label class="list-group-item border-0">
                                                            <input class="form-check-input me-1" type="radio"
                                                                wire:click='changeDocument'
                                                                wire:model='form.transaction_sub_type_id'
                                                                value="{{ $subType->id }}">
                                                            {{ Str::title($subType->name) }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <x-alert-message name="form.transaction_sub_type_id" />
                        </div>
                        <div class="col-xl-6">
                            @if ($transactionDocumentTemplate)
                                <div class="nav-align-top mb-4">
                                    <ul class="nav nav-pills mb-3" role="tablist">
                                        @foreach ($transactionDocumentTemplate as $iDocTemp => $documentTemplate)
                                            <li class="nav-item" wire:key="{{ $documentTemplate->id }}">
                                                <button type="button"
                                                    class="nav-link @if ($iDocTemp == 0) active @endif"
                                                    role="tab" data-bs-toggle="tab"
                                                    data-bs-target="#navs-pills-top-{{ $documentTemplate->id }}"
                                                    aria-controls="navs-pills-top-{{ $documentTemplate->id }}"
                                                    aria-selected="true">
                                                    {{ Str::title($documentTemplate->name) }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content mhx-252 overflow-y-auto">
                                        @foreach ($transactionDocumentTemplate as $iDocTempl => $documentTemplate)
                                            <div class="tab-pane fade @if ($iDocTempl == 0) active show @endif"
                                                id="navs-pills-top-{{ $documentTemplate->id }}" role="tabpanel"
                                                wire:key="{{ $documentTemplate->id }}">
                                                <div class="demo-inline-spacing">
                                                    <div class="list-group">
                                                        <div class="row">
                                                            @foreach ($documentTemplate->documents as $document)
                                                                <div class="col-xl-6 col-lg-4 col-md-6">
                                                                    <label
                                                                        class="list-group-item border-0 text-wrap text-break">
                                                                        <input class="form-check-input me-1"
                                                                            type="checkbox"
                                                                            value="{{ $document->id }}"
                                                                            wire:model='documents'>
                                                                        {{ Str::title($document->name) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <x-alert-message name="documents" />
                                    </div>
                                </div>
                            @endif
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
</div>

@script
    <script type="text/javascript">
        $(document).ready(function() {
            $("select[name='form.customer_id']").on("select2:select select2:clear", function(e) {
                e.preventDefault();
                $wire.dispatch('transaction-customer-change');
            });
        });
    </script>
@endscript
