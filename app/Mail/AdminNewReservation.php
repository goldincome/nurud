<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewReservation extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        $settings = \App\Models\GeneralSetting::first();
        $fromEmail = (!empty($settings->contact_email)) ? $settings->contact_email : 'info@nurud.com';
        $fromName = (!empty($settings->company_name)) ? $settings->company_name : 'Nurud Travels';

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromEmail, $fromName),
            subject: '🆕 New Reservation: ' . $this->booking->reference_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-reservation',
            with: ['booking' => $this->booking],
        );
    }
}
