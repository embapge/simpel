<?php

namespace App\Notifications;

use App\Models\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Verification $verification)
    {
        $this->verification->load(["subType.transactionType"]);
    }

    public function databaseType(object $notifiable): string
    {
        return 'user-registration';
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            "verification_id" => $this->verification->id,
            "message" => "{$this->verification->name} melakukan registrasi pelayanan, dengan jasa {$this->verification->subType->transactionType->name} dan sub jasa {$this->verification->subType->name}",
            "link" => ""
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject("{$this->verification->name} Melakukan Registrasi Layanan")->greeting("Halo!!")->line("{$this->verification->name} telah melakukan registrasi layanan. Mohon untuk melakukan pemantauan akan pelanggan ini.")->line("Terima kasih.");
    }
}
