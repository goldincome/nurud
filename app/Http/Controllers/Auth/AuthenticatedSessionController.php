<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // If the user came from a booking/search/checkout page, store that URL
        // so we can redirect them back after login
        $previousUrl = url()->previous();
        $bookingPaths = ['booking', 'checkout', 'search', 'bookings'];

        foreach ($bookingPaths as $path) {
            if (str_contains($previousUrl, $path)) {
                session()->put('url.intended', $previousUrl);
                break;
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if user was in a booking flow and redirect back there
        $intendedUrl = session()->pull('url.intended');

        if ($intendedUrl) {
            return redirect()->to($intendedUrl);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
