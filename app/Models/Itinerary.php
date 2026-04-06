<?php

namespace App\Models;

use App\Enums\TravelerType;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    use HasUuids;

    protected $fillable = [
        'booking_id',
        'itinerary_title',
        'itinerary_summary',
        'itinerary_index',
        'duration',
        'duration_in_minutes',
        'segments',
    ];

    protected $casts = [
        'segments' => 'array',
        'itinerary_index' => 'integer',
        'duration_in_minutes' => 'integer',
        'traveler_type' => TravelerType::class,
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
