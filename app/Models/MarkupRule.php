<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarkupRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operator',
        'threshold_price',
        'markup_type',
        'markup_value',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'threshold_price' => 'float',
        'markup_value' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}