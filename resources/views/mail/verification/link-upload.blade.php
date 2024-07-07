<x-mail::message>
Kepada {{ $verification->name }}

{{ $message }}

<x-mail::button :url="$url">
Link Upload
</x-mail::button>

Hormat Kami,<br>
{{ config('app.name') }}

@include("mail.footer")

</x-mail::message>
