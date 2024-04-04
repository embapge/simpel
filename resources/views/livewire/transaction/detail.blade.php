<div>
    <x-slot:title>
        Transaksi Detail
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h3>Detail</h3>
            <span class="text-md"><a href="{{ route('transaction') }}">Transaction</a> - Transaction Manager</span>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mt-3">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-xl-6 justify-content-end">
                                    <div class="d-flex">
                                        <h4 class="text-bold mb-0">Transaksi #</h4><input type="text"
                                            class="form-control-sm h-1 w-30 py-0 px-1 m-0 text-uppercase ms-1 top-0 @if ($editMode) border-1 @else border-0 @endif"
                                            wire:model='form.number_display'
                                            @if (!$editMode) readonly @endif>
                                    </div>
                                </div>
                                <div class="col-xl-6 text-end">
                                    @if ($editMode)
                                        <button type="button" class="btn btn-icon btn-outline-success"
                                            wire:click='save'>
                                            <span class="tf-icons bx bx-check"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-danger">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-icon btn-outline-warning"
                                            wire:click='modeEdit'>
                                            <span class="tf-icons bx bx-pencil"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-secondary"
                                            wire:click='modeEdit'>
                                            <span class="tf-icons bx bx-printer"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <table class="mx-3 text-sm text-justify">
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
                                            @if ($editMode)
                                                <th><input type="checkbox" class="form-check-input" id="parentCheck"
                                                        wire:click='checkboxParent' wire:model='parentCheckbox'></th>
                                            @endif
                                            <th>Item</th>
                                            <th colspan="2">Description</th>
                                            <th>Price</th>
                                            @if ($editMode)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($editMode)
                                            <tr>
                                                <td colspan="4">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="badge badge-center rounded-pill bg-label-success"><i
                                                            class="bx bx-plus" wire:click='addService'></i></button>
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach ($transactionServices as $iService => $service)
                                            <tr wire:key="$iService">
                                                @if ($editMode)
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input"
                                                            wire:model='checkedServices' value="{{ $service->id }}"
                                                            wire:click='clickService'
                                                            @if (!$editMode) readonly @endif>
                                                    </td>
                                                @endif
                                                <td><input type="text"
                                                        class="form-control w-100 @if ($editMode) border-1 @else border-0 @endif"
                                                        wire:model='transactionServices.{{ $iService }}.name'
                                                        @if (!$editMode) readonly @endif>
                                                </td>
                                                <td colspan="2">
                                                    <textarea rows="1" name="" id=""
                                                        class="form-control @if ($editMode) border-1 @else border-0 @endif"
                                                        wire:model='transactionServices.{{ $iService }}.description' @if (!$editMode) readonly @endif></textarea>
                                                </td>
                                                <td class="text-nowrap"><input type="text"
                                                        class="form-control w-100 @if ($editMode) border-1 @else border-0 @endif"
                                                        wire:model='transactionServices.{{ $iService }}.price'
                                                        @if (!$editMode) readonly @endif>
                                                </td>
                                                @if ($editMode)
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
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @if ($editMode)
                                                <td></td>
                                            @endif
                                            <td colspan="2"></td>
                                            <td class="text-enter"><strong>Total</strong></td>
                                            <td><strong>{{ $transaction->total }}</strong></td>
                                            @if ($editMode)
                                                <td></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <table class="mt-3">
                                                <tr>
                                                    <td class="text-enter w-10">Note</td>
                                                    <td>
                                                        <textarea rows="1" name="" id=""
                                                            class="form-control @if ($editMode) border-1 @else border-0 @endif"
                                                            wire:model='form.internal_note' @if (!$editMode) readonly @endif></textarea>
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
            </div>
        </div>
    </div>
</div>
