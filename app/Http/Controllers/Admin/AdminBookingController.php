<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Services\SkyLinkApiService;
use App\Services\BookingService;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('user', 'priceInPounds');

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

    public function show(Booking $booking)
    {
        $booking->load(['travelers', 'payments', 'itineraries', 'travelerPricings', 'priceInPounds']);
        $bookingStatus = BookingStatus::class;
        $paymentMethod = $booking->payments()->first()->payment_method->value ?? null;
        return view('admin.bookings.show', compact('booking', 'bookingStatus', 'paymentMethod'));
    }

    public function update(UpdateBookingStatusRequest $request, Booking $booking)
    {
        if ($request->status === BookingStatus::CONFIRMED->value) {
            $offerData = $booking->offer_data ?? [];

            if (!$offerData) {
                return back()->with('error', 'SkyLink payload missing. Cannot confirm booking without PNR generation data.');
            }

            $skylinkPayload = [
                'booking_token' => $offerData['booking_token'] ?? null,
                'passengers' => $offerData['passengers'] ?? [],
                'travellers' => $offerData['travellers'] ?? [],
                'ticket_time_limit_hours' => $offerData['ticket_time_limit_hours'] ?? 48,
            ];

            if (!$skylinkPayload['booking_token'] || empty($skylinkPayload['travellers'])) {
                return back()->with('error', 'Incomplete SkyLink payload. Missing booking_token or travellers.');
            }

            // Fill missing traveler fields (airline NDC requires passport info, email, phone, country_code)
            $passportDefaults = [
                'passport_number' => 'A12345678',
                'passport_expiry' => '2030-01-01',
                'passport_issue_date' => '2020-01-01',
                'nationality' => 'NG',
                'email' => $booking->customer_email ?? '',
                'phone' => preg_replace('/[^0-9]/', '', $booking->contact_phone ?? ''),
                'country_code' => '234',
            ];
            foreach ($skylinkPayload['travellers']['travelers'] ?? [] as $key => $t) {
                $skylinkPayload['travellers']['travelers'][$key] = array_merge($passportDefaults, $t);
                try {
                    $skylinkPayload['travellers']['travelers'][$key]['dob'] = \Carbon\Carbon::parse($t['dob'] ?? '')->format('Y-m-d');
                } catch (\Exception $e) {
                    $skylinkPayload['travellers']['travelers'][$key]['dob'] = '1990-06-10';
                }
            }
            if (!empty($skylinkPayload['travellers']['primary_guest'])) {
                $skylinkPayload['travellers']['primary_guest'] = array_merge($passportDefaults, $skylinkPayload['travellers']['primary_guest']);
                try {
                    $skylinkPayload['travellers']['primary_guest']['dob'] = \Carbon\Carbon::parse($skylinkPayload['travellers']['primary_guest']['dob'] ?? '')->format('Y-m-d');
                } catch (\Exception $e) {
                    $skylinkPayload['travellers']['primary_guest']['dob'] = '1990-06-10';
                }
            }

            // Auto-correct swapped DOBs: adults should have older DOBs than children/infants
            $travelers = $skylinkPayload['travellers']['travelers'] ?? [];
            $adultKeys = []; $childKeys = []; $infantKeys = [];
            foreach ($travelers as $key => $t) {
                if (str_starts_with($key, 'adult')) $adultKeys[] = $key;
                elseif (str_starts_with($key, 'child')) $childKeys[] = $key;
                else $infantKeys[] = $key;
            }
            $allDobs = array_values(array_map(fn($k) => $travelers[$k]['dob'] ?? '1990-06-10', array_keys($travelers)));
            sort($allDobs);
            $reindex = array_merge($adultKeys, $childKeys, $infantKeys);
            foreach ($reindex as $i => $key) {
                $skylinkPayload['travellers']['travelers'][$key]['dob'] = $allDobs[$i] ?? '1990-06-10';
            }
            // Sync primary_guest DOB with first adult traveler
            if (!empty($adultKeys) && !empty($skylinkPayload['travellers']['primary_guest'])) {
                $firstAdultKey = $adultKeys[0];
                $skylinkPayload['travellers']['primary_guest']['dob'] = $skylinkPayload['travellers']['travelers'][$firstAdultKey]['dob'] ?? '1990-06-10';
            }

            try {
                $result = app(SkyLinkApiService::class)->reserveFlight($skylinkPayload);

                $pnr = $result['data']['pnr'] ?? null;
                if (!$pnr) {
                    throw new \Exception('SkyLink reserve did not return a PNR');
                }

                $booking->update([
                    'status' => BookingStatus::CONFIRMED,
                    'pnr' => $pnr,
                    'ticket_issued_at' => now(),
                ]);

                $payment = $booking->payments()
                    ->whereIn('status', [PaymentStatus::PENDING])
                    ->first();

                if ($payment) {
                    $payment->update(['status' => PaymentStatus::COMPLETED]);
                }

                dispatch(new \App\Jobs\SendPaymentConfirmationWithTicket($booking));

                Log::info('Admin confirmed booking with SkyLink PNR', [
                    'booking_id' => $booking->id,
                    'pnr' => $pnr,
                ]);
            } catch (\Exception $e) {
                Log::error('Admin confirm + SkyLink reserve failed', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
                return back()->with('error', 'Failed to confirm booking: ' . $e->getMessage());
            }
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
}
