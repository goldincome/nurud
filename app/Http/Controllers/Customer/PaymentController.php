<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::whereHas('booking', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('booking')
            ->latest()
            ->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }
}
