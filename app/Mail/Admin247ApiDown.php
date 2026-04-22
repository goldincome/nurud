<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Admin247ApiDown extends Mailable
{
    use Queueable, SerializesModels;

    public string $error;
    public string $endpoint;

    public function __construct(string $error = '', string $endpoint = '')
    {
        $this->error = $error;
        $this->endpoint = $endpoint;
    }

    public function envelope(): Envelope
    {
        $settings = \App\Models\GeneralSetting::first();
        $fromEmail = (!empty($settings->contact_email)) ? $settings->contact_email : 'info@nurud.com';
        $fromName = (!empty($settings->company_name)) ? $settings->company_name : 'Nurud Travels';

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromEmail, $fromName),
            subject: '⚠️ 247 Travels API is DOWN — Immediate Attention Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.247-api-down',
            with: [
                'error' => $this->error,
                'endpoint' => $this->endpoint,
            ],
        );
    }
}
