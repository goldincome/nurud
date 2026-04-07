<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.unique' => 'You are already subscribed to our alerts.',
        ]);

        Subscriber::create([
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to price alerts!',
        ]);
    }
}
