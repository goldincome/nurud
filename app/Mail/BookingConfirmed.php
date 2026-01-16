<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
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
            subject: 'Flight Booking Reservation - PNR: ' . $this->booking->pnr_reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.confirmed',
            with: [
                'booking' => $this->booking,
                'bankDetails' => app(\App\Services\PaymentService::class)->getBankTransferInstructions(),
                'expiresAt' => $this->booking->expires_at,
            ],
        );
    }
}
