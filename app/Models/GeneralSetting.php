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
        'admin_email',
        'notify_admin_new_reservation',
        'notify_admin_stripe_no_ticket',
        'notify_admin_247_api_down',
        'notify_admin_simlesspay_api_down',
    ];

    protected $casts = [
        'emails' => 'array',
        'notify_admin_new_reservation' => 'boolean',
        'notify_admin_stripe_no_ticket' => 'boolean',
        'notify_admin_247_api_down' => 'boolean',
        'notify_admin_simlesspay_api_down' => 'boolean',
    ];

    /**
     * Get the admin email, falling back to info@nurud.com.
     */
    public function getAdminNotificationEmail(): string
    {
        return !empty($this->admin_email) ? $this->admin_email : 'info@nurud.com';
    }
}
