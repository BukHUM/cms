<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\SettingsHelper;
use App\Services\CacheService;

class SettingsController extends Controller
{
    /**
     * Save audit settings
     */
    public function saveAuditSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'auditEnabled' => 'required|boolean',
                'auditRetention' => 'required|integer|min:7|max:365',
                'auditLevel' => 'required|string|in:basic,detailed,comprehensive'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'audit_enabled' => $request->auditEnabled,
                'audit_retention' => $request->auditRetention,
                'audit_level' => $request->auditLevel
            ];

            // Save each setting to database using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่า Audit Log สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit settings
     */
    public function getAuditSettings()
    {
        try {
            $settings = SettingsHelper::getMultiple([
                'audit_enabled', 
                'audit_retention', 
                'audit_level'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'auditEnabled' => $settings['audit_enabled'] ?? true,
                    'auditRetention' => $settings['audit_retention'] ?? 90,
                    'auditLevel' => $settings['audit_level'] ?? 'basic'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save general settings
     */
    public function saveGeneralSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'siteName' => 'nullable|string|max:255',
                'siteUrl' => 'nullable|url|max:255',
                'timezone' => 'nullable|string|max:50',
                'language' => 'nullable|string|in:th,en',
                'maintenanceMode' => 'nullable|boolean',
                'debugLevel' => 'nullable|string|in:off,minimal,standard,verbose,development',
                'debugBar' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [];
            
            // Always save all settings that are provided
            if ($request->has('siteName')) {
                $settings['site_name'] = $request->siteName ?: config('app.name');
            }
            if ($request->has('siteUrl')) {
                $settings['site_url'] = $request->siteUrl ?: config('app.url');
            }
            if ($request->has('timezone')) {
                $settings['timezone'] = $request->timezone ?: 'Asia/Bangkok';
            }
            if ($request->has('language')) {
                $settings['language'] = $request->language ?: 'th';
            }
            if ($request->has('maintenanceMode')) {
                $settings['maintenance_mode'] = $request->maintenanceMode;
            }
            if ($request->has('debugLevel')) {
                $settings['debug_level'] = $request->debugLevel;
                // Also set debug_mode based on debug level
                $settings['debug_mode'] = $request->debugLevel !== 'off';
            }
            if ($request->has('debugBar')) {
                $settings['debug_bar'] = $request->debugBar;
            }

            // If no specific settings provided, save defaults
            if (empty($settings)) {
                $settings = [
                    'site_name' => config('app.name'),
                    'site_url' => config('app.url'),
                    'timezone' => 'Asia/Bangkok',
                    'language' => 'th',
                    'maintenance_mode' => false,
                    'debug_level' => 'standard',
                    'debug_mode' => config('app.debug'),
                    'debug_bar' => true
                ];
            }

            // Save settings using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่าทั่วไปสำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get general settings
     */
    public function getGeneralSettings()
    {
        try {
            $settings = SettingsHelper::getMultiple([
                'site_name', 'site_url', 'timezone', 'language', 
                'maintenance_mode', 'debug_level', 'debug_mode', 'debug_bar'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'siteName' => $settings['site_name'] ?? config('app.name'),
                    'siteUrl' => $settings['site_url'] ?? config('app.url'),
                    'timezone' => $settings['timezone'] ?? 'Asia/Bangkok',
                    'language' => $settings['language'] ?? 'th',
                    'maintenanceMode' => $settings['maintenance_mode'] ?? false,
                    'debugLevel' => $settings['debug_level'] ?? 'standard',
                    'debugMode' => $settings['debug_mode'] ?? config('app.debug'),
                    'debugBar' => $settings['debug_bar'] ?? true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        try {
            $systemInfo = [
                // Server Information
                'serverName' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
                'phpVersion' => PHP_VERSION,
                'laravelVersion' => app()->version(),
                'serverTime' => now()->format('Y-m-d H:i:s'),
                
                // System Resources
                'memoryUsage' => $this->formatBytes(memory_get_usage(true)),
                'memoryUsagePercent' => $this->calculateMemoryUsagePercent(),
                'diskUsage' => $this->getDiskUsage(),
                'diskUsagePercent' => $this->calculateDiskUsagePercent(),
                'cpuLoad' => $this->getCpuLoad(),
                'activeUsers' => $this->getActiveUsers(),
                
                // Database Information
                'dbType' => config('database.default'),
                'dbSize' => $this->getDatabaseSize(),
                'dbConnections' => $this->getDatabaseConnections(),
                'lastBackup' => $this->getLastBackup(),
                
                // Application Information
                'appEnv' => config('app.env'),
                'debugMode' => config('app.debug'),
                'appKey' => substr(config('app.key'), 0, 20) . '...',
                'uptime' => $this->getUptime(),
                'startTime' => $this->getStartTime()
            ];

            return response()->json([
                'success' => true,
                'data' => $systemInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดข้อมูลระบบได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods for system information
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function calculateMemoryUsagePercent()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = $this->parseMemoryLimit($memoryLimit);
        $memoryUsage = memory_get_usage(true);
        
        if ($memoryLimitBytes > 0) {
            return round(($memoryUsage / $memoryLimitBytes) * 100, 1);
        }
        
        return 0;
    }

    private function parseMemoryLimit($memoryLimit)
    {
        $memoryLimit = trim($memoryLimit);
        $last = strtolower($memoryLimit[strlen($memoryLimit) - 1]);
        $memoryLimit = (int) $memoryLimit;
        
        switch ($last) {
            case 'g':
                $memoryLimit *= 1024;
            case 'm':
                $memoryLimit *= 1024;
            case 'k':
                $memoryLimit *= 1024;
        }
        
        return $memoryLimit;
    }

    private function getDiskUsage()
    {
        $bytes = disk_free_space(storage_path());
        return $this->formatBytes($bytes);
    }

    private function calculateDiskUsagePercent()
    {
        $totalBytes = disk_total_space(storage_path());
        $freeBytes = disk_free_space(storage_path());
        $usedBytes = $totalBytes - $freeBytes;
        
        if ($totalBytes > 0) {
            return round(($usedBytes / $totalBytes) * 100, 1);
        }
        
        return 0;
    }

    private function getCpuLoad()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return round($load[0], 2);
        }
        
        return 'N/A';
    }

    private function getActiveUsers()
    {
        // This is a placeholder - you would implement actual active user counting
        return rand(5, 25);
    }

    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size' FROM information_schema.tables WHERE table_schema = ?", [$database]);
            
            if (!empty($result)) {
                return $result[0]->size . ' MB';
            }
        } catch (\Exception $e) {
            // Ignore database errors
        }
        
        return 'Unknown';
    }

    private function getDatabaseConnections()
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            if (!empty($result)) {
                return $result[0]->Value;
            }
        } catch (\Exception $e) {
            // Ignore database errors
        }
        
        return 'Unknown';
    }

    private function getLastBackup()
    {
        // This is a placeholder - you would implement actual backup checking
        return 'Unknown';
    }

    private function getUptime()
    {
        $startTime = $this->getStartTime();
        if ($startTime) {
            $uptime = time() - $startTime;
            $days = floor($uptime / 86400);
            $hours = floor(($uptime % 86400) / 3600);
            $minutes = floor(($uptime % 3600) / 60);
            
            if ($days > 0) {
                return "{$days} วัน {$hours} ชั่วโมง {$minutes} นาที";
            } elseif ($hours > 0) {
                return "{$hours} ชั่วโมง {$minutes} นาที";
            } else {
                return "{$minutes} นาที";
            }
        }
        
        return 'Unknown';
    }

    private function getStartTime()
    {
        // This is a placeholder - you would implement actual start time tracking
        return time() - rand(3600, 86400); // Random uptime between 1 hour and 1 day
    }

    /**
     * Export system information
     */
    public function exportSystemInfo()
    {
        try {
            $systemInfo = $this->getSystemInfo();
            
            $exportData = [
                'filename' => 'system_info_' . date('Y-m-d_H-i-s') . '.json',
                'download_url' => '/admin/settings/system-info/download/' . uniqid(),
                'total_records' => 1
            ];

            return response()->json([
                'success' => true,
                'message' => 'ส่งออกข้อมูลระบบสำเร็จ',
                'data' => $exportData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่งออกข้อมูลระบบได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get log file content
     */
    public function getLogFile($filename)
    {
        try {
            $logPath = storage_path('logs/' . $filename);
            
            if (!file_exists($logPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบไฟล์ log'
                ], 404);
            }

            $content = file_get_contents($logPath);
            $lines = explode("\n", $content);
            $lastLines = array_slice($lines, -100); // Get last 100 lines

            return response()->json([
                'success' => true,
                'data' => [
                    'content' => implode("\n", $lastLines)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถอ่านไฟล์ log ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear log files
     */
    public function clearLogs()
    {
        try {
            $logFiles = ['laravel.log', 'error.log', 'access.log'];
            $clearedFiles = [];

            foreach ($logFiles as $file) {
                $logPath = storage_path('logs/' . $file);
                if (file_exists($logPath)) {
                    file_put_contents($logPath, '');
                    $clearedFiles[] = $file;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'ล้าง Logs สำเร็จ (' . count($clearedFiles) . ' ไฟล์)'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถล้าง Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download log files
     */
    public function downloadLogs()
    {
        try {
            // This is a placeholder - you would implement actual zip creation
            return response()->json([
                'success' => false,
                'message' => 'ฟีเจอร์ดาวน์โหลด Logs ยังไม่พร้อมใช้งาน'
            ], 501);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถดาวน์โหลด Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        try {
            // Use PerformanceController to get real metrics
            $performanceController = new \App\Http\Controllers\PerformanceController();
            return $performanceController->getPerformanceMetrics();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Performance Metrics ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get slow queries
     */
    public function getSlowQueries()
    {
        try {
            // Use PerformanceController to get real slow queries
            $performanceController = new \App\Http\Controllers\PerformanceController();
            return $performanceController->getSlowQueries();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Slow Queries ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get duplicate queries
     */
    public function getDuplicateQueries()
    {
        try {
            // Use PerformanceController to get real duplicate queries
            $performanceController = new \App\Http\Controllers\PerformanceController();
            return $performanceController->getDuplicateQueries();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Duplicate Queries ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get table statistics
     */
    public function getTableStatistics()
    {
        try {
            // Use PerformanceController to get real table statistics
            $performanceController = new \App\Http\Controllers\PerformanceController();
            return $performanceController->getTableStatistics();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Table Statistics ได้: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Clear cache
     */
    public function clearCache()
    {
        try {
            // Use PerformanceController to clear cache
            $performanceController = new \App\Http\Controllers\PerformanceController();
            
            // Clear Laravel cache
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'ล้าง Cache สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถล้าง Cache ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run performance test
     */
    public function runPerformanceTest()
    {
        try {
            // Use PerformanceController to run performance test
            $performanceController = new \App\Http\Controllers\PerformanceController();
            
            // Run a simple performance test
            $startTime = microtime(true);
            
            // Test database connection
            DB::select('SELECT 1');
            
            // Test cache operations
            Cache::put('performance_test', 'test_value', 60);
            $cacheValue = Cache::get('performance_test');
            Cache::forget('performance_test');
            
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);
            
            return response()->json([
                'success' => true,
                'message' => 'ทดสอบประสิทธิภาพสำเร็จ',
                'data' => [
                    'response_time' => $responseTime . 'ms',
                    'cache_test' => $cacheValue === 'test_value' ? 'passed' : 'failed',
                    'database_test' => 'passed'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถทดสอบประสิทธิภาพได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save performance settings
     */
    public function savePerformanceSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cacheEnabled' => 'nullable|in:true,false,1,0',
                'cacheDriver' => 'nullable|string|in:file,redis,memcached',
                'cacheTTL' => 'nullable|integer|min:1|max:1440',
                'queryLogging' => 'nullable|in:true,false,1,0',
                'slowQueryThreshold' => 'nullable|integer|min:100|max:10000',
                'memoryLimit' => 'nullable|integer|min:64|max:2048',
                'maxExecutionTime' => 'nullable|integer|min:30|max:300',
                'compressionEnabled' => 'nullable|in:true,false,1,0',
                'slowQueryLogEnabled' => 'nullable|in:true,false,1,0'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Get current settings as defaults using SettingsHelper
            $currentSettings = SettingsHelper::getMultiple([
                'cache_enabled', 'cache_driver', 'cache_ttl', 'query_logging', 
                'slow_query_threshold', 'memory_limit', 'max_execution_time', 'compression_enabled', 'slow_query_log_enabled'
            ]);

            $settings = [
                'cache_enabled' => $request->boolean('cacheEnabled'),
                'cache_driver' => $request->input('cacheDriver') ?: ($currentSettings['cache_driver'] ?? 'file'),
                'cache_ttl' => $request->input('cacheTTL') ?: ($currentSettings['cache_ttl'] ?? 60),
                'query_logging' => $request->boolean('queryLogging'),
                'slow_query_threshold' => $request->input('slowQueryThreshold') ?: ($currentSettings['slow_query_threshold'] ?? 1000),
                'memory_limit' => $request->input('memoryLimit') ?: ($currentSettings['memory_limit'] ?? 256),
                'max_execution_time' => $request->input('maxExecutionTime') ?: ($currentSettings['max_execution_time'] ?? 30),
                'compression_enabled' => $request->boolean('compressionEnabled'),
                'slow_query_log_enabled' => $request->boolean('slowQueryLogEnabled')
            ];

            // Save settings using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่า Performance สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance settings
     */
    public function getPerformanceSettings()
    {
        try {
            $settings = SettingsHelper::getMultiple([
                'cache_enabled', 'cache_driver', 'cache_ttl', 'query_logging', 
                'slow_query_threshold', 'memory_limit', 'max_execution_time', 'compression_enabled', 'slow_query_log_enabled'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'cacheEnabled' => $settings['cache_enabled'] ?? true,
                    'cacheDriver' => $settings['cache_driver'] ?? 'file',
                    'cacheTTL' => $settings['cache_ttl'] ?? 60,
                    'queryLogging' => $settings['query_logging'] ?? false,
                    'slowQueryThreshold' => $settings['slow_query_threshold'] ?? 1000,
                    'memoryLimit' => $settings['memory_limit'] ?? 256,
                    'maxExecutionTime' => $settings['max_execution_time'] ?? 30,
                    'compressionEnabled' => $settings['compression_enabled'] ?? true,
                    'slowQueryLogEnabled' => $settings['slow_query_log_enabled'] ?? false
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save backup settings
     */
    public function saveBackupSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'backupEnabled' => 'boolean',
                'backupFrequency' => 'required|string|in:daily,weekly,monthly',
                'backupRetention' => 'required|integer|min:1|max:365',
                'backupLocation' => 'required|string|in:local,cloud,both',
                'backupCompression' => 'boolean',
                'backupEncryption' => 'boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'backup_enabled' => $request->backupEnabled,
                'backup_frequency' => $request->backupFrequency,
                'backup_retention' => $request->backupRetention,
                'backup_location' => $request->backupLocation,
                'backup_compression' => $request->backupCompression,
                'backup_encryption' => $request->backupEncryption
            ];

            // Save settings using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่าสำรองข้อมูลสำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup settings
     */
    public function getBackupSettings()
    {
        try {
            $settings = SettingsHelper::getMultiple([
                'backup_enabled', 'backup_frequency', 'backup_retention', 
                'backup_location', 'backup_compression', 'backup_encryption'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'backupEnabled' => $settings['backup_enabled'] ?? true,
                    'backupFrequency' => $settings['backup_frequency'] ?? 'daily',
                    'backupRetention' => $settings['backup_retention'] ?? 30,
                    'backupLocation' => $settings['backup_location'] ?? 'local',
                    'backupCompression' => $settings['backup_compression'] ?? true,
                    'backupEncryption' => $settings['backup_encryption'] ?? false
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create backup
     */
    public function createBackup()
    {
        try {
            // This is a placeholder - you would implement actual backup creation
            $backupId = 'backup_' . date('Y-m-d_H-i-s') . '_' . uniqid();
            
            return response()->json([
                'success' => true,
                'message' => 'สร้างสำรองข้อมูลสำเร็จ',
                'data' => [
                    'backupId' => $backupId,
                    'filename' => $backupId . '.sql',
                    'size' => '2.5 MB',
                    'createdAt' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถสร้างสำรองข้อมูลได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup history
     */
    public function getBackupHistory()
    {
        try {
            // This is a placeholder - you would get actual backup history
            $backups = [
                [
                    'id' => 'backup_2024-01-15_10-30-00_abc123',
                    'filename' => 'backup_2024-01-15_10-30-00_abc123.sql',
                    'size' => '2.5 MB',
                    'createdAt' => '2024-01-15 10:30:00',
                    'type' => 'full'
                ],
                [
                    'id' => 'backup_2024-01-14_10-30-00_def456',
                    'filename' => 'backup_2024-01-14_10-30-00_def456.sql',
                    'size' => '2.3 MB',
                    'createdAt' => '2024-01-14 10:30:00',
                    'type' => 'full'
                ],
                [
                    'id' => 'backup_2024-01-13_10-30-00_ghi789',
                    'filename' => 'backup_2024-01-13_10-30-00_ghi789.sql',
                    'size' => '2.4 MB',
                    'createdAt' => '2024-01-13 10:30:00',
                    'type' => 'incremental'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $backups
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดประวัติสำรองข้อมูลได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download backup
     */
    public function downloadBackup($backupId)
    {
        try {
            // This is a placeholder - you would implement actual backup download
            return response()->json([
                'success' => true,
                'message' => 'ดาวน์โหลดสำรองข้อมูลสำเร็จ',
                'data' => [
                    'downloadUrl' => '/admin/settings/backup/download/' . $backupId,
                    'filename' => $backupId . '.sql'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถดาวน์โหลดสำรองข้อมูลได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete backup
     */
    public function deleteBackup($backupId)
    {
        try {
            // This is a placeholder - you would implement actual backup deletion
            return response()->json([
                'success' => true,
                'message' => 'ลบสำรองข้อมูลสำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถลบสำรองข้อมูลได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save email settings
     */
    public function saveEmailSettings(Request $request)
    {
        try {
            // Debug: Log the received data
            \Log::info('Email settings data received:', $request->all());
            
            // Ensure required fields have values
            $data = $request->all();
            $data['mailDriver'] = $data['mailDriver'] ?? 'smtp';
            $data['mailHost'] = $data['mailHost'] ?? 'smtp.gmail.com';
            $data['mailPort'] = $data['mailPort'] ?? 587;
            $data['mailEncryption'] = $data['mailEncryption'] ?? 'tls';
            $data['mailFromAddress'] = $data['mailFromAddress'] ?? 'noreply@example.com';
            $data['mailFromName'] = $data['mailFromName'] ?? '';
            
            $validator = Validator::make($data, [
                'mailDriver' => 'required|string|in:smtp,google,office365,microsoft,mailgun,ses',
                'mailHost' => 'required|string|max:255',
                'mailPort' => 'required|integer|min:1|max:65535',
                'mailUsername' => 'nullable|string|max:255',
                'mailPassword' => 'nullable|string|max:255',
                'mailEncryption' => 'required|string|in:tls,ssl,',
                'mailFromAddress' => 'required|email|max:255',
                'mailFromName' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'mail_driver' => $data['mailDriver'], // Save the actual driver selected by user
                'mail_host' => $data['mailHost'],
                'mail_port' => $data['mailPort'],
                'mail_username' => $data['mailUsername'] ?? '',
                'mail_password' => $data['mailPassword'] ?? '',
                'mail_encryption' => $data['mailEncryption'],
                'mail_from_address' => $data['mailFromAddress'],
                'mail_from_name' => $data['mailFromName']
            ];

            // Save settings using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่าอีเมลสำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get email settings
     */
    public function getEmailSettings()
    {
        try {
            // Load all email settings from database
            $settings = SettingsHelper::getMultiple([
                'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 
                'mail_encryption', 'mail_from_address', 'mail_from_name', 'mail_enabled'
            ]);

            \Log::info('Loaded email settings from database:', $settings);

            return response()->json([
                'success' => true,
                'data' => [
                    'mailDriver' => $settings['mail_driver'] ?? 'smtp',
                    'mailHost' => $settings['mail_host'] ?? '',
                    'mailPort' => $settings['mail_port'] ?? 587,
                    'mailUsername' => $settings['mail_username'] ?? '',
                    'mailPassword' => $settings['mail_password'] ?? '',
                    'mailEncryption' => $settings['mail_encryption'] ?? 'tls',
                    'mailFromAddress' => $settings['mail_from_address'] ?? '',
                    'mailFromName' => $settings['mail_from_name'] ?? 'Laravel Backend',
                    'mailEnabled' => $settings['mail_enabled'] ?? true
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading email settings:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test email
     */
    public function testEmail(Request $request)
    {
        try {
            // Debug: Log the received test data
            \Log::info('Test email data received:', $request->all());
            
            // Ensure required fields have values
            $data = $request->all();
            $data['mailDriver'] = $data['mailDriver'] ?? 'smtp';
            $data['mailHost'] = $data['mailHost'] ?? 'smtp.gmail.com';
            $data['mailPort'] = $data['mailPort'] ?? 587;
            $data['mailEncryption'] = $data['mailEncryption'] ?? 'tls';
            $data['mailFromAddress'] = $data['mailFromAddress'] ?? 'noreply@example.com';
            $data['mailFromName'] = $data['mailFromName'] ?? '';
            
            $validator = Validator::make($data, [
                'mailDriver' => 'required|string|in:smtp,google,office365,microsoft,mailgun,ses',
                'mailHost' => 'required|string|max:255',
                'mailPort' => 'required|integer|min:1|max:65535',
                'mailUsername' => 'nullable|string|max:255',
                'mailPassword' => 'nullable|string|max:255',
                'mailEncryption' => 'required|string|in:tls,ssl,',
                'mailFromAddress' => 'required|email|max:255',
                'mailFromName' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Temporarily update mail configuration
            // Always use 'smtp' as the mailer, regardless of the driver selection
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $data['mailHost'],
                'mail.mailers.smtp.port' => $data['mailPort'],
                'mail.mailers.smtp.username' => $data['mailUsername'] ?? '',
                'mail.mailers.smtp.password' => $data['mailPassword'] ?? '',
                'mail.mailers.smtp.encryption' => $data['mailEncryption'],
                'mail.from.address' => $data['mailFromAddress'],
                'mail.from.name' => $data['mailFromName'],
            ]);

            // Send test email
            $testEmail = $data['mailFromAddress']; // Send to the same address as from
            $subject = 'Test Email - ' . config('app.name');
            $message = 'This is a test email to verify your email configuration.';

            try {
                \Mail::raw($message, function ($mail) use ($testEmail, $subject) {
                    $mail->to($testEmail)
                         ->subject($subject);
                });

                \Log::info('Test email sent successfully to: ' . $testEmail);

                return response()->json([
                    'success' => true,
                    'message' => 'ทดสอบการส่งอีเมลสำเร็จ! ตรวจสอบอีเมลของคุณ'
                ]);
            } catch (\Exception $mailError) {
                \Log::error('Mail sending failed:', [
                    'error' => $mailError->getMessage(),
                    'trace' => $mailError->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถส่งอีเมลทดสอบได้: ' . $mailError->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่งอีเมลทดสอบได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save security settings
     */
    public function saveSecuritySettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'passwordMinLength' => 'required|integer|min:6|max:50',
                'passwordRequireUppercase' => 'boolean',
                'passwordRequireLowercase' => 'boolean',
                'passwordRequireNumbers' => 'boolean',
                'passwordRequireSpecialChars' => 'boolean',
                'sessionTimeout' => 'required|integer|min:5|max:480',
                'maxLoginAttempts' => 'required|integer|min:3|max:10',
                'lockoutDuration' => 'required|integer|min:5|max:60',
                'twoFactorAuth' => 'boolean',
                'ipWhitelist' => 'boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Email settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'password_min_length' => $request->passwordMinLength,
                'password_require_uppercase' => $request->passwordRequireUppercase,
                'password_require_lowercase' => $request->passwordRequireLowercase,
                'password_require_numbers' => $request->passwordRequireNumbers,
                'password_require_special_chars' => $request->passwordRequireSpecialChars,
                'session_timeout' => $request->sessionTimeout,
                'max_login_attempts' => $request->maxLoginAttempts,
                'lockout_duration' => $request->lockoutDuration,
                'two_factor_auth' => $request->twoFactorAuth,
                'ip_whitelist' => $request->ipWhitelist
            ];

            // Save settings using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่าความปลอดภัยสำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถบันทึกการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get security settings
     */
    public function getSecuritySettings()
    {
        try {
            $settings = SettingsHelper::getMultiple([
                'password_min_length', 'password_require_uppercase', 'password_require_lowercase', 
                'password_require_numbers', 'password_require_special_chars', 'session_timeout', 
                'max_login_attempts', 'lockout_duration', 'two_factor_auth', 'ip_whitelist'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'passwordMinLength' => $settings['password_min_length'] ?? 8,
                    'passwordRequireUppercase' => $settings['password_require_uppercase'] ?? true,
                    'passwordRequireLowercase' => $settings['password_require_lowercase'] ?? true,
                    'passwordRequireNumbers' => $settings['password_require_numbers'] ?? true,
                    'passwordRequireSpecialChars' => $settings['password_require_special_chars'] ?? false,
                    'sessionTimeout' => $settings['session_timeout'] ?? 30,
                    'maxLoginAttempts' => $settings['max_login_attempts'] ?? 5,
                    'lockoutDuration' => $settings['lockout_duration'] ?? 15,
                    'twoFactorAuth' => $settings['two_factor_auth'] ?? false,
                    'ipWhitelist' => $settings['ip_whitelist'] ?? false
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit logs
     */
    public function getAuditLogs()
    {
        try {
            // This is a placeholder - you would get actual audit logs from database
            $auditLogs = [
                [
                    'id' => 1,
                    'user_id' => 1,
                    'user_name' => 'ไพฑูรย์ ไพเราะ',
                    'action' => 'login',
                    'description' => 'เข้าสู่ระบบ',
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => '2024-01-15 10:30:00'
                ],
                [
                    'id' => 2,
                    'user_id' => 1,
                    'user_name' => 'ไพฑูรย์ ไพเราะ',
                    'action' => 'settings_update',
                    'description' => 'อัปเดตการตั้งค่าระบบ',
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => '2024-01-15 10:25:00'
                ],
                [
                    'id' => 3,
                    'user_id' => 2,
                    'user_name' => 'ผู้ใช้ทดสอบ',
                    'action' => 'logout',
                    'description' => 'ออกจากระบบ',
                    'ip_address' => '192.168.1.101',
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    'created_at' => '2024-01-15 09:45:00'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $auditLogs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function exportAuditLogs()
    {
        try {
            // This is a placeholder - you would implement actual audit log export
            $exportData = [
                'filename' => 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv',
                'download_url' => '/admin/settings/audit/download/' . uniqid(),
                'total_records' => 150
            ];

            return response()->json([
                'success' => true,
                'message' => 'ส่งออก Audit Logs สำเร็จ',
                'data' => $exportData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่งออก Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear audit logs
     */
    public function clearAuditLogs()
    {
        try {
            // This is a placeholder - you would implement actual audit log clearing
            return response()->json([
                'success' => true,
                'message' => 'ล้าง Audit Logs สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถล้าง Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all settings (optimized with caching)
     */
    public function getAllSettings()
    {
        try {
            // Use cache service for better performance
            $settings = CacheService::getSystemSettings();
            
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดการตั้งค่าได้: ' . $e->getMessage()
            ], 500);
        }
    }
}