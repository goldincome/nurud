<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\BookingStatus;
use App\Services\SimlessPayService;

class BookingController extends Controller
{
    protected SimlessPayService $simlessPayService;

    public function __construct(
        SimlessPayService $simlessPayService
    ) {
        $this->simlessPayService = $simlessPayService;
    }
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['itineraries', 'priceInPounds'])
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['travelers', 'itineraries', 'payments', 'travelerPricings', 'priceInPounds'])
            ->findOrFail($id);

        return view('customer.bookings.show', compact('booking'));
    }

    public function downloadTicket($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['travelers', 'itineraries', 'payments', 'travelerPricings'])
            ->findOrFail($id);

        if ($booking->status === BookingStatus::CANCELLED) {
            return redirect()->back()->with('error', 'Ticket is not available for cancelled bookings.');
        }

        $banks = \App\Models\Bank::with('country')->get();
        $simlessPayService = $this->simlessPayService;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('booking.ticket', compact('booking', 'banks', 'simlessPayService'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'ticket_' . $booking->reservation_id . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadInvoice($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['travelers', 'itineraries', 'payments', 'travelerPricings', 'priceInPounds'])
            ->findOrFail($id);

        $banks = \App\Models\Bank::with('country')->get();
        $simlessPayService = $this->simlessPayService;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('booking.invoice', compact('booking', 'banks', 'simlessPayService'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'invoice_' . $booking->reservation_id . '.pdf';

        return $pdf->download($filename);
    }
}
