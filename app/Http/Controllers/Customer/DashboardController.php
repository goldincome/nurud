<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\BookingStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_payment' => Booking::where('user_id', $user->id)->where('status', BookingStatus::PENDING_PAYMENT)->count(),
            'completed_booking' => Booking::where('user_id', $user->id)->where('status', BookingStatus::CONFIRMED)->count(),
        ];

        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['itineraries'])
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentBookings'));
    }
}
