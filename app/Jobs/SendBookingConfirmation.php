<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Mail\BookingConfirmed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle(): void
    {
        try {
            Mail::to($this->booking->customer_email)->send(new BookingConfirmed($this->booking));
            Log::info('Booking confirmation email queued successfully', ['pnr' => $this->booking->reference_number]);
        } catch (\Exception $e) {
            Log::error('Failed to send queued booking confirmation email', [
                'pnr' => $this->booking->reference_number,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
