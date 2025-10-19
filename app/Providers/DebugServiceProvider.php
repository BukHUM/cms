<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class DebugServiceProvider extends ServiceProvider
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
        // Set debug configuration based on settings
        $this->configureDebugMode();
        $this->configureDebugBar();
    }

    /**
     * Configure debug mode
     */
    private function configureDebugMode(): void
    {
        try {
            $debugMode = setting('debug_mode', false);
            
            if ($debugMode) {
                Config::set('app.debug', true);
                Config::set('app.log_level', 'debug');
                Config::set('logging.channels.stack.level', 'debug');
            } else {
                Config::set('app.debug', false);
                Config::set('app.log_level', 'error');
                Config::set('logging.channels.stack.level', 'error');
            }
        } catch (\Exception $e) {
            // Fallback to default if settings are not available
            Config::set('app.debug', env('APP_DEBUG', false));
        }
    }

    /**
     * Configure debug bar
     */
    private function configureDebugBar(): void
    {
        try {
            $debugMode = setting('debug_mode', false);
            $debugBar = setting('debug_bar', false);
            
            if ($debugBar && $debugMode) {
                Config::set('debugbar.enabled', true);
                Config::set('debugbar.storage.enabled', true);
            } else {
                Config::set('debugbar.enabled', false);
                Config::set('debugbar.storage.enabled', false);
            }
        } catch (\Exception $e) {
            // Fallback to default if settings are not available
            Config::set('debugbar.enabled', env('DEBUGBAR_ENABLED', false));
        }
    }
}