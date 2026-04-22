<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminStripeNoTicket extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $error;

    public function __construct(Booking $booking, string $error = '')
    {
        $this->booking = $booking;
        $this->error = $error;
    }

    public function envelope(): Envelope
    {
        $settings = \App\Models\GeneralSetting::first();
        $fromEmail = (!empty($settings->contact_email)) ? $settings->contact_email : 'info@nurud.com';
        $fromName = (!empty($settings->company_name)) ? $settings->company_name : 'Nurud Travels';

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromEmail, $fromName),
            subject: '🚨 URGENT: Stripe Payment Confirmed but Ticket NOT Issued - ' . $this->booking->reference_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.stripe-no-ticket',
            with: [
                'booking' => $this->booking,
                'error' => $this->error,
            ],
        );
    }
}
