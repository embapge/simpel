<x-mail::message>
<p>Berikut ini merupakan tagihan anda sebesar <strong>Rp.{{ number_format($invoice->total, 0, ',', '.') }}</strong>dengan nomor <strong>{{ Str::upper(json_decode($invoice->payment->transaction->response, true)['va_numbers'][0]['bank']) }} Virtual Account: {{ json_decode($invoice->payment->transaction->response, true)['va_numbers'][0]['va_number'] }}</strong></p><br>
Thanks,<br>
{{ config('app.name') }}

@include("mail.footer")

</x-mail::message>
