<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Jobs\SendBookingConfirmation;
use App\Jobs\SendPaymentConfirmationWithTicket;
use App\Models\Bank;
use App\Models\Booking;
use App\Models\Country;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\SimlessPayService;
use App\Services\SkyLinkApiService;
use App\Services\SkyLinkResponseMapper;
use App\Services\VerifiedPriceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected SkyLinkApiService $skyLinkService;
    protected SkyLinkResponseMapper $responseMapper;
    protected SimlessPayService $simlessPayService;
    protected VerifiedPriceService $verifiedPriceService;

    public function __construct(
        BookingService $bookingService,
        SkyLinkApiService $skyLinkService,
        SkyLinkResponseMapper $responseMapper,
        SimlessPayService $simlessPayService,
        VerifiedPriceService $verifiedPriceService
    ) {
        $this->bookingService = $bookingService;
        $this->skyLinkService = $skyLinkService;
        $this->responseMapper = $responseMapper;
        $this->simlessPayService = $simlessPayService;
        $this->verifiedPriceService = $verifiedPriceService;
    }

    public function create()
    {
        $verifyId = session()->get('current_verify_id');
        $verifyCache = Cache::get('verified_offer_' . $verifyId);

        if (!$verifyCache) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }

        $pricingData = $verifyCache['pricing'];
        $originalOffer = $verifyCache['originalOffer'];
        $searchData = $verifyCache['searchData'] ?? [];
        $markupFee = (int) session()->get('markup_fee');
        $verifiedPrice = $verifyCache['verifiedPrice'] ?? $this->verifiedPriceService->getVerifiedPrice($pricingData);
        $total = $verifyCache['total'] ?? ($verifiedPrice + $markupFee);
        $flightData = $verifyCache['flightData'] ?? $this->responseMapper->buildFlightDataForViews(
            $originalOffer,
            $pricingData,
            $searchData,
            $markupFee,
            $this->simlessPayService
        );

        $passengerCount = ($searchData['travelers']['numberOfAdults'] ?? 1)
            + ($searchData['travelers']['numberOfChildren'] ?? 0)
            + ($searchData['travelers']['numberOfInfants'] ?? 0);

        $countries = Country::orderBy('name')->get();

        /*
        $searchId = session()->get('current_search_id');
        $searchData = Cache::get('flight_search_' . $searchId)['search_data'] ?? [];

        $passengerCount = ($searchData['travelers']['numberOfAdults'] ?? 1)
            + ($searchData['travelers']['numberOfChildren'] ?? 0)
            + ($searchData['travelers']['numberOfInfants'] ?? 0);

        $countries = Country::orderBy('name')->get();

        $markupFee = (int) session()->get('markup_fee', 0);
        $verifiedPrice = $this->verifiedPriceService->getVerifiedPrice($pricingData);
        $total = $verifiedPrice + $markupFee;
       // $estimatedTax =  //round($verifiedPrice * 0.15) + $markupFee;

        $flightData = $this->responseMapper->buildFlightDataForViews(
            $originalOffer,
            $pricingData,
            $searchData,
            $markupFee,
            $this->simlessPayService
        );
        */
        //$addToCache = ['flightData' => $flightData];
        //Cache::put('flight_data' ,  $addToCache , now()->addMinutes(13));
        
        $groupTotalPrice = $this->verifiedPriceService->getGroupTotalPrice($flightData);
        $taxes = $total - $groupTotalPrice; //$flightData['verifiedPriceBreakdown']['taxesAndFees']  ?? 0;
        /*dd([
            'verifyCache' => $verifyCache,
            'flightData' => $flightData,
            'pricingData' => $pricingData,
            'travelerCount' => $passengerCount,
            'routeModel' => $searchData['routeModel'] ?? 0,
            'countries' => $countries,
            'total' => $total,
            'taxes' => $taxes, 
            'markupFee' => $markupFee,
            'groupTotalPrice' => $this->verifiedPriceService->getGroupTotalPrice($flightData),
            'poundsTotal' => $this->simlessPayService->convertNairaToPounds($total),
        ]);
        */
        return view('booking.booking', [
            'flightData' => $flightData,
            'pricingData' => $pricingData,
            'travelerCount' => $passengerCount,
            'routeModel' => $searchData['routeModel'] ?? 0,
            'countries' => $countries,
            'total' => $total,
            'taxes' => $taxes,
            'simlessPayService' => $this->simlessPayService,
            'groupTotalPrice' => $this->verifiedPriceService->getGroupTotalPrice($flightData),
        ]);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'passengers' => 'required|array|min:1|max:9',
            'passengers.*.firstName' => 'required|string|max:255',
            'passengers.*.surname' => 'required|string|max:255',
            'passengers.*.dob' => 'required|date',
            'passengers.*.gender' => 'required|in:1,2,3',
            'passengers.*.title' => 'nullable|string|in:Mr,Mrs,Ms,Miss,Dr,Prof',
        ]);

        $dobErrors = [];
        foreach ($validated['passengers'] as $key => $passenger) {
            $dob = \Carbon\Carbon::parse($passenger['dob']);
            $age = $dob->age;
            if (str_starts_with($key, 'adult') && $age < 18) {
                $dobErrors["passengers.{$key}.dob"] = 'Adult must be 18 years or older';
            } elseif (str_starts_with($key, 'child') && ($age < 2 || $age > 17)) {
                $dobErrors["passengers.{$key}.dob"] = 'Child must be between 2 and 17 years old';
            } elseif (str_starts_with($key, 'infant') && $age >= 2) {
                $dobErrors["passengers.{$key}.dob"] = 'Infant must be less than 2 years old';
            }
        }
        if (!empty($dobErrors)) {
            return back()->withErrors($dobErrors)->withInput();
        }

        $verifyId = session()->get('current_verify_id');
        $verifyCache = Cache::get('verified_offer_' . $verifyId);
    
        if (!$verifyCache) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }

        $pricingData = $verifyCache['pricing'];
        $flightData = $verifyCache['flightData'];
        $bookingToken = $pricingData['booking_token'] ?? '';

        if (!$bookingToken) {
            Log::error('SkyLink checkout: missing booking_token', ['pricingData' => $pricingData]);
            return redirect()->route('search.results')->with('error', 'Pricing data invalid. Please search again.');
        }

        $originalOffer = $verifyCache['originalOffer'];

        $searchId = session()->get('current_search_id');
        $searchData = Cache::get('flight_search_' . $searchId)['search_data'] ?? [];

        $phone = preg_replace('/[^0-9+]/', '', $request->phone ?? '');

        $payload = $this->responseMapper->buildReservePayload(
            $bookingToken,
            $pricingData,
            $request->all(),
            $searchData
        );

        $payload['flight_summary'] = $originalOffer;
        $payload['search_params'] = $searchData;
        $payload['fare_summary'] = $pricingData;
        $payload['flight_data'] = $flightData;
        $payload['totalPrice'] = $verifyCache['total'] ?? ($this->verifiedPriceService->getVerifiedPrice($pricingData) + session()->get('markup_fee', 0));

        $groupTotalPrice = $payload['group_total_price'] = $this->verifiedPriceService->getGroupTotalPrice($flightData);
        $bookingId = Str::uuid()->toString();
        Cache::put('booking_offer_' . $bookingId, $payload, now()->addMinutes(60));
        session()->put('offer_data_id', $bookingId);

        $banks = Bank::all();
        //dd($payload, $verifyCache, $pricingData);
        //$verifiedPrice = $verifyCache['verifiedPrice']; //this->verifiedPriceService->getVerifiedPrice($pricingData);
        $total = $verifyCache['total'];  //$verifiedPrice + session()->get('markup_fee', 0);
       
        $estimatedTax = $total - $groupTotalPrice;//$verifiedPrice * 0.15 + session()->get('markup_fee', 0);

        $flightData = $this->responseMapper->buildFlightDataForViews(
            $originalOffer,
            $pricingData,
            $searchData,
            (int) session()->get('markup_fee', 0),
            $this->simlessPayService
        );

        return view('booking.checkout', [
            'flightData' => $flightData,
            'pricingData' => $pricingData,
            'total' => $total,
            'taxes' => $estimatedTax,
            'simlessPayService' => $this->simlessPayService,
            'banks' => $banks,
            'paymentMethod' => PaymentMethod::class,
        ]);
    }

    public function store(Request $request)
    {
        $bookingId = session()->get('offer_data_id');
        $bookingPayload = Cache::get('booking_offer_' . $bookingId);

        if (!$bookingPayload) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }

        try {
            $deferredMethods = [
                PaymentMethod::PAY_LATER->value,
                PaymentMethod::BANK_TRANSFER->value,
                PaymentMethod::BOOK_ON_HOLD->value,
            ];

            if (in_array($request->booking_type, $deferredMethods)) {
                $flightSummary = $bookingPayload['flight_summary'] ?? [];
                $searchParams = $bookingPayload['search_params'] ?? [];

                $booking = $this->bookingService->createPendingBookingFromSkyLinkPayload(
                    $bookingPayload,
                    $flightSummary,
                    $searchParams
                );

                $method = match ($request->booking_type) {
                    PaymentMethod::PAY_LATER->value => PaymentMethod::PAY_LATER,
                    PaymentMethod::BANK_TRANSFER->value => PaymentMethod::BANK_TRANSFER,
                    PaymentMethod::BOOK_ON_HOLD->value => PaymentMethod::BOOK_ON_HOLD,
                    default => PaymentMethod::BANK_TRANSFER,
                };

                $booking->payments()->create([
                    'transaction_ref' => strtoupper($method->name) . '_' . strtoupper(uniqid()),
                    'amount' => $booking->total_price,
                    'currency' => $booking->currency,
                    'status' => PaymentStatus::PENDING,
                    'payment_method' => $method,
                ]);

                try {
                    if ($method === PaymentMethod::PAY_LATER) {
                        Mail::to($booking->customer_email)
                            ->send(new \App\Mail\BuyNowPayLaterEmail($booking));
                    } elseif ($method === PaymentMethod::BANK_TRANSFER) {
                        Mail::to($booking->customer_email)
                            ->send(new \App\Mail\BankTransferBookingEmail($booking));
                    } elseif ($method === PaymentMethod::BOOK_ON_HOLD) {
                        Mail::to($booking->customer_email)
                            ->send(new \App\Mail\BookOnHoldEmail($booking));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send deferred payment email', [
                        'booking_id' => $booking->id,
                        'method' => $method->name,
                        'error' => $e->getMessage(),
                    ]);
                }

                session()->put('booking_id', $booking->id);
                session()->forget(['markup_fee', 'offer_data_id', 'current_verify_id']);

                $successMessage = match ($method) {
                    PaymentMethod::PAY_LATER => 'Booking reserved successfully via BNPL Facility! Please check your email and contact us within 12 hours.',
                    PaymentMethod::BANK_TRANSFER => 'Booking reserved successfully! Please check your email and complete the bank transfer within 12 hours.',
                    PaymentMethod::BOOK_ON_HOLD => 'Booking successfully placed on hold! Please check your email and complete the transfer within 12 hours.',
                    default => 'Booking reserved successfully.',
                };

                return redirect()->route('bookings.confirmation')->with('success', $successMessage);
            }

            Log::warning('Unknown booking type in store', ['type' => $request->booking_type]);
            return redirect()->back()->with('error', 'Invalid booking type selected.');

        } catch (\Exception $e) {
            Log::error('Booking store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function confirmation()
    {
        $bookingId = session()->get('booking_id');
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if (request()->has('session_id')) {
            $payment = $booking->payments()->where('stripe_session_id', request()->session_id)->first();
            if ($payment && $payment->status === PaymentStatus::PENDING) {
                try {
                    Mail::to($booking->customer_email)
                        ->send(new \App\Mail\StripePaymentProcessingEmail($booking));
                } catch (\Exception $e) {
                    Log::error('Failed to send Stripe processing email', ['error' => $e->getMessage()]);
                }
            }
        }
        //dd($booking->load(['travelers', 'itineraries', 'travelerPricings']));
        return view('booking.confirmation', [
            'booking' => $booking->load(['travelers', 'itineraries', 'travelerPricings']),
            'simlessPayService' => $this->simlessPayService,
            'banks' => Bank::all(),
        ]);
    }

    public function show(string $id): View
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('booking.show', [
            'booking' => $booking->load(['travelers', 'payments']),
        ]);
    }

    public function payment(string $id): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($booking->status !== BookingStatus::PENDING_PAYMENT) {
            return redirect()->route('bookings.show', $booking->id)
                ->with('error', 'Payment not available for this booking status');
        }

        return view('booking.payment', [
            'booking' => $booking->load(['travelers', 'user']),
            'bankDetails' => app(PaymentService::class)->getBankTransferInstructions(),
        ]);
    }

    public function processPayment(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'payment_method' => 'required|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
        ]);

        if ($request->payment_method === PaymentMethod::STRIPE->value) {
            try {
                $result = app(PaymentService::class)->processStripePayment([
                    'amount' => $booking->total_price,
                    'currency' => $booking->currency,
                    'booking_id' => $booking->id,
                    'reservation_id' => $booking->reservation_id,
                ]);

                if ($result['status'] === 'success') {
                    $booking->payments()->create([
                        'transaction_ref' => $result['transaction_id'],
                        'amount' => $result['amount'],
                        'status' => PaymentStatus::COMPLETED,
                    ]);

                    try {
                        Mail::to($booking->guest_email)
                            ->send(new \App\Mail\PaymentConfirmedTicketIssued($booking));
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment confirmation email', [
                            'reservation_id' => $booking->reservation_id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    return redirect()->route('bookings.show', $booking->id)
                        ->with('success', 'Payment processed successfully!');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
            }
        } elseif ($request->payment_method === PaymentMethod::BANK_TRANSFER->value) {
            $booking->payments()->create([
                'transaction_ref' => 'BANK_' . uniqid(),
                'amount' => $booking->total_amount,
                'status' => PaymentStatus::PENDING,
            ]);

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Bank transfer payment initiated. Please complete the transfer to confirm your booking.');
        }

        return back()->with('error', 'Payment method not supported');
    }

    public function downloadTicket(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $booking = Booking::findOrFail($id);

        $user = Auth::user();
        $isAdmin = $user && in_array($user->type, [
            \App\Enums\CustomerType::ADMIN,
            \App\Enums\CustomerType::SUPERADMIN,
        ]);

        if ($booking->user_id) {
            if (!$user || ($booking->user_id !== $user->id && !$isAdmin)) {
                abort(403, 'Unauthorized');
            }
        }

        if (!$isAdmin && in_array($booking->status, [BookingStatus::CANCELLED, BookingStatus::EXPIRED])) {
            abort(403, 'Ticket not available for this booking status');
        }

        $booking->load(['travelers', 'payments']);

        $pdf = Pdf::loadView('booking.ticket', compact('booking'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'ticket_' . ($booking->pnr ?: $booking->reservation_id) . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadInvoice(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $booking = Booking::findOrFail($id);

        $user = Auth::user();
        $isAdmin = $user && in_array($user->type, [
            \App\Enums\CustomerType::ADMIN,
            \App\Enums\CustomerType::SUPERADMIN,
        ]);

        if ($booking->user_id) {
            if (!$user || ($booking->user_id !== $user->id && !$isAdmin)) {
                abort(403, 'Unauthorized');
            }
        }

        $booking->load(['travelers', 'itineraries', 'payments', 'travelerPricings', 'priceInPounds']);

        $banks = \App\Models\Bank::with('country')->get();

        $pdf = Pdf::loadView('booking.invoice', compact('booking', 'banks'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'invoice_' . ($booking->pnr ?: $booking->reservation_id) . '.pdf';

        return $pdf->download($filename);
    }
}
