<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookOnHoldEmail extends Mailable
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
            subject: 'Action Required: Complete Your Booking Payment',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.book_on_hold_email',
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
