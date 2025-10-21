<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            // Check if settings table exists
            if (!\Schema::hasTable('settings')) {
                return;
            }

            // Load mail configuration from database
            $mailConfig = [
                'default' => Setting::get('mail_mailer', config('mail.default')),
                'mailers' => [
                    'smtp' => [
                        'transport' => 'smtp',
                        'host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
                        'port' => Setting::get('mail_port', config('mail.mailers.smtp.port')),
                        'encryption' => Setting::get('mail_encryption', config('mail.mailers.smtp.encryption')),
                        'username' => Setting::get('mail_username', config('mail.mailers.smtp.username')),
                        'password' => Setting::get('mail_password', config('mail.mailers.smtp.password')),
                        'timeout' => null,
                    ],
                ],
                'from' => [
                    'address' => Setting::get('mail_from_address', config('mail.from.address')),
                    'name' => Setting::get('mail_from_name', config('mail.from.name')),
                ],
            ];

            config(['mail' => array_merge(config('mail'), $mailConfig)]);
            
        } catch (\Exception $e) {
            // If there's an error (e.g., during migration), use default config
            \Log::error('Mail config error: ' . $e->getMessage());
        }
    }
}