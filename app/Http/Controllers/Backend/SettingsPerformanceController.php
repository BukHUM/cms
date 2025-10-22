<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use App\Services\LoggingService;

class SettingsPerformanceController extends BaseSettingsController
{
    protected $category = 'performance';
    protected $viewPath = 'backend.settings-performance';
    protected $routePrefix = 'backend.settings-performance';

    public function __construct()
    {
        parent::__construct();
        // Temporarily disable permission check for testing
        // $this->middleware('permission:performance.view', ['only' => ['index']]);
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
     * Clear all cache
     */
    public function clearAllCache(Request $request)
    {
        try {
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
            Cache::flush();
            
            // Log activity
            Log::info('All cache cleared', [
                'user_id' => auth()->id(),
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
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการล้าง Cache: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการล้าง Cache: ' . $e->getMessage());
        }
    }

    /**
     * Clear performance-related cache only
     */
    public function clearPerformanceCache(Request $request)
    {
        try {
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
                Cache::forget($key);
            }
            
            // Log activity
            Log::info('Performance cache cleared', [
                'user_id' => auth()->id(),
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
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการล้าง Performance Cache: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการล้าง Performance Cache: ' . $e->getMessage());
        }
    }
}
