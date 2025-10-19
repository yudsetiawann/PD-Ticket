<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SendETicket extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Anda untuk ' . $this->order->event->title,
        );
    }

    public function content(): Content
    {
        // Email bisa berupa teks sederhana atau view Blade
        return new Content(
            view: 'emails.eticket-notification',
        );
    }

    public function attachments(): array
    {
        // Ambil PDF e-ticket dari media library
        $eticket = $this->order->getFirstMedia('etickets');

        return [
            Attachment::fromPath($eticket->getPath())
                ->as($eticket->file_name)
                ->withMime($eticket->mime_type),
        ];
    }
}
