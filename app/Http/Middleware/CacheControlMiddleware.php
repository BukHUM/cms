<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;

class CacheControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if cache is enabled
        $cacheEnabled = is_cache_enabled();
        
        if (!$cacheEnabled) {
            // If cache is disabled, force disable ALL caching mechanisms
            $this->forceDisableAllCaching();
            
            // Also clear cache on every request to ensure fresh data
            $this->clearAllCachesOnEveryRequest();
        }
        
        return $next($request);
    }
    
    
    /**
     * Clear all caches
     */
    private function clearAllCaches(): void
    {
        try {
            // Clear application cache
            cache_flush();
            
            // Clear view cache using Artisan command
            Artisan::call('view:clear');
            
            // Clear view cache manually (for immediate effect)
            $this->clearViewCacheManually();
            
            // Clear config cache
            Artisan::call('config:clear');
            
            // Clear route cache
            Artisan::call('route:clear');
            
        } catch (\Exception $e) {
            \Log::warning("Error clearing caches in CacheControlMiddleware: " . $e->getMessage());
        }
    }
    
    /**
     * Clear view cache manually
     */
    private function clearViewCacheManually(): void
    {
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
            \Log::warning("Error clearing view cache manually: " . $e->getMessage());
        }
    }
    
    /**
     * Disable Laravel's built-in caching
     */
    private function disableLaravelCaching(): void
    {
        try {
            // Disable view caching
            \Illuminate\Support\Facades\View::getEngineResolver()->resolve('blade')->getCompiler()->setCachePath(null);
            
            // Disable config caching
            \Illuminate\Support\Facades\Config::set('view.compiled', null);
            
            // Clear any existing cache
            cache_flush();
            
        } catch (\Exception $e) {
            \Log::warning("Error disabling Laravel caching: " . $e->getMessage());
        }
    }
    
    /**
     * Force disable ALL caching mechanisms
     */
    private function forceDisableAllCaching(): void
    {
        try {
            // 1. Clear all view cache files
            $this->clearViewCacheManually();
            
            // 2. Disable Laravel's built-in caching
            $this->disableLaravelCaching();
            
            // 3. Force disable Blade compiler caching
            $this->disableBladeCompilerCaching();
            
            // 4. Clear all application cache
            cache_flush();
            
            // 5. Force reload config
            \Illuminate\Support\Facades\Config::set('cache.default', 'array');
            \Illuminate\Support\Facades\Config::set('view.compiled', null);
            
        } catch (\Exception $e) {
            \Log::warning("Error forcing disable all caching: " . $e->getMessage());
        }
    }
    
    /**
     * Disable Blade compiler caching completely
     */
    private function disableBladeCompilerCaching(): void
    {
        try {
            // Get the Blade compiler and disable caching
            $compiler = \Illuminate\Support\Facades\View::getEngineResolver()->resolve('blade')->getCompiler();
            $compiler->setCachePath(null);
            
            // Also try to override the compiler instance
            app()->singleton('blade.compiler', function ($app) {
                $compiler = new \Illuminate\View\Compilers\BladeCompiler($app['files'], null);
                $compiler->setCachePath(null);
                return $compiler;
            });
            
        } catch (\Exception $e) {
            \Log::warning("Error disabling Blade compiler caching: " . $e->getMessage());
        }
    }
    
    /**
     * Clear all caches on every request when cache is disabled
     */
    private function clearAllCachesOnEveryRequest(): void
    {
        try {
            // Clear all Laravel caches
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            \Artisan::call('clear-compiled');
            
            // Clear all cache files manually
            $this->clearViewCacheManually();
            $this->clearConfigCacheManually();
            $this->clearRouteCacheManually();
            
            // Force flush all cache
            cache_flush();
            
        } catch (\Exception $e) {
            \Log::warning("Error clearing all caches on every request: " . $e->getMessage());
        }
    }
    
    /**
     * Clear config cache manually
     */
    private function clearConfigCacheManually(): void
    {
        try {
            $configPath = bootstrap_path('cache/packages.php');
            if (file_exists($configPath)) {
                unlink($configPath);
            }
            
            $servicesPath = bootstrap_path('cache/services.php');
            if (file_exists($servicesPath)) {
                unlink($servicesPath);
            }
            
        } catch (\Exception $e) {
            \Log::warning("Error clearing config cache manually: " . $e->getMessage());
        }
    }
    
    /**
     * Clear route cache manually
     */
    private function clearRouteCacheManually(): void
    {
        try {
            $routePath = bootstrap_path('cache/routes-v7.php');
            if (file_exists($routePath)) {
                unlink($routePath);
            }
            
        } catch (\Exception $e) {
            \Log::warning("Error clearing route cache manually: " . $e->getMessage());
        }
    }
}
