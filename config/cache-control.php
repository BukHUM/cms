<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache Control Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file controls the cache behavior based on the
    | cache_enabled setting in the database.
    |
    */

    'enabled' => function() {
        try {
            // Get cache_enabled setting directly from database
            $cacheSetting = \App\Models\Setting::where('key', 'cache_enabled')
                ->where('category', 'performance')
                ->where('is_active', true)
                ->first();
                
            if (!$cacheSetting) {
                return true; // Default to enabled
            }
            
            return $cacheSetting->value === 'true' || $cacheSetting->value === '1';
        } catch (\Exception $e) {
            return true; // Default to enabled on error
        }
    },

    'force_disable' => [
        'view' => true,
        'config' => true,
        'route' => true,
        'application' => true,
    ],
];
