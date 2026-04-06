<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Enums\CustomerType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Enums\BookingStatus;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of all customers.
     */
    public function index(Request $request): View
    {
        $query = User::where('type', CustomerType::CUSTOMER)
            ->withCount('bookings')
            ->withSum('bookings', 'total_price');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone_no', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');

        $allowedSorts = ['created_at', 'name', 'email', 'bookings_count', 'bookings_sum_total_price'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        $customers = $query->paginate(20)->appends($request->query());

        // Summary stats
        $totalCustomers = User::where('type', CustomerType::CUSTOMER)->count();
        $newCustomersThisMonth = User::where('type', CustomerType::CUSTOMER)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $totalRevenue = Booking::whereHas('user', function ($q) {
            $q->where('type', CustomerType::CUSTOMER);
        })->where('status', BookingStatus::CONFIRMED)->sum('total_price');

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'newCustomersThisMonth',
            'totalRevenue'
        ));
    }

    /**
     * Display customer details with their bookings.
     */
    public function show(string $id): View
    {
        $customer = User::where('type', CustomerType::CUSTOMER)
            ->withCount('bookings')
            ->withSum('bookings', 'total_price')
            ->findOrFail($id);

        $bookings = Booking::where('user_id', $customer->id)
            ->with(['travelers', 'payments'])
            ->latest()
            ->paginate(15);

        // Booking statistics
        $stats = [
            'total_bookings' => $customer->bookings_count,
            'total_spend' => $customer->bookings_sum_total_price ?? 0,
            'confirmed_bookings' => Booking::where('user_id', $customer->id)->where('status', BookingStatus::CONFIRMED)->count(),
            'cancelled_bookings' => Booking::where('user_id', $customer->id)->where('status', BookingStatus::CANCELLED)->count(),
            'pending_bookings' => Booking::where('user_id', $customer->id)->whereIn('status', [BookingStatus::RESERVED, BookingStatus::PENDING_PAYMENT])->count(),
            'last_booking_date' => Booking::where('user_id', $customer->id)->latest()->value('created_at'),
        ];

        return view('admin.customers.show', compact('customer', 'bookings', 'stats'));
    }
}
