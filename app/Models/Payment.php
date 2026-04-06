<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'transaction_ref',
        'amount',
        'currency',
        'status',
        'payment_method',
        'stripe_session_id',
        'gateway_response',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
