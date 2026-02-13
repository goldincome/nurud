<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AdminController;
use App\Enums\CustomerType;
use App\Http\Controllers\Admin\AdminBankController;
use App\Http\Controllers\Admin\AdminGeneralSettingController;
use App\Http\Controllers\Admin\MarkupController;

/*Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/booking', function () {
    return view('booking.booking');
});

Route::get('/result', function () {
    return view('search-result');
});

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
Route::resource('bookings', BookingController::class);
Route::post('/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
Route::get('/manage-booking', [BookingController::class, 'manage'])->name('manage.booking');
Route::post('/manage-booking', [BookingController::class, 'manage'])->name('manage.booking.post');
Route::get('/bookings/{id}/ticket', [BookingController::class, 'downloadTicket'])->name('bookings.ticket.download');

Route::get('/dashboard', function () {
    if (in_array(auth()->user()->type, [CustomerType::ADMIN, CustomerType::SUPERADMIN])) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/financial', [AdminController::class, 'financial'])->name('financial');
    Route::post('/bookings/{id}/approve-payment', [AdminController::class, 'approvePayment'])->name('bookings.approve-payment');
    Route::post('/bookings/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
    Route::get('/bookings/{id}', [AdminController::class, 'bookingDetails'])->name('bookings.show');
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
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
