<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceInPounds extends Model
{
    protected $table = 'price_in_pounds';

    protected $fillable = [
        'booking_id',
        'currency',
        'price',
        'tax',
        'markup',
        'total_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'markup' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
