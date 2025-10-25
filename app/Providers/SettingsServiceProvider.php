<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class SettingsServiceProvider extends ServiceProvider
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
        // Only load settings in web context (not console/artisan)
        if (!$this->app->runningInConsole()) {
            $this->loadSettingsFromDatabase();
        }
    }
    
    /**
     * Load settings from database and override config values
     */
    private function loadSettingsFromDatabase()
    {
        try {
            // Check if settings table exists
            if (!\Schema::hasTable('core_settings')) {
                return;
            }
            
            // Get settings from database (force fresh data if cache is disabled)
            if (!is_cache_enabled()) {
                // If cache is disabled, get directly from database
                $settings = \App\Models\Setting::where('is_active', true)->get();
                $config = [];
                foreach ($settings as $setting) {
                    $config[$setting->key] = \App\Services\SettingsService::castValue($setting->value, $setting->type);
                }
            } else {
                // Use normal cached method
                $config = SettingsService::getConfig();
            }
            
            // Override config values with database settings
            foreach ($config as $key => $value) {
                $this->overrideConfigValue($key, $value);
            }
            
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::warning('Failed to load settings from database: ' . $e->getMessage());
        }
    }
    
    /**
     * Override specific config values based on setting key
     */
    private function overrideConfigValue(string $key, $value)
    {
        switch ($key) {
            // App settings
            case 'site_name':
                Config::set('app.name', $value);
                break;
                
            case 'site_timezone':
                Config::set('app.timezone', $value);
                break;
                
            case 'site_language':
                Config::set('app.locale', $value);
                break;
                
            // Mail settings
            case 'mail_from_address':
                Config::set('mail.from.address', $value);
                break;
                
            case 'mail_from_name':
                Config::set('mail.from.name', $value);
                break;
                
            case 'mail_host':
                Config::set('mail.mailers.smtp.host', $value);
                break;
                
            case 'mail_port':
                Config::set('mail.mailers.smtp.port', $value);
                break;
                
            case 'mail_username':
                Config::set('mail.mailers.smtp.username', $value);
                break;
                
            case 'mail_password':
                Config::set('mail.mailers.smtp.password', $value);
                break;
                
            case 'mail_encryption':
                Config::set('mail.mailers.smtp.encryption', $value);
                break;
                
            // Session settings
            case 'session_lifetime':
                Config::set('session.lifetime', (int) $value);
                break;
                
            // Cache settings
            case 'cache_driver':
                Config::set('cache.default', $value);
                break;
                
            // Security settings
            case 'password_min_length':
                Config::set('auth.password.min_length', (int) $value);
                break;
                
            case 'max_login_attempts':
                Config::set('auth.max_attempts', (int) $value);
                break;
                
            case 'lockout_duration':
                Config::set('auth.lockout_duration', (int) $value);
                break;
        }
    }
}
