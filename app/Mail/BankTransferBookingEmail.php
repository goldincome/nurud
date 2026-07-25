<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Bank;


class BankTransferBookingEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $banks;

    public function __construct(\App\Models\Booking $booking)
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
            subject: 'Action Required: Complete Your Booking Payment',
        );
    }

    public function content(): Content
    {
        // Fetch Bank data right here. The background queue worker will execute this.
        $this->banks = Bank::all();
        return new Content(
            view: 'emails.bank_transfer_booking',
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
