<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BuyNowPayLaterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $banks;

    public function __construct(\App\Models\Booking $booking, $banks)
    {
        $this->booking = $booking;
        $this->banks = $banks;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: Finalize Your Buy Now, Pay Later Booking',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bnpl_booking',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
