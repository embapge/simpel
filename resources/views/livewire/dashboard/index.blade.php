<div>
    <x-slot:title>
        Dashboard
    </x-slot>
    <div class="row">
        <div class="col-md-6 col-lg-4 order-1 mb-4">
            <livewire:components.total-type-card lazy />
        </div>
        <div class="col-md-6 col-lg-8 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header text-center">
                    <h3>Transaksi per bulan</h3>
                    {{-- <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-tabs-line-card-income"
                                aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                Income
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Expenses</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Profit</button>
                        </li>
                    </ul> --}}
                </div>
                <div class="card-body px-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            {{-- <div class="d-flex p-4 pt-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ Vite::asset('resources/images/icons/unicons/wallet.png') }}"
                                        alt="User" />
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Balance</small>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$459.10</h6>
                                        <small class="text-success fw-semibold">
                                            <i class="bx bx-chevron-up"></i>
                                            42.9%
                                        </small>
                                    </div>
                                </div>
                            </div> --}}
                            <livewire:components.transaction-graph-chart chartId="transactionChart" lazy />
                            {{-- <div class="d-flex justify-content-center pt-4 gap-2">
                                <div class="flex-shrink-0">
                                    <div id="expensesOfWeek1"></div>
                                </div>
                                <div>
                                    <p class="mb-n1 mt-1">Expenses This Week Wayaw</p>
                                    <small class="text-muted">$39 less than last week</small>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-6 mb-4 order-2">
                    <livewire:components.total-card
                        icon="{{ Vite::asset('resources/images/icons/unicons/chart-success.png') }}"
                        title="Total Customer" total="{{ $totalCustomer }}" :isCurrency="false" />
                </div>
                <div class="col-lg-6 mb-4 order-2">
                    <livewire:components.total-card
                        icon="{{ Vite::asset('resources/images/icons/unicons/cc-primary.png') }}"
                        title="Total Transaksi" total="{{ $totalTransaction }}" />
                </div>
                <div class="col-lg-6 mb-4 order-2">
                    <livewire:components.total-card
                        icon="{{ Vite::asset('resources/images/icons/unicons/paypal.png') }}" title="Total Invoice"
                        total="{{ $totalInvoice }}" />
                </div>
                <div class="col-lg-6 mb-4 order-2">
                    <livewire:components.total-card
                        icon="{{ Vite::asset('resources/images/icons/unicons/wallet-info.png') }}"
                        title="Total Pembayaran" total="{{ $totalInvoice }}" />
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header text-center">
                    <h3>Invoice per bulan</h3>
                    {{-- <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-tabs-line-card-income"
                                aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                Income
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Expenses</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Profit</button>
                        </li>
                    </ul> --}}
                </div>
                <div class="card-body px-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            {{-- <div class="d-flex p-4 pt-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ Vite::asset('resources/images/icons/unicons/wallet.png') }}"
                                        alt="User" />
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Balance</small>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$459.10</h6>
                                        <small class="text-success fw-semibold">
                                            <i class="bx bx-chevron-up"></i>
                                            42.9%
                                        </small>
                                    </div>
                                </div>
                            </div> --}}
                            <livewire:components.invoice-graph-card chartId="invoiceChart" lazy />
                            {{-- <div class="d-flex justify-content-center pt-4 gap-2">
                                <div class="flex-shrink-0">
                                    <div id="expensesOfWeek1"></div>
                                </div>
                                <div>
                                    <p class="mb-n1 mt-1">Expenses This Week Wayaw</p>
                                    <small class="text-muted">$39 less than last week</small>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Transactions</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <livewire:components.payment-type-card lazy />
                </div>
            </div>
            {{-- <livewire:components.graph-chart chartId="Wayaw2" /> --}}
        </div>
    </div>
</div>

@push('layout-header')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.css', 'resources/assets/js/config.js'])
@endpush

@once
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/js/dashboards-analytics.js'])
@endonce
