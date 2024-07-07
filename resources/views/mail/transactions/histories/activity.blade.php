<x-mail::message>
Kepada Bapak/Ibu {{ \Illuminate\Support\Str::title($name) }}

Silahkan melakukan pemantauan aktifitas transaksi anda melalui link  di bawah ini.

<x-mail::button :url="$url">
Cek Aktifitas
</x-mail::button>

Jika Anda kesulitan mengeklik tombol "Cek Aktifitas", salin dan tempel URL di bawah ke browser web Anda: <a href="{{ $url }}">{{ $url }}</a>

Thanks,<br>
{{ config('app.name') }}

@include("mail.footer")

</x-mail::message>
