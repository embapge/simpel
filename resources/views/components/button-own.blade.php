@props([
    "color" => "primary",
    "icon" => "bxs-plus-circle"
])

<button type="button" class="btn btn-icon btn-{{ $color }} ms-2" {{ $attributes }}>
    <span class="tf-icons bx {{ $icon }} bx-sm"></span>
</button>