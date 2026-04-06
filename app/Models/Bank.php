<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_number',
        'swift_code',
        'iban',
        'routing_number',
        'bank_name',
        'instructions',
        'deadline',
        'country_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
