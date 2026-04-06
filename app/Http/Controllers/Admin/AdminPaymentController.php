<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;

class AdminPaymentController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['booking', 'booking.user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_ref', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($bq) use ($search) {
                      $bq->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('customer_first_name', 'like', "%{$search}%")
                        ->orWhere('customer_last_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by method
        if ($request->filled('method')) {
            $query->where('payment_method', $request->input('method'));
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'statuses' => PaymentStatus::cases(),
            'methods' => PaymentMethod::cases(),
        ]);
    }
}
