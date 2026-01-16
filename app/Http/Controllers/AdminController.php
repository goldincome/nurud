<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Admin dashboard
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';

        // Get stats based on user role
        $stats = $this->getStatsForRole($role);

        // Get recent bookings
        $recentBookings = Booking::with(['user', 'travelers', 'payments'])
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'role'));
    }

    /**
     * Financial admin panel for payment management
     */
    public function financial(): View
    {
        // Only financial admins and super admins
        if (!in_array(Auth::user()->role ?? 'user', ['financial', 'admin', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }

        $pendingPayments = Payment::with(['booking.travelers'])
            ->where('status', 'pending')
            ->whereHas('booking', function($query) {
                $query->where('payment_method', 'bank_transfer');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $recentCompletions = Payment::with(['booking.travelers'])
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.financial', compact('pendingPayments', 'recentCompletions'));
    }

    /**
     * Approve bank transfer payment
     */
    public function approvePayment(Request $request, string $bookingId): RedirectResponse
    {
        if (!in_array(Auth::user()->role ?? 'user', ['financial', 'admin', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }

        $booking = Booking::findOrFail($bookingId);

        if ($booking->status !== 'pending_payment') {
            return back()->with('error', 'Booking is not in pending payment status');
        }

        // Approve the bank transfer
        $success = $this->paymentService->verifyBankTransfer($bookingId, Auth::id());

        if ($success) {
            return back()->with('success', 'Payment approved successfully. Booking confirmed and ticket issued.');
        }

        return back()->with('error', 'Failed to approve payment');
    }

    /**
     * Cancel booking (admin action)
     */
    public function cancelBooking(Request $request, string $bookingId): RedirectResponse
    {
        $user = Auth::user();
        if (!in_array($user->role ?? 'user', ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access');
        }

        $booking = Booking::findOrFail($bookingId);

        // Only allow cancellation of pending bookings
        if (!in_array($booking->status, ['reserved', 'pending_payment'])) {
            return back()->with('error', 'Booking cannot be cancelled at this status');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => $user->id,
        ]);

        // Handle any refunds if necessary (placeholder for future implementation)
        // TODO: Implement refund logic based on payment method

        return back()->with('success', 'Booking cancelled successfully');
    }

    /**
     * User management (super admin only)
     */
    public function users(): View
    {
        if ((Auth::user()->role ?? 'user') !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        $users = User::withCount(['bookings'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.users', compact('users'));
    }

    /**
     * Update user role (super admin only)
     */
    public function updateUserRole(Request $request, User $user): JsonResponse
    {
        if ((Auth::user()->role ?? 'user') !== 'super_admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot change own role'], 400);
        }

        $request->validate([
            'role' => 'required|in:user,sales,financial,admin,super_admin'
        ]);

        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully'
        ]);
    }

    /**
     * Get booking details for admin
     */
    public function bookingDetails(string $id): View
    {
        $booking = Booking::with(['user', 'travelers', 'payments'])->findOrFail($id);

        // Check permissions based on role
        $user = Auth::user();
        $userRole = $user->role ?? 'user';

        if ($userRole === 'user' && $booking->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        if ($userRole === 'sales' && $booking->status === 'confirmed') {
            abort(403, 'Sales agents can only view pending bookings');
        }

        return view('admin.booking-details', compact('booking'));
    }

    /**
     * Generate admin statistics
     */
    private function getStatsForRole(string $role): array
    {
        $stats = [
            'total_bookings' => 0,
            'pending_payments' => 0,
            'confirmed_bookings' => 0,
            'total_revenue' => 0,
        ];

        // Super admin and admin see everything
        if (in_array($role, ['admin', 'super_admin'])) {
            $stats = [
                'total_bookings' => Booking::count(),
                'pending_payments' => Booking::where('status', 'pending_payment')->count(),
                'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
                'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            ];
        }
        // Financial admin sees payment-related stats
        elseif ($role === 'financial') {
            $stats = [
                'total_bookings' => Booking::whereHas('payments')->count(),
                'pending_payments' => Booking::where('status', 'pending_payment')->count(),
                'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
                'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            ];
        }
        // Sales agents see booking creation stats
        elseif ($role === 'sales') {
            $stats = [
                'total_bookings' => Booking::count(),
                'pending_payments' => Booking::where('status', 'pending_payment')->count(),
                'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            ];
        }

        return $stats;
    }
}
