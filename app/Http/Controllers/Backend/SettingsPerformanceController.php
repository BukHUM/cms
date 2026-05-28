<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class SettingsPerformanceController extends BaseSettingsController
{
    protected $category = 'performance';
    protected $viewPath = 'backend.settings-performance';
    protected $routePrefix = 'backend.settings-performance';

    public function __construct()
    {
        parent::__construct();
        // Enable permission check for security
        $this->middleware('permission:performance.view', ['only' => ['index']]);
        $this->middleware('permission:performance.edit', ['only' => ['update']]);
        $this->middleware('permission:performance.cache-clear', ['only' => ['clearAllCache', 'clearPerformanceCache']]);
    }

    /**
     * Get available groups for performance settings
     */
    protected function getCategoryGroups()
    {
        return [
            'cache' => 'การตั้งค่า Cache',
            'database' => 'การตั้งค่าฐานข้อมูล',
            'memory' => 'การตั้งค่าหน่วยความจำ',
            'session' => 'การตั้งค่า Session',
            'queue' => 'การตั้งค่า Queue',
            'logging' => 'การตั้งค่า Logging',
            'optimization' => 'การตั้งค่าการปรับปรุงประสิทธิภาพ',
        ];
    }

    /**
     * Clear all cache (with security and rate limiting)
     */
    public function clearAllCache(Request $request)
    {
        // Rate limiting to prevent abuse
        $key = 'clear_all_cache_' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "คุณพยายามล้าง Cache บ่อยเกินไป กรุณารอ {$seconds} วินาที"
                ], 429);
            }
            
            return redirect()->back()->with('error', "คุณพยายามล้าง Cache บ่อยเกินไป กรุณารอ {$seconds} วินาที");
        }
        
        RateLimiter::hit($key, 300); // 5 minutes cooldown

        try {
            // Log before clearing cache
            Log::info('Starting cache clear operation', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'operation' => 'clear_all_cache'
            ]);

            // Clear application cache
            Artisan::call('cache:clear');
            
            // Clear config cache
            Artisan::call('config:clear');
            
            // Clear route cache
            Artisan::call('route:clear');
            
            // Clear view cache
            Artisan::call('view:clear');
            
            // Clear compiled services
            Artisan::call('clear-compiled');
            
            // Clear all Laravel cache
            cache_flush();
            
            // Log successful operation
            Log::info('All cache cleared successfully', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ล้าง Cache ทั้งหมดเรียบร้อยแล้ว'
                ]);
            }

            return redirect()->back()->with('success', 'ล้าง Cache ทั้งหมดเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            Log::error('Error clearing all cache', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการล้าง Cache'
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการล้าง Cache');
        }
    }

    /**
     * Clear performance-related cache only (with security and rate limiting)
     */
    public function clearPerformanceCache(Request $request)
    {
        // Rate limiting to prevent abuse
        $key = 'clear_performance_cache_' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "คุณพยายามล้าง Performance Cache บ่อยเกินไป กรุณารอ {$seconds} วินาที"
                ], 429);
            }
            
            return redirect()->back()->with('error', "คุณพยายามล้าง Performance Cache บ่อยเกินไป กรุณารอ {$seconds} วินาที");
        }
        
        RateLimiter::hit($key, 180); // 3 minutes cooldown

        try {
            // Log before clearing cache
            Log::info('Starting performance cache clear operation', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'operation' => 'clear_performance_cache'
            ]);

            // Clear application cache (most important for performance)
            Artisan::call('cache:clear');
            
            // Clear config cache (affects performance settings)
            Artisan::call('config:clear');
            
            // Clear view cache (affects page loading speed)
            Artisan::call('view:clear');
            
            // Clear specific performance-related cache keys
            $performanceKeys = [
                'performance_settings',
                'cache_settings',
                'optimization_settings',
                'memory_settings',
                'session_settings',
                'queue_settings',
                'logging_settings',
            ];
            
            foreach ($performanceKeys as $key) {
                cache_forget($key);
            }
            
            // Log successful operation
            Log::info('Performance cache cleared successfully', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'cleared_keys' => $performanceKeys,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ล้าง Performance Cache เรียบร้อยแล้ว'
                ]);
            }

            return redirect()->back()->with('success', 'ล้าง Performance Cache เรียบร้อยแล้ว');

        } catch (\Exception $e) {
            Log::error('Error clearing performance cache', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการล้าง Performance Cache'
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการล้าง Performance Cache');
        }
    }
}
