<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('general_settings')) {
                $settings = \App\Models\GeneralSetting::first();
                $contactEmail = (!empty($settings->contact_email)) ? $settings->contact_email : 'info@nurud.com';
                $companyName = (!empty($settings->company_name)) ? $settings->company_name : 'Nurud Travel';

                config([
                    'mail.from.address' => $contactEmail,
                    'mail.from.name' => $companyName,
                ]);
            }
        } catch (\Exception $e) {
            config([
                'mail.from.address' => 'info@nurud.com',
                'mail.from.name' => 'Nurud Travels',
            ]);
        }
    }
}
