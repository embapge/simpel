@props(['name' => "", "class" => ""])

@error($name)
    <span class="text-danger {{ $class }}">
        {{ $message }}
    </span>
@enderror