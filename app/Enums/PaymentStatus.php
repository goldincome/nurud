<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case PAID = 'paid';
    case REFUNDED = 'refunded';


    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::PAID => 'Paid',
            self::REFUNDED => 'Refunded',
        };
    }
}
