<div>
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar @if ($count > 0) avatar-busy @endif ">
                <i class='bx bxs-bell-ring bx-md'></i>
            </div>
        </a>
        <div class="demo-inline-spacing mt-3 dropdown-menu dropdown-menu-end p-0">
            <div class="list-group text-xs">
                @foreach ($user->notifications->sortByDesc('created_at')->take(5) as $notification)
                    <a href="{{ json_decode($notification)->data->link }}"
                        class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex justify-content-between w-100">
                            <h6>{{ $notification->notifiable_type }}</h6>
                            <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans(['parts' => 3]) }}</small>
                        </div>
                        <p class="mb-1">
                            {{ json_decode($notification)->data->message }}
                        </p>
                        {{-- <small>Donec id elit non mi porta.</small> --}}
                    </a>
                @endforeach
                <a href="javascript:void(0);"
                    class="list-group-item list-group-item-action flex-column align-items-start">
                    <p class="mb-1 text-center">
                        See More Result...
                    </p>
                </a>
            </div>
        </div>
    </li>
</div>
