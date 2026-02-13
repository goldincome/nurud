<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Traveler extends Model
{
    use HasUuids;

    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'base_price',
        'taxes_and_fees',
        'total_price',
        'gender',
        'email',
        'phone',
        'country_calling_code',
        'date_of_birth',
        'traveler_id',
        'date_created',
        'date_modified',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
        'base_price' => 'integer',
        'taxes_and_fees' => 'integer',
        'total_price' => 'integer',
        'gender' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
