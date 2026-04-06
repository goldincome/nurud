<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\BookingStatus;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['itineraries'])
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['travelers', 'itineraries', 'payments', 'travelerPricings'])
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

        $banks = \App\Models\Bank::all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('booking.reservation_ticket', compact('booking', 'banks'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'ticket_' . $booking->reservation_id . '.pdf';

        return $pdf->download($filename);
    }
}
