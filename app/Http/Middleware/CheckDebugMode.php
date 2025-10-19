<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckDebugMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Default values
        $debugMode = false;
        $debugBar = false;
        
        try {
            // Check if database is available
            if (\DB::connection()->getPdo()) {
                // Check debug mode setting
                $debugMode = Setting::get('debug_mode', false);
                $debugBar = Setting::get('debug_bar', false);
            }
        } catch (\Exception $e) {
            // If there's any error accessing settings, use default values
            \Log::warning('Debug mode check failed: ' . $e->getMessage());
            $debugMode = false;
            $debugBar = false;
        }
        
        // Set debug mode in config
        if ($debugMode) {
            config(['app.debug' => true]);
            config(['app.log_level' => 'debug']);
        } else {
            config(['app.debug' => false]);
            config(['app.log_level' => 'error']);
        }
        
        // Set debug bar configuration
        if ($debugBar && $debugMode) {
            config(['debugbar.enabled' => true]);
        } else {
            config(['debugbar.enabled' => false]);
        }
        
        // Set environment variables for the current request
        if ($debugMode) {
            putenv('APP_DEBUG=true');
            $_ENV['APP_DEBUG'] = 'true';
        } else {
            putenv('APP_DEBUG=false');
            $_ENV['APP_DEBUG'] = 'false';
        }
        
        if ($debugBar && $debugMode) {
            putenv('DEBUGBAR_ENABLED=true');
            $_ENV['DEBUGBAR_ENABLED'] = 'true';
        } else {
            putenv('DEBUGBAR_ENABLED=false');
            $_ENV['DEBUGBAR_ENABLED'] = 'false';
        }

        return $next($request);
    }
}