<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Traveler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Enums\PaymentStatus;

class TicketService
{
    /**
     * Generate PDF ticket for a booking
     */
    public function generateTicket(Booking $booking): array
    {
        try {
            // Validate booking has confirmed payment
            if ($booking->payment_status !== PaymentStatus::PAID) {
                throw new \Exception('Booking must be paid before generating ticket');
            }

            // Generate ticket data
            $ticketData = $this->prepareTicketData($booking);

            // Generate PDF content
            $pdf = $this->createPdf($ticketData);
            
            // Save PDF to storage
            $filename = "ticket_{$booking->pnr}.pdf";
            $path = "tickets/{$filename}";
            
            Storage::put($path, $pdf->output());
            
            Log::info('Ticket generated successfully', [
                'booking_id' => $booking->id,
                'pnr' => $booking->pnr,
                'path' => $path
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $path,
                'url' => Storage::url($path),
                'download_url' => route('bookings.ticket.download', $booking->id)
            ];

        } catch (\Exception $e) {
            Log::error('Ticket generation failed', [
                'booking_id' => $booking->id ?? null,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Prepare ticket data for PDF generation
     */
    protected function prepareTicketData(Booking $booking): array
    {
        // Load relationships
        $booking->load([
            'travelers',
            'payments' => function ($query) {
                $query->where('status', PaymentStatus::COMPLETED)->latest();
            }
        ]);

        // Get flight details (assuming it's stored in flight_details JSON)
        $flightDetails = json_decode($booking->flight_details, true);

        return [
            'booking' => $booking,
            'travelers' => $booking->travelers,
            'payment' => $booking->payments->first(),
            'flight_details' => $flightDetails,
            'qr_code_data' => $this->generateQrCodeData($booking),
            'generation_date' => now()->format('Y-m-d H:i:s'),
            'terms_conditions' => $this->getTermsConditions(),
        ];
    }

    /**
     * Create PDF using DomPDF
     */
    protected function createPdf(array $ticketData): \Barryvdh\DomPDF\PDF
    {
        // Get the ticket view
        $view = View::make('booking.ticket', $ticketData);
        
        // Generate PDF
        $pdf = PDF::loadView('booking.ticket', $ticketData)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'dpi' => 300,
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'margin_top' => 0.75,
                'margin_right' => 0.75,
                'margin_bottom' => 0.75,
                'margin_left' => 0.75,
            ]);

        return $pdf;
    }

    /**
     * Generate QR code data for ticket
     */
    protected function generateQrCodeData(Booking $booking): string
    {
        // QR code contains PNR and booking validation data
        $qrData = json_encode([
            'pnr' => $booking->pnr,
            'booking_id' => $booking->id,
            'validation_key' => hash('sha256', $booking->pnr . $booking->created_at),
            'timestamp' => now()->timestamp,
        ]);

        return base64_encode($qrData);
    }

    /**
     * Get terms and conditions text
     */
    protected function getTermsConditions(): string
    {
        return "
        <h3>Terms and Conditions</h3>
        <ol>
            <li>This ticket is non-transferable and non-refundable unless otherwise stated.</li>
            <li>Passengers must present valid identification at check-in.</li>
            <li>Check-in closes 45 minutes before departure for domestic flights.</li>
            <li>Passengers must arrive at the gate 15 minutes before departure.</li>
            <li>Baggage allowances are as per airline policy.</li>
            <li>Flight schedules are subject to change without notice.</li>
            <li>For assistance, contact our 24/7 customer service.</li>
        </ol>
        ";
    }

    /**
     * Stream ticket to browser
     */
    public function streamTicket(Booking $booking)
    {
        $result = $this->generateTicket($booking);
        
        if (!$result['success']) {
            throw new \Exception($result['error']);
        }

        $path = Storage::path($result['path']);
        
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ticket_' . $booking->pnr . '.pdf"',
            'Content-Length' => filesize($path)
        ]);
    }

    /**
     * Download ticket to user's device
     */
    public function downloadTicket(Booking $booking)
    {
        $result = $this->generateTicket($booking);
        
        if (!$result['success']) {
            throw new \Exception($result['error']);
        }

        $path = Storage::path($result['path']);
        
        return response()->download($path, "ticket_{$booking->pnr}.pdf", [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * Check if ticket already exists and is recent
     */
    public function ticketExists(Booking $booking): bool
    {
        $filename = "ticket_{$booking->pnr}.pdf";
        $path = "tickets/{$filename}";
        
        return Storage::exists($path);
    }

    /**
     * Clean up old tickets (older than 30 days)
     */
    public function cleanupOldTickets(): array
    {
        $ticketsPath = 'tickets';
        $cleaned = 0;
        $errors = 0;

        if (Storage::exists($ticketsPath)) {
            $files = Storage::allFiles($ticketsPath);
            
            foreach ($files as $file) {
                try {
                    $lastModified = Storage::lastModified($file);
                    $daysOld = (time() - $lastModified) / (60 * 60 * 24);
                    
                    if ($daysOld > 30) {
                        Storage::delete($file);
                        $cleaned++;
                    }
                } catch (\Exception $e) {
                    $errors++;
                    Log::warning('Failed to cleanup ticket file', [
                        'file' => $file,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        Log::info('Ticket cleanup completed', [
            'cleaned' => $cleaned,
            'errors' => $errors
        ]);

        return [
            'cleaned' => $cleaned,
            'errors' => $errors
        ];
    }
}
