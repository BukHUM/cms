<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use App\Models\Setting;

class CacheControlServiceProvider extends ServiceProvider
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
        // Override Laravel's view caching behavior based on cache_enabled setting
        $this->overrideViewCaching();
        
        // Override Laravel's config caching behavior based on cache_enabled setting
        $this->overrideConfigCaching();
        
        // Clear view cache on every request if cache is disabled
        $this->clearViewCacheOnRequest();
        
        // Listen for setting changes to clear cache immediately
        $this->listenForSettingChanges();
        
        // Force disable caching at Laravel core level
        $this->forceDisableCaching();
    }
    
    /**
     * Override view caching based on cache_enabled setting
     */
    private function overrideViewCaching(): void
    {
        if (!is_cache_enabled()) {
            // Disable view caching when cache_enabled is false
            try {
                $compiler = View::getEngineResolver()->resolve('blade')->getCompiler();
                $compiler->setCachePath(null);
                
                // Also disable view caching in config
                Config::set('view.compiled', null);
                
                // Clear existing compiled views
                $this->clearViewCacheOnRequest();
            } catch (\Exception $e) {
                \Log::warning("Error disabling view caching: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Override config caching based on cache_enabled setting
     */
    private function overrideConfigCaching(): void
    {
        if (!is_cache_enabled()) {
            // Clear config cache when cache_enabled is false
            cache_forget('config');
            cache_forget('routes');
            cache_forget('views');
        }
    }
    
    /**
     * Clear view cache on every request if cache is disabled
     */
    private function clearViewCacheOnRequest(): void
    {
        if (!is_cache_enabled()) {
            // Clear view cache on every request when cache is disabled
            try {
                $viewPath = storage_path('framework/views');
                if (is_dir($viewPath)) {
                    $files = glob($viewPath . '/*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("Error clearing view cache: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Listen for setting changes to clear cache immediately
     */
    private function listenForSettingChanges(): void
    {
        // Listen for model events
        \App\Models\Setting::updated(function ($setting) {
            // Clear view cache when any setting is updated
            $this->clearViewCacheOnRequest();
            
            // If cache_enabled setting is updated, clear all caches
            if ($setting->key === 'cache_enabled') {
                $this->clearAllCaches();
            }
        });
        
        \App\Models\Setting::created(function ($setting) {
            // Clear view cache when any setting is created
            $this->clearViewCacheOnRequest();
        });
        
        \App\Models\Setting::deleted(function ($setting) {
            // Clear view cache when any setting is deleted
            $this->clearViewCacheOnRequest();
        });
    }
    
    /**
     * Clear all caches
     */
    private function clearAllCaches(): void
    {
        try {
            // Clear application cache
            cache_flush();
            
            // Clear view cache
            $this->clearViewCacheOnRequest();
            
            // Clear config cache
            cache_forget('config');
            cache_forget('routes');
            cache_forget('views');
            
        } catch (\Exception $e) {
            \Log::warning("Error clearing all caches: " . $e->getMessage());
        }
    }
    
    /**
     * Force disable caching at Laravel core level
     */
    private function forceDisableCaching(): void
    {
        if (!is_cache_enabled()) {
            try {
                // Force disable all Laravel caching mechanisms
                $this->disableViewCaching();
                $this->disableConfigCaching();
                $this->disableRouteCaching();
                $this->disableApplicationCaching();
                
                // Clear all existing caches
                $this->clearAllCaches();
                
            } catch (\Exception $e) {
                \Log::warning("Error forcing cache disable: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Disable view caching completely
     */
    private function disableViewCaching(): void
    {
        // Set view compiled path to null
        Config::set('view.compiled', null);
        
        // Override Blade compiler
        $this->app->singleton('blade.compiler', function ($app) {
            $compiler = new \Illuminate\View\Compilers\BladeCompiler($app['files'], null);
            $compiler->setCachePath(null);
            return $compiler;
        });
        
        // Clear view cache directory
        $viewPath = storage_path('framework/views');
        if (is_dir($viewPath)) {
            $files = glob($viewPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
    
    /**
     * Disable config caching
     */
    private function disableConfigCaching(): void
    {
        // Clear config cache
        cache_forget('config');
        
        // Force reload config
        $this->app['config']->set('cache.default', 'array');
    }
    
    /**
     * Disable route caching
     */
    private function disableRouteCaching(): void
    {
        // Clear route cache
        cache_forget('routes');
        
        // Force reload routes
        $this->app['router']->getRoutes()->refresh();
    }
    
    /**
     * Disable application caching
     */
    private function disableApplicationCaching(): void
    {
        // Set cache driver to array (no persistence)
        Config::set('cache.default', 'array');
        Config::set('cache.stores.array.driver', 'array');
        
        // Clear all cache
        cache_flush();
    }
}
