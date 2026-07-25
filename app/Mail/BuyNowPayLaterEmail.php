<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Imported for queueing
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\Bank;
use App\Models\GeneralSetting;
use Illuminate\Mail\Mailables\Address;

class BuyNowPayLaterEmail extends Mailable implements ShouldQueue // Added implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $banks;

    // Removed $banks from constructor parameters to keep the payload clean
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        $settings = GeneralSetting::first();
        $fromEmail = (!empty($settings->contact_email)) ? $settings->contact_email : 'info@nurud.com';
        $fromName = (!empty($settings->company_name)) ? $settings->company_name : 'Nurud Travels';

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: 'Action Required: Finalize Your Buy Now, Pay Later Booking',
        );
    }

    public function content(): Content
    {
        // Fetch Bank data right here. The background queue worker will execute this.
        $this->banks = Bank::all();

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
