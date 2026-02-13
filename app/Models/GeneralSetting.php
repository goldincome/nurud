<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'emails',
        'phone_number',
        'support_email',
        'contact_email',
    ];

    protected $casts = [
        'emails' => 'array', // If user enters comma separated, we can handle it or just text. Let's assume text for now but 'emails' implies plural.
        // Actually, let's keep it simple string for 'emails' (e.g. "info@nurud.com, sales@nurud.com") and let user format it.
        // Or JSON if we want structured. The migration is 'text'.
    ];
}
