<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelerPricing extends Model
{
    use HasUuids;

    protected $fillable = [
        'booking_id',
        'traveler_id',
        'fare_option',
        'traveler_type',
        'price',
        'price_breakdown',
        'fare_details_by_segment',
    ];

    protected $casts = [
        'price' => 'array',
        'price_breakdown' => 'array',
        'fare_details_by_segment' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
