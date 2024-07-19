<div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
            <h5 class="m-0 me-2">Jenis Transaksi</h5>
            {{-- <small class="text-muted">42.82k Total Sales</small> --}}
        </div>
        <div class="dropdown">
            <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column align-items-center gap-1">
                <h2 class="mb-2">{{ $transactions->sum('amount') }}</h2>
                <span>Total Transaksi</span>
            </div>
            <livewire:transaction-donut-chart chartId="transactionDonutChart" :transactions="$transactions" />
        </div>
        <ul class="p-0 m-0">
            @foreach ($transactions as $transaction)
                <li class="d-flex mb-4 pb-1">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                        <div class="me-2">
                            <h6 class="mb-0">
                                {{ Str::of("$transaction->name")->explode(' ')->map(fn($title) => \Illuminate\Support\Str::upper($title[0]))->join('') }}
                            </h6>
                            <small class="text-muted">{{ \Illuminate\Support\Str::title("$transaction->name") }}</small>
                        </div>
                        <div class="user-progress">
                            <small class="fw-semibold">{{ $transaction->amount }}</small>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
