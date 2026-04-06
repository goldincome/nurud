<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case RESERVED = 'reserved';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case PROCESSING = 'processing';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Pending Payment',
            self::RESERVED => 'Reserved',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
            self::EXPIRED => 'Expired',
            self::PROCESSING => 'Processing',
        };
    }
}
