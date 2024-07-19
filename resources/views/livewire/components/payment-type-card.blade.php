<div>
    <ul class="p-0 m-0">
        @foreach ($vendors as $vendor)
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                    <img src="{{ Vite::asset('resources/images/icons/unicons/paypal.png') }}" alt="User"
                        class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                        <small class="text-muted d-block mb-1">Virtual Account</small>
                        <h6 class="mb-0">{{ $vendor['name'] }}</h6>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                        <h6 class="mb-0">@uang($vendor['total'])</h6>
                        <span class="text-muted">Rupiah</span>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
