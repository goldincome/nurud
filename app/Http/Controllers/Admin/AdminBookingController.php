<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Enums\BookingStatus;
use App\Services\FlexiApiService;
use App\Services\BookingService;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reservation_id', 'like', "%{$search}%")
                    ->orWhere('pnr', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_first_name', 'like', "%{$search}%")
                    ->orWhere('customer_last_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(15);

        $bookingStatus = BookingStatus::class;
        return view('admin.bookings.index', compact('bookings', 'bookingStatus'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['travelers', 'payments', 'itineraries', 'travelerPricings']);
        $bookingStatus = BookingStatus::class;
        $paymentMethod = $booking->payments()->first()->payment_method->value ?? null;
        return view('admin.bookings.show', compact('booking', 'bookingStatus', 'paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingStatusRequest $request, Booking $booking)
    {
        if ($request->status === BookingStatus::CONFIRMED->value) {
            //dd(json_encode($booking->offer_data));
            $isConfirmed = app(BookingService::class)->confirmBookingAndIssueTicket($booking);
        }

        if ($request->status === BookingStatus::CANCELLED->value) {
            $booking->update([
                'status' => BookingStatus::CANCELLED->value,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Booking status updated successfully.');
    }

    // Download details functionality can be reused from existing controller or implemented here if specific format needed.
    // For now, focusing on show and status update.
}
