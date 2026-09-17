<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightRoute extends Model
{
    /**
     * Airports are referenced by IATA code strings only. All human-readable
     * airport details (city, name, country) are resolved at render time from
     * database/data/airports.json via App\Services\AirportService.
     */
    protected $fillable = [
        'origin_code',
        'destination_code',
        'slug',
        'distance_miles',
        'avg_duration_minutes',
        'has_direct_flights',
        'lowest_fare_gbp',
        'primary_airline',
        'is_indexable',
        'search_intent_metadata',
    ];

    protected $casts = [
        'search_intent_metadata' => 'array',
        'has_direct_flights' => 'boolean',
        'is_indexable' => 'boolean',
    ];

    public function getOriginCodeAttribute(string $value): string
    {
        return strtoupper($value);
    }

    public function getDestinationCodeAttribute(string $value): string
    {
        return strtoupper($value);
    }

    public function liveFares(): HasMany
    {
        return $this->hasMany(FlightRouteLiveFare::class, 'flight_route_id');
    }
}