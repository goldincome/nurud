<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\Admin\AdminController;
use App\Enums\CustomerType;
use App\Http\Controllers\Admin\AdminBankController;
use App\Http\Controllers\Admin\AdminGeneralSettingController;
use App\Http\Controllers\Admin\MarkupController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SubscriberController;

/*Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');
Route::get('/services', function () {
    return view('pages.services');
})->name('services');

Route::get('/booking', function () {
    return view('booking.booking');
});

Route::get('/result', function () {
    return view('search-result');
});

Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');

// API routes (no auth required)
Route::prefix('api')->group(function () {
    Route::get('/airports', [AirportController::class, 'search'])->name('api.search');
    Route::post('/offer/verify', [SearchController::class, 'verifyOffer'])->name('api.offer.verify');
});

// Search and booking routes
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/results', [SearchController::class, 'results'])->name('search.results');
Route::get('/search', function () {
    return redirect('/');
});

// Booking routes
Route::get('bookings/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::post('/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
Route::resource('bookings', BookingController::class);
Route::get('/manage-booking', [BookingController::class, 'manage'])->name('manage.booking');
Route::post('/manage-booking', [BookingController::class, 'manage'])->name('manage.booking.post');
Route::get('/bookings/{id}/ticket', [BookingController::class, 'downloadTicket'])->name('bookings.ticket.download');

Route::get('/dashboard', function () {
    if (in_array(auth()->user()->type, [CustomerType::ADMIN, CustomerType::SUPERADMIN])) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/financial', [AdminController::class, 'financial'])->name('financial');
    Route::get('/transactions', [AdminPaymentController::class, 'index'])->name('transactions.index');

    // Admin Account
    Route::get('/account', [AdminAccountController::class, 'edit'])->name('account.edit');
    Route::put('/account/profile', [AdminAccountController::class, 'updateProfile'])->name('account.update-profile');
    Route::put('/account/password', [AdminAccountController::class, 'updatePassword'])->name('account.update-password');
    Route::post('/bookings/{id}/approve-payment', [AdminController::class, 'approvePayment'])->name('bookings.approve-payment');
    Route::post('/bookings/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::resource('admins', AdminUserController::class);
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
    // REMOVED CONFLICTING ROUTE: Route::get('/bookings/{id}', [AdminController::class, 'bookingDetails'])->name('bookings.show');
    Route::resource('bookings', AdminBookingController::class);
    Route::resource('banks', AdminBankController::class);
    // General Settings
    Route::get('/settings/general', [AdminGeneralSettingController::class, 'edit'])->name('settings.general');
    Route::put('/settings/general', [AdminGeneralSettingController::class, 'update'])->name('settings.general.update');

    Route::resource('markups', MarkupController::class)->only([
        'index',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::get('/subscribers', [\App\Http\Controllers\Admin\AdminSubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers/send-email', [\App\Http\Controllers\Admin\AdminSubscriberController::class, 'sendEmail'])->name('subscribers.send-email');
    Route::delete('/subscribers/{id}', [\App\Http\Controllers\Admin\AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');

    // Super Admin Routes
    Route::middleware(['superadmin'])->group(function () {
        Route::resource('admins', AdminUserController::class);
    });
});

// Customer Routes
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [CustomerBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/ticket', [CustomerBookingController::class, 'downloadTicket'])->name('bookings.ticket');
    Route::get('/payments', [CustomerPaymentController::class, 'index'])->name('payments.index');
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [CustomerProfileController::class, 'changePasswordView'])->name('profile.change-password');
    Route::put('/change-password', [CustomerProfileController::class, 'updatePassword'])->name('profile.update-password');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/stripe/checkout', [StripePaymentController::class, 'checkout'])->name('stripe.checkout');
Route::post('/stripe/webhook', [StripePaymentController::class, 'webhook'])->name('stripe.webhook');

require __DIR__ . '/auth.php';