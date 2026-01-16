<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'pnr_reference',
        'flight_offer_id',
        'guest_email',
        'guest_phone',
        'total_amount',
        'currency',
        'status',
        'payment_method',
        'expires_at',
        'ticket_issued_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'ticket_issued_at' => 'datetime',
        'total_amount' => 'decimal:2',
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
}
