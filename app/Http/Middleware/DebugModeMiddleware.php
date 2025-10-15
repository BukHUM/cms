<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;
use Symfony\Component\HttpFoundation\Response;

class DebugModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Override debug mode from settings
        $this->overrideDebugMode();
        
        return $next($request);
    }

    /**
     * Override debug mode setting from database
     */
    private function overrideDebugMode(): void
    {
        try {
            // Get debug level from settings
            $debugLevel = SettingsHelper::get('debug_level', 'standard');
            $debugMode = SettingsHelper::get('debug_mode');
            $debugBar = SettingsHelper::get('debug_bar', false);
            
            // Determine debug mode based on debug level
            $shouldEnableDebug = $debugLevel !== 'off';
            
            // Override the config value
            config(['app.debug' => $shouldEnableDebug]);
            
            // Set debug level in config for other components to use
            config(['app.debug_level' => $debugLevel]);
            
            // Configure Debug Bar based on settings
            $this->configureDebugBar($debugLevel, $debugBar);
            
            // Also override the environment variable for this request
            $_ENV['APP_DEBUG'] = $shouldEnableDebug ? 'true' : 'false';
            putenv('APP_DEBUG=' . ($shouldEnableDebug ? 'true' : 'false'));
            
        } catch (\Exception $e) {
            // If SettingsHelper fails, keep the original .env value
            // This ensures the app doesn't break if database is unavailable
        }
    }

    /**
     * Configure Debug Bar based on debug level and settings
     */
    private function configureDebugBar(string $debugLevel, bool $debugBar): void
    {
        // Enable Debug Bar if debug_bar setting is true and debug level is not 'off'
        $shouldEnableDebugBar = $debugBar && $debugLevel !== 'off';
        
        config(['debugbar.enabled' => $shouldEnableDebugBar]);
        config(['debugbar.inject' => $shouldEnableDebugBar]);
        
        if ($shouldEnableDebugBar) {
            // Configure collectors based on debug level
            $collectors = $this->getCollectorsForLevel($debugLevel);
            config(['debugbar.collectors' => $collectors]);
            
            // Configure options based on debug level
            $options = $this->getOptionsForLevel($debugLevel);
            config(['debugbar.options' => $options]);
        }
    }

    /**
     * Get collectors configuration based on debug level
     */
    private function getCollectorsForLevel(string $debugLevel): array
    {
        $baseCollectors = [
            'phpinfo' => false,
            'messages' => false,
            'time' => false,
            'memory' => false,
            'exceptions' => false,
            'log' => false,
            'db' => false,
            'views' => false,
            'route' => false,
            'auth' => false,
            'gate' => false,
            'session' => false,
            'symfony_request' => false,
            'mail' => false,
            'laravel' => false,
            'events' => false,
            'default_request' => false,
            'logs' => false,
            'files' => false,
            'config' => false,
            'cache' => false,
            'models' => false,
            'livewire' => false,
            'jobs' => false,
            'pennant' => false,
        ];

        switch ($debugLevel) {
            case 'minimal':
                return array_merge($baseCollectors, [
                    'exceptions' => true,
                    'log' => true,
                ]);
                
            case 'standard':
                return array_merge($baseCollectors, [
                    'exceptions' => true,
                    'log' => true,
                    'time' => true,
                    'memory' => true,
                    'db' => true,
                    'route' => true,
                ]);
                
            case 'verbose':
                return array_merge($baseCollectors, [
                    'exceptions' => true,
                    'log' => true,
                    'time' => true,
                    'memory' => true,
                    'db' => true,
                    'route' => true,
                    'views' => true,
                    'auth' => true,
                    'session' => true,
                    'laravel' => true,
                    'events' => true,
                ]);
                
            case 'development':
                return array_merge($baseCollectors, [
                    'exceptions' => true,
                    'log' => true,
                    'time' => true,
                    'memory' => true,
                    'db' => true,
                    'route' => true,
                    'views' => true,
                    'auth' => true,
                    'session' => true,
                    'laravel' => true,
                    'events' => true,
                    'messages' => true,
                    'symfony_request' => true,
                    'mail' => true,
                    'default_request' => true,
                    'logs' => true,
                    'files' => true,
                    'config' => true,
                    'cache' => true,
                    'models' => true,
                    'jobs' => true,
                ]);
                
            default:
                return $baseCollectors;
        }
    }

    /**
     * Get options configuration based on debug level
     */
    private function getOptionsForLevel(string $debugLevel): array
    {
        $baseOptions = [
            'time' => ['memory_usage' => false],
            'messages' => ['trace' => false, 'capture_dumps' => false],
            'memory' => ['reset_peak' => false, 'with_baseline' => false, 'precision' => 0],
            'auth' => ['show_name' => false, 'show_guards' => false],
            'gate' => ['trace' => false],
            'db' => [
                'with_params' => false,
                'exclude_paths' => [],
                'backtrace' => false,
                'backtrace_exclude_paths' => [],
                'timeline' => false,
                'duration_background' => false,
                'explain' => ['enabled' => false],
                'hints' => false,
                'show_copy' => false,
                'slow_threshold' => false,
                'memory_usage' => false,
                'soft_limit' => 100,
                'hard_limit' => 500,
            ],
            'mail' => ['timeline' => false, 'show_body' => false],
            'views' => ['timeline' => false, 'data' => false, 'group' => 50],
            'route' => ['label' => false],
            'session' => ['hiddens' => []],
            'symfony_request' => ['label' => false, 'hiddens' => []],
            'events' => ['data' => false, 'excluded' => []],
            'logs' => ['file' => null],
            'cache' => ['values' => false],
        ];

        switch ($debugLevel) {
            case 'minimal':
                return $baseOptions;
                
            case 'standard':
                return array_merge($baseOptions, [
                    'time' => ['memory_usage' => true],
                    'memory' => ['reset_peak' => true, 'precision' => 2],
                    'db' => [
                        'with_params' => true,
                        'backtrace' => true,
                        'timeline' => true,
                        'show_copy' => true,
                        'slow_threshold' => 100,
                    ],
                ]);
                
            case 'verbose':
                return array_merge($baseOptions, [
                    'time' => ['memory_usage' => true],
                    'memory' => ['reset_peak' => true, 'precision' => 2],
                    'messages' => ['trace' => true, 'capture_dumps' => true],
                    'auth' => ['show_name' => true, 'show_guards' => true],
                    'db' => [
                        'with_params' => true,
                        'backtrace' => true,
                        'timeline' => true,
                        'show_copy' => true,
                        'slow_threshold' => 50,
                        'memory_usage' => true,
                    ],
                    'views' => ['timeline' => true, 'data' => true],
                    'route' => ['label' => true],
                    'session' => ['hiddens' => ['password', 'password_confirmation']],
                ]);
                
            case 'development':
                return array_merge($baseOptions, [
                    'time' => ['memory_usage' => true],
                    'memory' => ['reset_peak' => true, 'precision' => 2],
                    'messages' => ['trace' => true, 'capture_dumps' => true],
                    'auth' => ['show_name' => true, 'show_guards' => true],
                    'gate' => ['trace' => true],
                    'db' => [
                        'with_params' => true,
                        'backtrace' => true,
                        'timeline' => true,
                        'show_copy' => true,
                        'slow_threshold' => 10,
                        'memory_usage' => true,
                        'explain' => ['enabled' => true],
                        'hints' => true,
                    ],
                    'mail' => ['timeline' => true, 'show_body' => true],
                    'views' => ['timeline' => true, 'data' => true],
                    'route' => ['label' => true],
                    'session' => ['hiddens' => []],
                    'symfony_request' => ['label' => true, 'hiddens' => []],
                    'events' => ['data' => true],
                    'cache' => ['values' => true],
                ]);
                
            default:
                return $baseOptions;
        }
    }
}
