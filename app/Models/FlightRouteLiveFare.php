<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightRouteLiveFare extends Model
{
    protected $table = 'flight_route_live_fares';

    protected $fillable = [
        'flight_route_id',
        'airline',
        'airline_code',
        'price',
        'currency',
        'stops',
        'duration_minutes',
        'departure_code',
        'departure_time',
        'departure_date',
        'arrival_code',
        'arrival_time',
        'arrival_date',
        'all_offer',
        'payload',
        'searched_for_departure_date',
        'searched_for_return_date',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'float',
        'stops' => 'integer',
        'duration_minutes' => 'integer',
        'departure_date' => 'date:Y-m-d',
        'arrival_date' => 'date:Y-m-d',
        'all_offer' => 'array',
        'payload' => 'array',
        'searched_for_departure_date' => 'date:Y-m-d',
        'searched_for_return_date' => 'date:Y-m-d',
        'sort_order' => 'integer',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(FlightRoute::class, 'flight_route_id');
    }
}