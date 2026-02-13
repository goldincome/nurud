<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'iso_alpha2',
        'iso_alpha3',
        'numeric_code',
        'dialing_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'numeric_code' => 'integer',
        ];
    }

    /**
     * Scope: Find by ISO Alpha-2 (e.g., 'US', 'GB')
     */
    public function scopeByCode($query, string $code)
    {
        return $query->where('iso_alpha2', strtoupper($code));
    }

    /**
     * Helper: Get formatted display name with code
     * Example: "United States (+1)"
     */
    public function getFullDialingCodeAttribute(): string
    {
        return "{$this->name} ({$this->dialing_code})";
    }
}