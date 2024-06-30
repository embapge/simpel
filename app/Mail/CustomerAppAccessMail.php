<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerAppAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password To Login',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.customers.access',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
