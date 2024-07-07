<x-mail::message>
Halo Bapak Test,

Silahkan klik button untuk melakukan reset password dan menggunakan aplikasi ini.

<x-mail::button url="http://localhost:8000/forgot-password">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
