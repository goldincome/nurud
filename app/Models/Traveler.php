<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traveler extends Model
{
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'passport_number',
        'passport_expiry',
        'type',
    ];

    protected $casts = [
        'dob' => 'date',
        'passport_expiry' => 'date',
        'passport_number' => 'encrypted',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
