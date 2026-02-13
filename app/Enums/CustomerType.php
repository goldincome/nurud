<?php

namespace App\Enums;

enum CustomerType: string
{
    case CUSTOMER = 'customer';
    case PARTNER = 'partner';
    case ADMIN = 'admin';
    case SUPERADMIN = 'superadmin';

    // Optional: Add a helper method for UI labels
    public function label(): string
    {
        return match ($this) {
            self::CUSTOMER => 'Standard Customer',
            self::PARTNER => 'Business Partner',
            self::ADMIN => 'Administrator',
            self::SUPERADMIN => 'Super Administrator',
        };
    }
}