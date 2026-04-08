<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InvoiceMail extends Mailable
{
    public $booking;

    public function __construct(Booking $booking) {
        $this->booking = $booking;
    }

    public Envelope envelope(): Envelope {
        return new Envelope(
            subject: 'Invoice Pemesanan Photoholic - ' . $this->booking->booking_code,
        );
    }

    public Content content(): Content {
        return new Content(
            view: 'emails.invoice', // Kita buat view-nya nanti
        );
    }
}