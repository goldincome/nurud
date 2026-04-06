<?php

namespace App\Enums;

enum RouteMode: int
{
    case OneWay = 0;
    case RoundTrip = 1;
    case MultiCity = 2;

    /**
     * Get a human-readable label for the UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::OneWay => 'One Way',
            self::RoundTrip => 'Round Trip',
            self::MultiCity => 'Multi City',
        };
    }
}