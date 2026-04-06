<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case STRIPE = 'stripe';
    case BANK_TRANSFER = 'bank_transfer';
    case SIMLESSPAY = 'simlesspay';

    // Optional: Add a helper method for UI labels
    public function label(): string
    {
        return match ($this) {
            self::STRIPE => 'Stripe',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::SIMLESSPAY => 'SimlessPay',
        };
    }
}