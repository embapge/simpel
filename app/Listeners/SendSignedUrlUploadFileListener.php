<?php

namespace App\Listeners;

use App\Events\CustomerRegistratedEvent;
use App\Mail\VerificationLinkUploadMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\UrlSigner\Laravel\Facades\UrlSigner;

class SendSignedUrlUploadFileListener implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(CustomerRegistratedEvent $event): void
    {
        Mail::to($event->verification->email)->send(new VerificationLinkUploadMail($event->verification, $event->url, $event->message));
    }
}
