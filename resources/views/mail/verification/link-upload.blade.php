<x-mail::message>
Kepada {{ $verification->name }}

{{ $message }}

<x-mail::button :url="$url">
Link Upload
</x-mail::button>

Hormat Kami,<br>
{{ config('app.name') }}
</x-mail::message>
