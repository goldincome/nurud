<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case STRIPE = 'stripe';
    case BANK_TRANSFER = 'bank_transfer';
    case SIMLESSPAY = 'simlesspay';
    case PAY_LATER = 'pay_later';
    case BOOK_ON_HOLD = 'book_on_hold';

    // Optional: Add a helper method for UI labels
    public function label(): string
    {
        return match ($this) {
            self::STRIPE => 'Stripe',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::SIMLESSPAY => 'SimlessPay',
            self::PAY_LATER => 'Buy Now, Pay Later',
            self::BOOK_ON_HOLD => 'Book on Hold',
        };
    }
}