<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\RouteMode;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasUuids;

    protected $fillable = [
        'reference_number',
        'user_id',
        'office_id',
        'ota_id',
        'flight_offer_id',
        'origin_location',
        'origin_destination',
        'carrier_code',
        'route_model',
        'departure_date',
        'cabin',
        'class',
        'ama_client_ref',
        'reservation_id',
        'pnr',
        'base_price',
        'taxes_and_fees',
        'total_price',
        'markup_fee',
        'contact_phone',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'reservation_date',
        'offer_data',
        'order_status',
        'date_created',
        'date_modified',
        'currency',
        'status',
        'payment_status',
        'payment_method',
        'expires_at',
        'ticket_issued_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->reference_number)) {
                $booking->reference_number = date('dmY') . '-' . mt_rand(10000, 99999);
            }
        });
    }

    protected $casts = [
        'departure_date' => 'datetime',
        'reservation_date' => 'datetime',
        'offer_data' => 'array',
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
        'expires_at' => 'datetime',
        'ticket_issued_at' => 'datetime',
        'base_price' => 'integer',
        'taxes_and_fees' => 'integer',
        'total_price' => 'integer',
        'markup_fee' => 'integer',
        'route_model' => 'integer',
        'order_status' => 'integer',
        'route_mode' => RouteMode::class,
        'status' => BookingStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function travelers(): HasMany
    {
        return $this->hasMany(Traveler::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function travelerPricings(): HasMany
    {
        return $this->hasMany(TravelerPricing::class);
    }

    public function priceInPounds(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PriceInPounds::class);
    }
}
