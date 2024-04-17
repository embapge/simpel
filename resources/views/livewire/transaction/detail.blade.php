<div>
    <x-slot:title>
        Transaksi Detail
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h3>Detail</h3>
            <span class="text-md"><a href="{{ route('transaction') }}">Transaction</a> - Transaction Manager</span>
            <div class="row">
                <div class="col-xl-8">
                    <div class="card mt-3 min-vh-70">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-xl-6 justify-content-end">
                                    <div class="d-flex">
                                        <h4 class="text-bold mb-0">Transaksi #</h4><span
                                            class="form-control-sm h-1 w-30 py-0 px-1 m-0 text-uppercase ms-1 top-0">{{ $form->number_display }}</span>
                                        @if ($editTransaction)
                                            @if ($form->number_display == 'DRAFT')
                                                <button type="button"
                                                    class="badge badge-center rounded-pill bg-label-primary"><i
                                                        class="bx bx-refresh" wire:click='generateNumber'></i></button>
                                            @endif
                                            <button type="button"
                                                class="badge badge-center rounded-pill bg-label-primary"><i
                                                    class="bx bxs-hand-left"
                                                    wire:click='updateNumberDisplay'></i></button>
                                        @endif
                                        {{-- <input type="text"
                                            class="form-control-sm h-1 w-30 py-0 px-1 m-0 text-uppercase ms-1 top-0 @if ($editTransaction) border-1 @else border-0 @endif"
                                            wire:model='form.number_display' readonly > --}}
                                    </div>
                                </div>
                                <div class="col-xl-6 text-end">
                                    @if ($editTransaction)
                                        <button type="button" class="btn btn-icon btn-outline-success"
                                            wire:click='saveTransaction'>
                                            <span class="tf-icons bx bx-save"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-danger">
                                            <span class="tf-icons bx bx-x" wire:click='editTransactionMode'></span>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-icon btn-outline-warning"
                                            wire:click='editTransactionMode'>
                                            <span class="tf-icons bx bx-pencil"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary">
                                            <span class="tf-icons bx bx-printer"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <table class="mx-3 text-sm text-justify">
                                        <tr class="align-top">
                                            <td class="w-25">Jenis Jasa</td>
                                            <td>{{ $subType->name }}</td>
                                        </tr>
                                        <tr class="align-top">
                                            <td class="w-25">Company</td>
                                            <td>{{ $customer->name }}</td>
                                        </tr>
                                        <tr class="align-top">
                                            <td>PIC Name</td>
                                            <td>{{ $customer->pic_name }}</td>
                                        </tr>
                                        <tr class="align-top">
                                            <td>Address</td>
                                            <td>{{ $customer->address }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                {{-- <div class="col-xl-6">
                                    <table class="mx-3 text-sm text-justify">
                                        <tr class="align-top">
                                            <td class="w-25">Tanggal Invoice</td>
                                            <td><x-datepicker class="form-control-sm" /></td>
                                        </tr>
                                        <tr class="align-top">
                                            <td>Jatuh Tempo</td>
                                            <td>{{ $customer->pic_name }}</td>
                                        </tr>
                                        <tr class="align-top">
                                            <td>Periode Invoice</td>
                                            <td>{{ $customer->address }}
                                            </td>
                                        </tr>
                                    </table>
                                </div> --}}
                            </div>
                            <div class="divider divider-dashed">
                                <div class="divider-text">Services</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-p-2">
                                    <thead>
                                        <tr>
                                            @if ($editService)
                                                <th><input type="checkbox" class="form-check-input" id="parentCheck"
                                                        wire:click='checkboxParent' wire:model='parentCheckbox'></th>
                                            @endif
                                            <th>Item</th>
                                            <th colspan="2">Description</th>
                                            <th>Price</th>
                                            <th>
                                                @if (!$editService)
                                                    <button type="button"
                                                        class="badge badge-center rounded-pill bg-label-warning"><i
                                                            class="bx bx-pencil"
                                                            wire:click='editServiceMode'></i></button>
                                                @else
                                                    <button type="button"
                                                        class="badge badge-center rounded-pill bg-label-primary"><i
                                                            class="bx bx-save" wire:click='saveServices'></i></button>
                                                    <button type="button"
                                                        class="badge badge-center rounded-pill bg-label-success"><i
                                                            class="bx bx-plus" wire:click='addService'></i></button>
                                                    <button type="button"
                                                        class="badge badge-center rounded-pill bg-label-danger"><i
                                                            class="bx bx-x" wire:click='editServiceMode'></i></button>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactionServices as $iService => $service)
                                            <tr wire:key="$iService">
                                                @if ($editService)
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input"
                                                            wire:model='checkedServices' value="{{ $service->id }}"
                                                            wire:click='clickService'
                                                            @if (!$editService) readonly @endif>
                                                    </td>
                                                @endif
                                                <td><input type="text"
                                                        class="form-control w-100 @if ($editService) border-1 @else border-0 @endif"
                                                        wire:model='transactionServices.{{ $iService }}.name'
                                                        @if (!$editService) readonly @endif>
                                                </td>
                                                <td colspan="2">
                                                    <textarea rows="1" name="" id=""
                                                        class="form-control @if ($editService) border-1 @else border-0 @endif"
                                                        wire:model='transactionServices.{{ $iService }}.description'
                                                        @if (!$editService) readonly @endif></textarea>
                                                </td>
                                                <td class="text-nowrap"><input type="text"
                                                        class="form-control w-100 @if ($editService) border-1 @else border-0 @endif"
                                                        wire:model='transactionServices.{{ $iService }}.price'
                                                        @if (!$editService) readonly @endif>
                                                </td>
                                                @if ($editService)
                                                    @if ($service->id)
                                                        <td class="text-center"><button type="button"
                                                                class="badge badge-center rounded-pill bg-label-danger"
                                                                wire:confirm='Apakah anda yakin ingin menghapus data ini?'
                                                                wire:click='destroyService("{{ $service->id }}")'><i
                                                                    class="bx bx-trash"></i></button>
                                                        </td>
                                                    @else
                                                        <td class="text-center"><button type="button"
                                                                class="badge badge-center rounded-pill bg-label-danger"><i
                                                                    class="bx bx-minus"
                                                                    wire:click='removeService({{ $iService }})'></i></button>
                                                        </td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @if ($editService)
                                                <td></td>
                                            @endif
                                            <td colspan="2">
                                            </td>
                                            <td class="text-enter"><strong>Total</strong></td>
                                            <td><strong>@uang($transaction->total)</strong></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            @if ($editService)
                                                <td></td>
                                            @endif
                                            <td colspan="3"></td>
                                            <td style="width: 280px !important"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            @if ($editService)
                                                <td></td>
                                            @endif
                                            <td colspan="5"><span
                                                    class="badge bg-label-danger w-100 p-3"><strong><em>#
                                                            @terbilang($transaction->total) #</em></strong></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <table class="mt-3">
                                                <tr>
                                                    <td class="text-enter w-10">Note</td>
                                                    <td>
                                                        <textarea rows="1" name="" id=""
                                                            class="form-control @if ($editTransaction) border-1 @else border-0 @endif"
                                                            wire:model='form.internal_note' @if (!$editTransaction) readonly @endif></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="row">
                        <div class="col">
                            <div class="card overflow-hidden mb-4" style="height: 300px">
                                <div class="card-header pb-1">
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class='bx bx-folder bx-md'></i>
                                                </div>
                                                <div class="flex-grow-1 row">
                                                    <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                                        <h4 class="mb-0">Documents</h4>
                                                        <small class="text-muted">Process</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 text-end">
                                            @if (!$editDocument)
                                                <button type="button" class="btn btn-icon btn-outline-warning"
                                                    wire:click='editDocumentMode'>
                                                    <span class="tf-icons bx bx-pencil"></span>
                                                </button>
                                            @else
                                                <div wire:loading.remove>
                                                    <button type="button" class="btn btn-icon btn-outline-primary"
                                                        wire:click='storeDocument'>
                                                        <span class="tf-icons bx bx-save"></span>
                                                    </button>
                                                    <button type="button" class="btn btn-icon btn-outline-danger"
                                                        wire:click='editDocumentMode'>
                                                        <span class="tf-icons bx bx-x"></span>
                                                    </button>
                                                </div>
                                                <div wire:loading
                                                    wire:target="{{ $this->transactionDocuments->map(function ($document, $idx) {
                                                            return "transactionDocuments.{$idx}.file";
                                                        })->join(', ') }}">
                                                    Loading...</div>
                                            @endif
                                        </div>
                                        <div class="divider p-0 m-0">
                                            <div class="divider-text"><i class='bx bx-search-alt'></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body ps ps--active-y perfect-scrollbar" id="vertical-example">
                                    {{-- @if ($editDocument)
                                        <div class="row">
                                            <div class="col text-end">
                                                <button type="button"
                                                    class="badge badge-center rounded-pill bg-label-success"><i
                                                        class="bx bx-plus"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row text-sm">
                                        <div class="col p-0">
                                            <div class="demo-inline-spacing mt-1 transaction-documents">
                                                <ul class="list-group">
                                                    @foreach ($transactionDocuments as $document)
                                                        <div wire:key="{{ $document->document->id }}">
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                @if ($editDocument)
                                                                    <input type="checkbox" class="form-check-input"
                                                                        value="{{ $document->document->id }}"
                                                                        wire:model='checkedDocument'>
                                                                @endif
                                                                {{ $document->document->name }}
                                                                <small>{{ $document->date }}</small>
                                                                <span
                                                                    class="badge @if ($document->document->date) bg-success
                                                                @else
                                                                bg-warning @endif rounded-pill p-1"><i
                                                                        class="bx @if ($document->document->date) bx-check
                                                                        @else
                                                                        bx-loader-circle @endif bx-xs"></i></span>
                                                            </li>
                                                        </div>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <table class="transaction-documents">
                                        @foreach ($transactionDocuments as $iDocument => $document)
                                            <tr class="align-content-center text-xs" wire:key='{{ $iDocument }}'>
                                                <td class="w-px-200">
                                                    @if ($editDocument)
                                                        <input type="checkbox" class="form-check-input me-1">
                                                    @endif
                                                    {{ $document->document->name }}
                                                    <br>
                                                    <small
                                                        class="@if ($document->date) text-success @endif ">{{ $document->date ? Carbon\Carbon::parse($document->date)->translatedFormat('d F Y H:i:s') : 'belum lengkap' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    @if ($editDocument)
                                                        <input type="file"
                                                            class="form-control form-control-sm w-100"
                                                            wire:model='transactionDocuments.{{ $iDocument }}.file'
                                                            id="transactionDocument{{ $iDocument }}file">
                                                    @elseif ($document->file)
                                                        <form action="{{ route('document.preview') }}" method="POST"
                                                            target="__blank">
                                                            @csrf
                                                            <input type="hidden" name="path"
                                                                value="{{ $document->file }}">
                                                            <button type="submit"
                                                                class="text-primary">{{ Str::limit(Str::of($document->file)->explode('/')->last(),32) }}</button>
                                                        </form>
                                                    @else
                                                        Dokumen belum terpenuhi
                                                    @endif
                                                </td>
                                                <td class="text-end w-1">
                                                    <div class="d-flex">
                                                        <span
                                                            class="badge me-1 @if ($document->date) bg-success
                                                                    @else
                                                                    bg-warning @endif rounded-pill p-1"><i
                                                                class="bx @if ($document->date) bx-check
                                                                            @else
                                                                            bx-loader-circle @endif bx-xs"></i></span>
                                                        @if ($transaction->documents->where('id', $document->document->id)->first()->pivot->file != $document->file)
                                                            <button type="submit"
                                                                class="badge 
                                                                    bg-danger rounded-pill p-1"
                                                                wire:click='revertUploadDocument("{{ $document->document->id }}")'><i
                                                                    class="bx bx-x bx-xs"></i></button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="ps__rail-x" style="left: 0px; bottom: -788px;">
                                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                    </div>
                                    <div class="ps__rail-y" style="top: 788px; height: 232px; right: 0px;">
                                        <div class="ps__thumb-y" tabindex="0" style="top: 156px; height: 45px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card overflow-hidden mb-4" style="height: 300px">
                                <div class="card-header pb-1">
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class='bx bx-file bx-md'></i>
                                                </div>
                                                <div class="flex-grow-1 row">
                                                    <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                                        <h4 class="mb-0">Invoices</h4>
                                                        <small class="text-muted">Process</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 text-end">
                                            <button type="button" class="btn btn-icon btn-outline-primary"
                                                wire:confirm="Apakah anda yakin? Invoice akan tergenerate"
                                                wire:click='generateInvoice'
                                                @if (!$generateable) disabled @endif>
                                                <span class="tf-icons bx bx-refresh"></span>
                                            </button>
                                        </div>
                                        <div class="divider p-0 m-0">
                                            <div class="divider-text"><i class='bx bx-search-alt'></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body ps ps--active-y perfect-scrollbar" id="vertical-example">
                                    <div class="row text-sm">
                                        <div class="col p-0">
                                            <div class="list-group">
                                                <a href="javascript:void(0);"
                                                    class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex justify-content-between w-100">
                                                        <h6 class="mb-0">saadhasd-asdasdasdsad-asdasdasaas</h6>
                                                        <small class="text-muted">Rp. 3.350.000</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between w-100">
                                                        <small>03 Januari 2024</small>
                                                        <small class="text-muted">Rp. 450.000</small>
                                                    </div>
                                                    <small class="text-muted">Donec id elit non mi porta.</small>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ps__rail-x" style="left: 0px; bottom: -788px;">
                                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                    </div>
                                    <div class="ps__rail-y" style="top: 788px; height: 232px; right: 0px;">
                                        <div class="ps__thumb-y" tabindex="0" style="top: 156px; height: 45px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card overflow-hidden mb-4" style="height: 300px">
                                <div class="card-header pb-1">
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class='bx bx-file bx-md'></i>
                                                </div>
                                                <div class="flex-grow-1 row">
                                                    <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                                        <h4 class="mb-0">Status</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 text-end">
                                            <button type="button" class="btn btn-icon btn-outline-primary"
                                                wire:confirm="Apakah anda yakin? Invoice akan tergenerate"
                                                wire:click='generateInvoice'
                                                @if (!$generateable) disabled @endif>
                                                <span class="tf-icons bx bx-refresh"></span>
                                            </button>
                                        </div>
                                        <div class="divider p-0 m-0">
                                            <div class="divider-text"><i class='bx bx-search-alt'></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body ps ps--active-y perfect-scrollbar" id="vertical-example">
                                    <div class="row text-sm">
                                        <div class="col p-0">
                                            <div class="list-group">
                                                <a href="javascript:void(0);"
                                                    class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <div class="d-flex justify-content-between w-100">
                                                        <h6 class="mb-0">saadhasd-asdasdasdsad-asdasdasaas</h6>
                                                        <small class="text-muted">Rp. 3.350.000</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between w-100">
                                                        <small>03 Januari 2024</small>
                                                        <small class="text-muted">Rp. 450.000</small>
                                                    </div>
                                                    <small class="text-muted">Donec id elit non mi porta.</small>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ps__rail-x" style="left: 0px; bottom: -788px;">
                                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                    </div>
                                    <div class="ps__rail-y" style="top: 788px; height: 232px; right: 0px;">
                                        <div class="ps__thumb-y" tabindex="0" style="top: 156px; height: 45px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
