<div>
    <x-slot:title>
        Invoice Detail
    </x-slot>
    <div class="row justify-content-center">
        <div class="col">
            <h3>Detail</h3>
            <span class="text-md"><a href="{{ route('invoice') }}">Invoice</a> - Invoice Manager</span>
            <div class="row">
                <div class="col-xl-8">
                    <div class="card mt-3 min-vh-70 p-5">
                        <div class="card-header pb-0">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <p class="text-xl text-bold">
                                        <img src="{{ Vite::asset('resources/images/logo/Logo-Sinar-Laut-Maritim.png') }}"
                                            class="d-inline" alt="" width="15%">
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 justify-content-end">
                                    <div class="d-flex">
                                        <h4 class="text-bold mb-0">Invoice #</h4><span
                                            class="form-control-sm h-1 w-30 py-0 px-1 m-0 text-uppercase ms-1 top-0">{{ $form->number_display }}</span>
                                        @if ($editInvoice)
                                            @if ($form->number_display == 'DRAFT')
                                                <button type="button"
                                                    class="badge badge-center rounded-pill bg-label-primary"><i
                                                        class="bx bx-refresh" wire:click='generateNumber'></i></button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-6 text-end">
                                    @can('admin', App\Models\User::class)
                                        @if ($editInvoice)
                                            <button type="button" class="btn btn-icon btn-outline-success"
                                                wire:click='saveInvoice'>
                                                <span class="tf-icons bx bx-save"></span>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-outline-danger">
                                                <span class="tf-icons bx bx-x" wire:click='editInvoiceMode'></span>
                                            </button>
                                        @elseif($form->number_display == 'DRAFT')
                                            <button type="button" class="btn btn-icon btn-outline-warning"
                                                wire:click='editInvoiceMode'>
                                                <span class="tf-icons bx bx-pencil"></span>
                                            </button>
                                        @endif
                                        @if ($paymentTransaction->id)
                                            <button type="button" class="btn btn-icon btn-outline-secondary"
                                                wire:click="print" wire:ignore.self>
                                                <span class="tf-icons bx bx-printer"></span>
                                            </button>
                                        @endif
                                        @if ($paymentTransaction->id)
                                            <button type="button" class="btn btn-icon btn-outline-info" wire:click="send"
                                                wire:ignore.self>
                                                <span class="tf-icons bx bx-mail-send"></span>
                                            </button>
                                        @endif
                                    @else
                                        @if ($paymentTransaction->id)
                                            <button type="button" class="btn btn-icon btn-outline-secondary"
                                                wire:click="print" wire:ignore.self>
                                                <span class="tf-icons bx bx-printer"></span>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted mb-0">Issue Date:</p>
                                    <p><strong>{{ Carbon\Carbon::parse($form->issue_date)->format('d F Y h:i:s') }}</strong>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Due Date For:</p>
                                    <p><strong>{{ Carbon\Carbon::parse($form->due_date)->format('d F Y h:i:s') }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted mb-0">Issued For:</p>
                                    <p>
                                        <a href="{{ route('transaction.detail', ['transaction' => $form->transaction_id]) }}"
                                            target="__blank"><strong>{{ $form->customer_name }}</strong></a><br>
                                        <span class="text-muted">{{ $form->customer_address }} <br>
                                            {{ $form->customer_email }} <br>
                                            {{ $form->customer_phone_number }}</span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Issued By:</p>
                                    <p>
                                        <strong>{{ companyData()['name'] }}</strong> ({{ $invoice->createdBy->name }})
                                        <br>
                                        <span class="text-muted">{{ companyData()['address'] }} <br>
                                            {{ companyData()['email'] }} <br>
                                            {{ companyData()['phone_number'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-p-2">
                                        <thead>
                                            <tr>
                                                @if ($editInvoice)
                                                    <th><input type="checkbox" class="form-check-input" id="parentCheck"
                                                            wire:click='checkboxParent' wire:model='parentCheckbox'>
                                                    </th>
                                                @endif
                                                <th>Item</th>
                                                <th colspan="2">Description</th>
                                                <th>Price</th>
                                                <th>
                                                    @can('admin', App\Models\User::class)
                                                        @if (!$editService && !$invoice->number_display)
                                                            <button type="button"
                                                                class="badge badge-center rounded-pill bg-label-warning"><i
                                                                    class="bx bx-pencil"
                                                                    wire:click='editServiceMode'></i></button>
                                                        @elseif($editService)
                                                            <button type="button"
                                                                class="badge badge-center rounded-pill bg-label-primary"><i
                                                                    class="bx bx-save"
                                                                    wire:click='saveServices'></i></button>
                                                            <button type="button"
                                                                class="badge badge-center rounded-pill bg-label-success"><i
                                                                    class="bx bx-plus" wire:click='addService'></i></button>
                                                            <button type="button"
                                                                class="badge badge-center rounded-pill bg-label-danger"><i
                                                                    class="bx bx-x"
                                                                    wire:click='editServiceMode'></i></button>
                                                        @endif
                                                    @endcan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoiceServices as $iService => $service)
                                                <tr wire:key="$iService">
                                                    @if ($editService)
                                                        <td class="text-center">
                                                            <input type="checkbox" class="form-check-input"
                                                                wire:model='checkedServices'
                                                                value="{{ $service->id }}" wire:click='clickService'
                                                                @if (!$editService) readonly @endif>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <input type="text"
                                                            class="form-control w-100 @if ($editService) border-1 @else border-0 @endif @error('invoiceServices.' . $iService . '.name')
                                                            is-invalid
                                                        @enderror"
                                                            wire:model='invoiceServices.{{ $iService }}.name'
                                                            @if (!$editService) readonly @endif>
                                                        @error('invoiceServices.' . $iService . '.name')
                                                            <span class="invalid-feedback">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </td>
                                                    <td colspan="2">
                                                        <textarea rows="1" name="" id=""
                                                            class="form-control @if ($editService) border-1 @else border-0 @endif"
                                                            wire:model='invoiceServices.{{ $iService }}.description' @if (!$editService) readonly @endif></textarea>
                                                    </td>
                                                    <td class="text-nowrap"><input type="text"
                                                            class="form-control w-100 @if ($editService) border-1 @else border-0 @endif @error('invoiceServices.' . $iService . '.price')
                                                            is-invalid
                                                        @enderror"
                                                            wire:model='invoiceServices.{{ $iService }}.price'
                                                            @if (!$editService) readonly @endif>
                                                        @error('invoiceServices.' . $iService . '.price')
                                                            <span class="invalid-feedback">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
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
                                            {{-- @if ($form->tax > 0) --}}
                                            <tr>
                                                @if ($editService)
                                                    <td></td>
                                                @endif
                                                <td colspan="2">
                                                </td>
                                                <td>
                                                    @if ($editInvoice)
                                                        <input type="checkbox" class="form-check-input"
                                                            id="tax" wire:click='updateTax' wire:model='tax'>
                                                    @endif
                                                    Ppn
                                                </td>
                                                <td>{{ $form->tax }}</td>
                                                <td></td>
                                            </tr>
                                            {{-- @endif --}}
                                            {{-- @if ($form->stamp > 0) --}}
                                            <tr>
                                                @if ($editService)
                                                    <td></td>
                                                @endif
                                                <td colspan="2">
                                                </td>
                                                <td>
                                                    Materai
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control w-100 @if ($editInvoice) border-1 @else border-0 @endif @error('form.stamp')
                                                            is-invalid
                                                        @enderror"
                                                        wire:model='form.stamp'
                                                        @if (!$editInvoice) readonly @endif>
                                                    @error('form.stamp')
                                                        <span class="invalid-feedback">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td></td>
                                            </tr>
                                            {{-- @endif --}}
                                            <tr>
                                                @if ($editService)
                                                    <td></td>
                                                @endif
                                                <td colspan="2">
                                                </td>
                                                <td class="text-enter"><strong>Total</strong></td>
                                                <td><strong>@uang($form->total)</strong></td>
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
                                                                @terbilang($form->total) #</em></strong></span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <table class="mt-3">
                                                    <tr>
                                                        <td class="text-enter w-10">Note</td>
                                                        <td>
                                                            <textarea rows="1" name="" id=""
                                                                class="form-control @if ($editInvoice) border-1 @else border-0 @endif"
                                                                wire:model='form.internal_note' @if (!$editInvoice) readonly @endif></textarea>
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
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5>Payment Detail</h5>
                                    @if (!$payment->id)
                                        <span class="badge bg-label-danger">Not Generated</span>
                                    @else
                                        <span
                                            class="badge bg-label-{{ paymentColor($paymentTransaction->status) }}">{{ $paymentTransaction->status }}</span>
                                    @endif
                                </div>
                                <div class="col-6 text-end">
                                    @if ($invoice->number_display != 'DRAFT' && !$payment->id)
                                        <button class="btn btn-outline-success" type="button" id="payNow"
                                            wire:click='payNow'>Pay
                                            Now</button>
                                    @elseif($paymentTransaction->status == 'pending')
                                        <button class="btn btn-outline-danger" type="button"
                                            wire:click='payCancel'>Cancel Payment</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($payment->id)
                                <div class="row align-content-center">
                                    <div class="col">
                                        <ol>
                                            <li class="mb-3">
                                                <span class="text-muted">Payment Id:</span> <br>
                                                <strong>{{ Str::upper($payment->id) }}</strong>
                                            </li>
                                            <li class="mb-3">
                                                <span class="text-muted">Bank:</span> <br>
                                                <strong>{{ Str::upper($paymentTransaction->bank) }}</strong>
                                            </li>
                                            <li class="mb-3">
                                                <span class="text-muted">VA Number:</span> <br>
                                                <strong>{{ $paymentTransaction->va_number }}</strong>
                                            </li>
                                            <li class="mb-3">
                                                <span class="text-muted">Payment Term:</span> <br>
                                                <strong>{{ \Carbon\Carbon::parse($paymentTransaction->transaction_time)->translatedFormat('d F Y H:i:s') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($paymentTransaction->expiry_time)->translatedFormat('d F Y H:i:s') }}</strong>
                                                <div wire:poll.1s>
                                                    @if ($paymentTransaction->status == 'pending')
                                                        <small class="text-danger">
                                                            @if ($paymentTransaction->expiry_time > \Carbon\Carbon::now()->format('Y-m-d H:i:s'))
                                                                {{ \Carbon\Carbon::parse($paymentTransaction->expiry_time)->diffForHumans(['parts' => 3]) }}
                                                            @else
                                                                Expired
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="mb-3">
                                                <span class="text-muted">Nominal:</span> <br>
                                                <strong>@uang($payment->amount)</strong>
                                            </li>
                                            <li class="mb-3">
                                                <span class="text-muted">Terbilang:</span> <br>
                                                <strong><strong><em>#
                                                            @terbilang($form->total) #</em></strong></strong>
                                            </li>
                                            @if ($paymentTransaction->settlement_time)
                                                <li class="mb-3">
                                                    <span class="text-muted">Payment Date:</span> <br>
                                                    <strong>{{ \Carbon\Carbon::parse($paymentTransaction->settlement_time)->format('d F Y H:i:s') }}</strong>
                                                </li>
                                            @endif
                                        </ol>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @script
    <script src="text/javascript">
        $(document).on("click", "#payNow", async function(e) {
            e.preventDefault();
            await new Promise(function(resolve, reject) {
                resolve($wire.payNow());
            }).then((result) => {
                window.snap.embed(result, {
                    embedId: 'snap-container',
                    onSuccess: function(result) {
                        /* You may add your own implementation here */
                        alert("payment success!");
                        console.log(result);
                    },
                    onPending: function(result) {
                        /* You may add your own implementation here */
                        alert("wating your payment!");
                        console.log(result);
                    },
                    onError: function(result) {
                        /* You may add your own implementation here */
                        alert("payment failed!");
                        console.log(result);
                    },
                    onClose: function() {
                        /* You may add your own implementation here */
                        alert('you closed the popup without finishing the payment');
                    }
                });
            }).catch((err) => {
                alert(err);
            });
        });
    </script>
@endscript --}}

{{-- @push('midtrans')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $midtransClientKey }}"></script>
@endpush --}}
