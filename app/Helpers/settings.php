<?php

use App\Services\SettingsService;

if (!function_exists('setting')) {
    /**
     * Get a setting value
     */
    function setting(string $key, $default = null)
    {
        return SettingsService::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get settings by category
     */
    function settings(string $category)
    {
        return SettingsService::getByCategory($category);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     */
    function set_setting(string $key, $value, string $type = 'string', string $category = 'general')
    {
        return SettingsService::set($key, $value, $type, $category);
    }
}

if (!function_exists('toggle_setting')) {
    /**
     * Toggle a setting status
     */
    function toggle_setting(string $key)
    {
        return SettingsService::toggle($key);
    }
}

if (!function_exists('clear_settings_cache')) {
    /**
     * Clear settings cache
     */
    function clear_settings_cache(?string $key = null)
    {
        return SettingsService::clearCache($key);
    }
}

if (!function_exists('cache_remember')) {
    /**
     * Cache remember with cache_enabled check
     */
    function cache_remember(string $key, int $ttl, callable $callback)
    {
        // Check if cache is enabled
        if (!is_cache_enabled()) {
            // If cache is disabled, execute callback directly
            return $callback();
        }
        
        // Use normal cache if enabled
        return \Illuminate\Support\Facades\Cache::remember($key, $ttl, $callback);
    }
}

if (!function_exists('is_cache_enabled')) {
    /**
     * Check if cache is enabled in settings
     */
    function is_cache_enabled(): bool
    {
        try {
            // Get cache_enabled setting directly from database (not cached to avoid circular dependency)
            $cacheSetting = \App\Models\Setting::where('key', 'cache_enabled')
                ->where('category', 'performance')
                ->where('is_active', true)
                ->first();
                
            if (!$cacheSetting) {
                // If setting doesn't exist, default to enabled
                return true;
            }
            
            // Convert value to boolean
            return $cacheSetting->value === 'true' || $cacheSetting->value === '1';
        } catch (\Exception $e) {
            // If there's any error, default to enabled
            \Log::warning("Error checking cache_enabled setting: " . $e->getMessage());
            return true;
        }
    }
}

if (!function_exists('cache_forget')) {
    /**
     * Cache forget with cache_enabled check
     */
    function cache_forget(string $key): bool
    {
        // Always allow cache forget operations (for cleanup)
        return \Illuminate\Support\Facades\Cache::forget($key);
    }
}

if (!function_exists('cache_flush')) {
    /**
     * Cache flush with cache_enabled check
     */
    function cache_flush(): bool
    {
        // Always allow cache flush operations (for cleanup)
        return \Illuminate\Support\Facades\Cache::flush();
    }
}