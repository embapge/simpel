<?php

namespace App\Notifications;

use App\Mail\NotifyPaymentTransactionMail;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Midtrans\BCAService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailable;

class MidtransStatusChange extends Notification
{
    use Queueable;

    private $gateawayService;

    public function __construct(public Payment $payment)
    {
        $this->afterCommit();
        $this->gateawayService = (new BCAService($payment));
        $this->payment->load("invoice");
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function databaseType(object $notifiable): string
    {
        return 'payment-status';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'number_display' => $this->payment->invoice->id,
            'amount' => $this->payment->amount,
            "message" => $this->gateawayService->notificationMessage(),
            "link" => route("invoice.detail", ["invoice" => $this->payment->invoice->id])
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            "status" => json_decode($this->payment->transaction->response, true)["transaction_status"],
            "message" => $this->gateawayService->notificationMessage(),
        ]);
    }

    public function broadcastType(): string
    {
        return 'payment-status';
    }

    public function toMail(object $notifiable): Mailable
    {
        return (new NotifyPaymentTransactionMail($this->payment->invoice))->to("barata@ciptamedianusa.net", "Mohammad Barata Putra Gusti");
        // $response = json_decode($this->payment->transaction->response, true);
        // return (new MailMessage)
        //     ->from('no-reply@sinarlautanmaritim.com', 'Sinar Lautan Maritim')
        //     ->greeting("Kepada Yth Bapak/Ibu {$this->payment->invoice->customer_name}")
        //     ->lineIf($response["transaction_status"] == "pending", "Mohon untuk melakukan pembayaran dengan " . Str::upper($response["va_numbers"][0]["bank"]) . " Virtual Account sebesar Rp.{$this->payment->amount}")
        //     ->lineIf($response["transaction_status"] == "settlement", "Terima kasih telah mempercayakan pengurusan dokumen anda kepada PT. Sinar Lautan Maritim, mohon untuk menunggu update pengurusan dokumen anda");
        // ->lineIf($this->amount > 0, "Amount paid: {$this->amount}")
        // ->action('View Invoice', $url)
    }
}
