<?php

namespace App\Enums;

enum Gender: int
{
    case Unspecified = 1;
    case Female = 2;
    case Male = 3;

    /**
     * Get a human-readable label for the UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Unspecified => 'Unspecified',
            self::Female => 'Female',
            self::Male => 'Male',
        };
    }

    /**
     * Get the title/prefix associated with the gender.
     */
    public function title(): string
    {
        return match ($this) {
            self::Female => 'Ms.',
            self::Male => 'Mr.',
            self::Unspecified => '',
        };
    }
}