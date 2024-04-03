<div>
    <x-slot:title>
        Transaksi Detail
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <h2>Detail</h2>
            <span class="text-lg"><a href="{{ route('transaction') }}">Transaction</a> - Transaction Manager</span>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mt-3">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-xl-6 justify-content-end">
                                    <div class="d-flex">
                                        <h4 class="text-bold mb-0">Transaksi #</h4><input type="text"
                                            class="form-control-sm h-1 w-30 p-0 m-0 border-0 text-uppercase text-bold ms-1 top-0"
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
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <table class="mx-3 text-sm text-justify">
                                        <tr class="align-top">
                                            <td class="w-25">Company</td>
                                            <td>Sinar Lautan Maritim</td>
                                        </tr>
                                        <tr class="align-top">
                                            <td>PIC Name</td>
                                            <td>Mohammad Barata</td>
                                        </tr>
                                        <tr class="align-top">
                                            <td>Address</td>
                                            <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Atque, eum.
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
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            @if ($editMode)
                                                <th><input type="checkbox" class="form-check-input" id="parentCheck"
                                                        wire:click='checkboxParent' wire:model='parentCheckbox'></th>
                                            @endif
                                            <th>Item</th>
                                            <th>Description</th>
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
                                        @foreach ($transactionServicesWrapper as $iService => $service)
                                            <tr wire:key="$iService">
                                                @if ($editMode)
                                                    <td>
                                                        <input type="checkbox" class="form-check-input"
                                                            wire:model='checkedServices' value="{{ $service->id }}"
                                                            wire:click='clickService'
                                                            @if (!$editMode) readonly @endif>
                                                    </td>
                                                @endif
                                                <td><input type="text" class="form-control p-0 border-0 w-100"
                                                        wire:model='transactionServicesWrapper.{{ $iService }}.name'
                                                        @if (!$editMode) readonly @endif>
                                                </td>
                                                <td>
                                                    <textarea rows="1" name="" id="" class="form-control border-0 p-0"
                                                        wire:model='transactionServicesWrapper.{{ $iService }}.description'
                                                        @if (!$editMode) readonly @endif></textarea>
                                                </td>
                                                <td class="text-nowrap"><input type="text"
                                                        class="form-control p-0 border-0 w-100"
                                                        wire:model='transactionServicesWrapper.{{ $iService }}.price'
                                                        @if (!$editMode) readonly @endif>
                                                </td>
                                                @if ($editMode)
                                                    <td class="text-center"><button type="button"
                                                            class="badge badge-center rounded-pill bg-label-danger"><i
                                                                class="bx bx-trash"></i></button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        @foreach ($services as $iService => $service)
                                            <tr wire:key="$iService">
                                                @if ($editMode)
                                                    <td>
                                                    </td>
                                                @endif
                                                <td><input type="text"
                                                        class="form-control p-0  w-100 @if ($editMode) border-1 @else border-0 @endif"
                                                        wire:model='services.{{ $iService }}.name'
                                                        @if (!$editMode) readonly @endif>
                                                </td>
                                                <td>
                                                    <textarea rows="1" name="" id=""
                                                        class="form-control p-0 @if ($editMode) border-1 @else border-0 @endif"
                                                        wire:model='services.{{ $iService }}.description' @if (!$editMode) readonly @endif></textarea>
                                                </td>
                                                <td class="text-nowrap"><input type="text"
                                                        class="form-control p-0 w-100 @if ($editMode) border-1 @else border-0 @endif"
                                                        wire:model='services.{{ $iService }}.price'
                                                        @if (!$editMode) readonly @endif>
                                                </td>
                                                @if ($editMode)
                                                    <td class="text-center"><button type="button"
                                                            class="badge badge-center rounded-pill bg-label-danger"><i
                                                                class="bx bx-minus"
                                                                wire:click='removeService({{ $iService }})'></i></button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
