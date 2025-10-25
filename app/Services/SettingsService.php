<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        // Check if cache is enabled before using cache
        if (!is_cache_enabled()) {
            // If cache is disabled, get directly from database
            $setting = Setting::where('key', $key)
                ->where('is_active', true)
                ->first();
                
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->value, $setting->type);
        }
        
        $cacheKey = "setting.{$key}";
        
        return cache_remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)
                ->where('is_active', true)
                ->first();
                
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->value, $setting->type);
        });
    }
    
    /**
     * Get multiple settings by category
     */
    public static function getByCategory(string $category)
    {
        // Check if cache is enabled before using cache
        if (!is_cache_enabled()) {
            // If cache is disabled, get directly from database
            return Setting::where('category', $category)
                ->where('is_active', true)
                ->pluck('value', 'key')
                ->map(function ($value, $key) {
                    $setting = Setting::where('key', $key)->first();
                    return self::castValue($value, $setting->type);
                });
        }
        
        $cacheKey = "settings.category.{$category}";
        
        return cache_remember($cacheKey, 3600, function () use ($category) {
            return Setting::where('category', $category)
                ->where('is_active', true)
                ->pluck('value', 'key')
                ->map(function ($value, $key) {
                    $setting = Setting::where('key', $key)->first();
                    return self::castValue($value, $setting->type);
                });
        });
    }
    
    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $category = 'general')
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => (string) $value,
                'type' => $type,
                'category' => $category,
                'is_active' => true,
            ]
        );
        
        // Clear cache
        self::clearCache($key);
        
        return $setting;
    }
    
    /**
     * Toggle setting status
     */
    public static function toggle(string $key)
    {
        $setting = Setting::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['is_active' => !$setting->is_active]);
            self::clearCache($key);
        }
        
        return $setting;
    }
    
    /**
     * Clear setting cache
     */
    public static function clearCache(?string $key = null)
    {
        if ($key) {
            cache_forget("setting.{$key}");
        } else {
            cache_forget('settings.category.general');
            cache_forget('settings.category.email');
            cache_forget('settings.category.security');
            cache_forget('settings.category.performance');
            cache_forget('settings.category.system');
        }
    }
    
    /**
     * Cast value to appropriate type
     */
    private static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return is_string($value) ? explode(',', $value) : $value;
            default:
                return $value;
        }
    }
    
    /**
     * Get all active settings as config array
     */
    public static function getConfig()
    {
        // Check if cache is enabled before using cache
        if (!is_cache_enabled()) {
            // If cache is disabled, get directly from database
            $settings = Setting::where('is_active', true)->get();
            
            $config = [];
            foreach ($settings as $setting) {
                $config[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            
            return $config;
        }
        
        $cacheKey = 'settings.config';
        
        return cache_remember($cacheKey, 3600, function () {
            $settings = Setting::where('is_active', true)->get();
            
            $config = [];
            foreach ($settings as $setting) {
                $config[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            
            return $config;
        });
    }
    
}
