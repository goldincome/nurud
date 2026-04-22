<?php

namespace App\Services;

use App\Models\GeneralSetting;
use App\Mail\AdminNewReservation;
use App\Mail\AdminStripeNoTicket;
use App\Mail\Admin247ApiDown;
use App\Mail\AdminSimlessPayApiDown;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdminNotificationService
{
    /**
     * Notify admin of a new reservation.
     */
    public static function notifyNewReservation($booking): void
    {
        try {
            $settings = GeneralSetting::first();
            if (!$settings || !$settings->notify_admin_new_reservation) return;

            $adminEmail = $settings->getAdminNotificationEmail();
            Mail::to($adminEmail)->send(new AdminNewReservation($booking));

            Log::info('Admin notified of new reservation', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send admin new reservation email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notify admin that Stripe payment was confirmed but ticket was not issued.
     */
    public static function notifyStripeNoTicket($booking, string $error = ''): void
    {
        try {
            $settings = GeneralSetting::first();
            if (!$settings || !$settings->notify_admin_stripe_no_ticket) return;

            $adminEmail = $settings->getAdminNotificationEmail();
            Mail::to($adminEmail)->send(new AdminStripeNoTicket($booking, $error));

            Log::info('Admin notified of Stripe payment without ticket', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send admin stripe-no-ticket email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notify admin that 247 Travels API is down.
     * Uses cache to avoid spamming (max once per 15 minutes).
     */
    public static function notify247ApiDown(string $error = '', string $endpoint = ''): void
    {
        try {
            $cacheKey = 'admin_notified_247_api_down';
            if (Cache::has($cacheKey)) return;

            $settings = GeneralSetting::first();
            if (!$settings || !$settings->notify_admin_247_api_down) return;

            $adminEmail = $settings->getAdminNotificationEmail();
            Mail::to($adminEmail)->send(new Admin247ApiDown($error, $endpoint));

            Cache::put($cacheKey, true, now()->addMinutes(15));

            Log::info('Admin notified of 247 API downtime');
        } catch (\Exception $e) {
            Log::error('Failed to send admin 247-api-down email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notify admin that SimlessPay API is down.
     * Uses cache to avoid spamming (max once per 15 minutes).
     */
    public static function notifySimlessPayApiDown(string $error = '', string $endpoint = ''): void
    {
        try {
            $cacheKey = 'admin_notified_simlesspay_api_down';
            if (Cache::has($cacheKey)) return;

            $settings = GeneralSetting::first();
            if (!$settings || !$settings->notify_admin_simlesspay_api_down) return;

            $adminEmail = $settings->getAdminNotificationEmail();
            Mail::to($adminEmail)->send(new AdminSimlessPayApiDown($error, $endpoint));

            Cache::put($cacheKey, true, now()->addMinutes(15));

            Log::info('Admin notified of SimlessPay API downtime');
        } catch (\Exception $e) {
            Log::error('Failed to send admin simlesspay-api-down email', ['error' => $e->getMessage()]);
        }
    }
}
