<?php

namespace App\Enums;

enum TravelerType: string
{
    case ADULT = 'ADULT';
    case CHILD = 'CHILD';
    case HELD_INFANT = 'HELD_INFANT';

    /**
     * Get the human-readable label for the UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADULT => 'Adult',
            self::CHILD => 'Child',
            self::HELD_INFANT => 'Infant',
        };
    }
}