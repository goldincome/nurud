<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AirportController;

/*Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/booking', function () {
    return view('booking');
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
Route::get('/search', function () { return redirect('/'); });

// Booking routes
Route::resource('bookings', BookingController::class)->only(['create','index','store', 'show']);
Route::get('/manage-booking', [BookingController::class, 'manage'])->name('manage.booking');
Route::post('/manage-booking', [BookingController::class, 'manage'])->name('manage.booking.post');
Route::get('/bookings/{id}/ticket', [BookingController::class, 'downloadTicket'])->name('bookings.ticket.download');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
