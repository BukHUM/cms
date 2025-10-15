<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Cache key for settings
     */
    private const CACHE_KEY = 'laravel_settings';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Settings that should always come from .env/config (not database)
     */
    private static $envOnlySettings = [
        'DB_CONNECTION',
        'DB_HOST', 
        'DB_PASSWORD',
        'DB_USERNAME',
        'DB_DATABASE',
        'DB_PORT',
        'APP_KEY',
        'APP_ENV',
        'APP_URL'
    ];

    /**
     * Get setting value with priority: Database Settings > Config > Default
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // If it's an env-only setting, always use config
        if (in_array($key, self::$envOnlySettings)) {
            return config($key, $default);
        }

        // Try to get from database settings
        $value = self::getFromDatabase($key);
        
        if ($value !== null) {
            return self::castValue($value);
        }

        // Fallback to config
        return config($key, $default);
    }

    /**
     * Set setting value in database
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, $value): bool
    {
        try {
            // Don't allow setting env-only settings in database
            if (in_array($key, self::$envOnlySettings)) {
                throw new \InvalidArgumentException("Setting '{$key}' cannot be stored in database. It must be set in .env file.");
            }

            // Convert boolean to integer for database storage
            if (is_bool($value)) {
                $value = $value ? 1 : 0;
            }

            $type = self::getValueType($value);
            
            DB::table('laravel_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'updated_at' => now()
                ]
            );

            // Clear cache
            self::clearCache();

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to set setting '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get multiple settings at once
     *
     * @param array $keys
     * @return array
     */
    public static function getMultiple(array $keys): array
    {
        $result = [];
        
        foreach ($keys as $key) {
            $result[$key] = self::get($key);
        }
        
        return $result;
    }

    /**
     * Get all database settings
     *
     * @return array
     */
    public static function getAll(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $settings = DB::table('laravel_settings')
                ->pluck('value', 'key')
                ->toArray();

            $result = [];
            foreach ($settings as $key => $value) {
                $result[$key] = self::castValue($value);
            }

            return $result;
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get setting from database with cache
     *
     * @param string $key
     * @return mixed
     */
    private static function getFromDatabase(string $key)
    {
        $allSettings = self::getAll();
        return $allSettings[$key] ?? null;
    }

    /**
     * Cast value to appropriate type
     *
     * @param mixed $value
     * @return mixed
     */
    private static function castValue($value)
    {
        if (is_string($value)) {
            // Handle boolean values more explicitly
            if ($value === '1' || $value === 'true' || $value === 'yes') {
                return true;
            } elseif ($value === '0' || $value === 'false' || $value === 'no') {
                return false;
            }
            
            // Try to detect integer values
            if (is_numeric($value) && !str_contains($value, '.')) {
                return (int) $value;
            }
            
            // Try to detect float values
            if (is_numeric($value) && str_contains($value, '.')) {
                return (float) $value;
            }
        }

        return $value;
    }

    /**
     * Get value type for storage
     *
     * @param mixed $value
     * @return string
     */
    private static function getValueType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_int($value)) {
            return 'integer';
        } elseif (is_float($value)) {
            return 'float';
        } elseif (is_array($value) || is_object($value)) {
            return 'json';
        } else {
            return 'string';
        }
    }

    /**
     * Check if setting exists in database
     *
     * @param string $key
     * @return bool
     */
    public static function exists(string $key): bool
    {
        return DB::table('laravel_settings')->where('key', $key)->exists();
    }

    /**
     * Delete setting from database
     *
     * @param string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        try {
            if (in_array($key, self::$envOnlySettings)) {
                throw new \InvalidArgumentException("Cannot delete env-only setting '{$key}'");
            }

            $deleted = DB::table('laravel_settings')->where('key', $key)->delete();
            
            if ($deleted) {
                self::clearCache();
            }
            
            return $deleted > 0;
        } catch (\Exception $e) {
            \Log::error("Failed to delete setting '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get settings that can be modified via admin panel
     *
     * @return array
     */
    public static function getModifiableSettings(): array
    {
        $allSettings = self::getAll();
        
        // Filter out env-only settings
        return array_filter($allSettings, function ($key) {
            return !in_array($key, self::$envOnlySettings);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get env-only settings (read-only from admin panel)
     *
     * @return array
     */
    public static function getEnvOnlySettings(): array
    {
        $result = [];
        
        foreach (self::$envOnlySettings as $key) {
            $result[$key] = config($key);
        }
        
        return $result;
    }
}
