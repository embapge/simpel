<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Spatie\WebhookClient\Models\WebhookCall;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// Schedule::command('midtrans:check')->everyMinute();

Schedule::command('model:prune', [
    '--model' => [WebhookCall::class],
])->daily();
