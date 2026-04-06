<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['travelers', 'payments']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmed - Ticket Processing : ' . $this->booking->reference_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment.confirmed',
            with: [
                'booking' => $this->booking,
                'issuedAt' => $this->booking->ticket_issued_at,
            ],
        );
    }
}
