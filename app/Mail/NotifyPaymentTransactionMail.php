<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Log;

class NotifyPaymentTransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Invoice $invoice)
    {
        $this->invoice->load(["payment.transaction"]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pemberitahuan Status Pembayaran Bapak/Ibu {$this->invoice->customer_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: $this->markdownTemplate(),
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('invoice.pdf', ["invoice" => $this->invoice, "response" => json_decode($this->invoice->payment->transaction->response, true)]);
        return [
            Attachment::fromData(fn () => $pdf->output(), "{$this->invoice->customer_name} Billing Information.pdf")
                ->withMime('application/pdf'),
        ];
    }

    public function markdownTemplate()
    {
        if (json_decode($this->invoice->payment->transaction->response, true)["transaction_status"] == "pending") {
            return "mail.text.payment.created";
        } else if (json_decode($this->invoice->payment->transaction->response, true)["transaction_status"] == "settlement") {
            return "mail.text.payment.paid";
        }
    }
}
