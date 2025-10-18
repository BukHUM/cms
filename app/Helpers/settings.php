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
