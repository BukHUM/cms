<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class LoggingService
{
    /**
     * Apply logging settings from database
     */
    public static function applySettings()
    {
        try {
            // Get log_daily setting
            $logDaily = Setting::where('key', 'log_daily')
                ->where('category', 'performance')
                ->where('is_active', true)
                ->first();
            
            // Get log_level setting
            $logLevel = Setting::where('key', 'log_level')
                ->where('category', 'performance')
                ->where('is_active', true)
                ->first();
            
            // Get log_max_files setting
            $logMaxFiles = Setting::where('key', 'log_max_files')
                ->where('category', 'performance')
                ->where('is_active', true)
                ->first();
            
            // Apply log_daily setting
            if ($logDaily) {
                $isDaily = $logDaily->value === 'true' || $logDaily->value === '1';
                Config::set('logging.default', $isDaily ? 'daily' : 'single');
                
                // Log the change
                Log::info('Logging mode changed', [
                    'mode' => $isDaily ? 'daily' : 'single',
                    'setting_id' => $logDaily->id
                ]);
            }
            
            // Apply log_level setting
            if ($logLevel) {
                $level = $logLevel->value;
                Config::set('logging.channels.single.level', $level);
                Config::set('logging.channels.daily.level', $level);
                
                // Log the change
                Log::info('Log level changed', [
                    'level' => $level,
                    'setting_id' => $logLevel->id
                ]);
            }
            
            // Apply log_max_files setting
            if ($logMaxFiles) {
                $maxFiles = (int) $logMaxFiles->value;
                Config::set('logging.channels.daily.days', $maxFiles);
                
                // Log the change
                Log::info('Log max files changed', [
                    'max_files' => $maxFiles,
                    'setting_id' => $logMaxFiles->id
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to apply logging settings', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test logging functionality
     */
    public static function testLogging()
    {
        try {
            Log::info('Logging test started', [
                'timestamp' => now(),
                'test_type' => 'manual_test'
            ]);
            
            Log::warning('This is a test warning message', [
                'timestamp' => now(),
                'test_type' => 'manual_test'
            ]);
            
            Log::error('This is a test error message', [
                'timestamp' => now(),
                'test_type' => 'manual_test'
            ]);
            
            return [
                'success' => true,
                'message' => 'Logging test completed successfully'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Logging test failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get current logging configuration
     */
    public static function getCurrentConfig()
    {
        return [
            'default_channel' => Config::get('logging.default'),
            'single_level' => Config::get('logging.channels.single.level'),
            'daily_level' => Config::get('logging.channels.daily.level'),
            'daily_days' => Config::get('logging.channels.daily.days'),
            'log_path' => storage_path('logs'),
        ];
    }
}
