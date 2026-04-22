<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmedTicketIssued extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['travelers', 'payments']);
    }

    public function envelope(): Envelope
    {
        $settings = \App\Models\GeneralSetting::first();
        $fromEmail = (!empty($settings->contact_email)) ? $settings->contact_email : 'info@nurud.com';
        $fromName = (!empty($settings->company_name)) ? $settings->company_name : 'Nurud Travels';

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromEmail, $fromName),
            subject: 'Payment Confirmed - Ticket Processed: ' . $this->booking->pnr,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'booking.reservation_ticket',
            with: [
                'booking' => $this->booking,
                'banks' => \App\Models\Bank::all(),
                'issuedAt' => $this->booking->ticket_issued_at,
            ],

        );
    }

    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('booking.ticket', [
            'booking' => $this->booking,
        ])->setPaper('a4', 'portrait')->setWarnings(false);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'Ticket-' . str_replace('/', '-', $this->booking->pnr) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
