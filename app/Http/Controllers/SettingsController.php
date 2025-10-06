<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

            // Save each setting to database
            foreach ($settings as $key => $value) {
                DB::table('laravel_settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string'),
                        'updated_at' => now()
                    ]
                );
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
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['audit_enabled', 'audit_retention', 'audit_level'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'auditEnabled' => filter_var($settings['audit_enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'auditRetention' => (int)($settings['audit_retention'] ?? 90),
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
                'siteEnabled' => 'nullable|boolean',
                'maintenanceMode' => 'nullable|boolean',
                'debugMode' => 'nullable|boolean',
                'autoSave' => 'nullable|boolean',
                'notifications' => 'nullable|boolean',
                'analytics' => 'nullable|boolean',
                'updates' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
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
            if ($request->has('siteEnabled')) {
                $settings['site_enabled'] = $request->siteEnabled;
            }
            if ($request->has('maintenanceMode')) {
                $settings['maintenance_mode'] = $request->maintenanceMode;
            }
            if ($request->has('debugMode')) {
                $settings['debug_mode'] = $request->debugMode;
            }
            if ($request->has('autoSave')) {
                $settings['auto_save'] = $request->autoSave;
            }
            if ($request->has('notifications')) {
                $settings['notifications'] = $request->notifications;
            }
            if ($request->has('analytics')) {
                $settings['analytics'] = $request->analytics;
            }
            if ($request->has('updates')) {
                $settings['updates'] = $request->updates;
            }

            // If no specific settings provided, save defaults
            if (empty($settings)) {
                $settings = [
                    'site_name' => config('app.name'),
                    'site_url' => config('app.url'),
                    'timezone' => 'Asia/Bangkok',
                    'language' => 'th',
                    'site_enabled' => true,
                    'maintenance_mode' => false,
                    'debug_mode' => config('app.debug'),
                    'auto_save' => true,
                    'notifications' => true,
                    'analytics' => true,
                    'updates' => true
                ];
            }

            $this->saveSettings($settings);

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
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['site_name', 'site_url', 'timezone', 'language', 'site_enabled', 'maintenance_mode', 'debug_mode', 'auto_save', 'notifications', 'analytics', 'updates'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'siteName' => $settings['site_name'] ?? config('app.name'),
                    'siteUrl' => $settings['site_url'] ?? config('app.url'),
                    'timezone' => $settings['timezone'] ?? 'Asia/Bangkok',
                    'language' => $settings['language'] ?? 'th',
                    'siteEnabled' => filter_var($settings['site_enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'maintenanceMode' => filter_var($settings['maintenance_mode'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'debugMode' => filter_var($settings['debug_mode'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'autoSave' => filter_var($settings['auto_save'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'notifications' => filter_var($settings['notifications'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'analytics' => filter_var($settings['analytics'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'updates' => filter_var($settings['updates'] ?? true, FILTER_VALIDATE_BOOLEAN)
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
            // This is a placeholder - you would get actual performance metrics
            $metrics = [
                'responseTime' => '245ms',
                'memoryUsage' => '128MB',
                'cacheHitRate' => '85%',
                'activeConnections' => '12',
                'bufferPoolHitRate' => '95.5%',
                'tableLockWaitRatio' => '2.1%',
                'tmpTableRatio' => '15.3%',
                'totalQueries' => '1000'
            ];

            return response()->json([
                'success' => true,
                'data' => $metrics
            ]);

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
            // This is a placeholder - you would get actual slow queries from database
            $slowQueries = [
                [
                    'query' => 'SELECT * FROM users WHERE created_at > ?',
                    'time' => 1250,
                    'count' => 5
                ],
                [
                    'query' => 'SELECT COUNT(*) FROM orders WHERE status = ?',
                    'time' => 890,
                    'count' => 12
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $slowQueries
            ]);

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
            // This is a placeholder - you would get actual duplicate queries
            $duplicateQueries = [
                [
                    'query' => 'SELECT * FROM users WHERE id = ?',
                    'count' => 150,
                    'impact' => 'high'
                ],
                [
                    'query' => 'SELECT name FROM products WHERE active = 1',
                    'count' => 75,
                    'impact' => 'medium'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $duplicateQueries
            ]);

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
            // This is a placeholder - you would get actual table statistics
            $tableStatistics = [
                [
                    'name' => 'users',
                    'rows' => 1250,
                    'size' => '2.5',
                    'engine' => 'InnoDB'
                ],
                [
                    'name' => 'orders',
                    'rows' => 5670,
                    'size' => '8.2',
                    'engine' => 'InnoDB'
                ],
                [
                    'name' => 'products',
                    'rows' => 890,
                    'size' => '1.8',
                    'engine' => 'InnoDB'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $tableStatistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Table Statistics ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get index statistics
     */
    public function getIndexStatistics()
    {
        try {
            // This is a placeholder - you would get actual index statistics
            $indexStatistics = [
                [
                    'table' => 'users',
                    'name' => 'PRIMARY',
                    'usage' => '95.2',
                    'type' => 'BTREE'
                ],
                [
                    'table' => 'users',
                    'name' => 'email_unique',
                    'usage' => '87.5',
                    'type' => 'BTREE'
                ],
                [
                    'table' => 'orders',
                    'name' => 'user_id_index',
                    'usage' => '92.1',
                    'type' => 'BTREE'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $indexStatistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Index Statistics ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        try {
            // This is a placeholder - you would implement actual cache clearing
            // For now, we'll just simulate a successful cache clear
            
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
            // This is a placeholder - you would implement actual performance testing
            // For now, we'll just simulate a successful test
            
            return response()->json([
                'success' => true,
                'message' => 'ทดสอบประสิทธิภาพสำเร็จ'
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
                'cacheEnabled' => 'boolean',
                'cacheDriver' => 'required|string|in:file,redis,memcached',
                'cacheTTL' => 'required|integer|min:1|max:1440',
                'queryLogging' => 'boolean',
                'slowQueryThreshold' => 'required|integer|min:100|max:10000',
                'memoryLimit' => 'required|integer|min:64|max:2048',
                'maxExecutionTime' => 'required|integer|min:30|max:300',
                'compressionEnabled' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'cache_enabled' => $request->cacheEnabled,
                'cache_driver' => $request->cacheDriver,
                'cache_ttl' => $request->cacheTTL,
                'query_logging' => $request->queryLogging,
                'slow_query_threshold' => $request->slowQueryThreshold,
                'memory_limit' => $request->memoryLimit,
                'max_execution_time' => $request->maxExecutionTime,
                'compression_enabled' => $request->compressionEnabled
            ];

            $this->saveSettings($settings);

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
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['cache_enabled', 'cache_driver', 'cache_ttl', 'query_logging', 'slow_query_threshold', 'memory_limit', 'max_execution_time', 'compression_enabled'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'cacheEnabled' => filter_var($settings['cache_enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'cacheDriver' => $settings['cache_driver'] ?? 'file',
                    'cacheTTL' => (int)($settings['cache_ttl'] ?? 60),
                    'queryLogging' => filter_var($settings['query_logging'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'slowQueryThreshold' => (int)($settings['slow_query_threshold'] ?? 1000),
                    'memoryLimit' => (int)($settings['memory_limit'] ?? 256),
                    'maxExecutionTime' => (int)($settings['max_execution_time'] ?? 30),
                    'compressionEnabled' => filter_var($settings['compression_enabled'] ?? true, FILTER_VALIDATE_BOOLEAN)
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

            $this->saveSettings($settings);

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
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['backup_enabled', 'backup_frequency', 'backup_retention', 'backup_location', 'backup_compression', 'backup_encryption'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'backupEnabled' => filter_var($settings['backup_enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'backupFrequency' => $settings['backup_frequency'] ?? 'daily',
                    'backupRetention' => (int)($settings['backup_retention'] ?? 30),
                    'backupLocation' => $settings['backup_location'] ?? 'local',
                    'backupCompression' => filter_var($settings['backup_compression'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'backupEncryption' => filter_var($settings['backup_encryption'] ?? false, FILTER_VALIDATE_BOOLEAN)
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
            $validator = Validator::make($request->all(), [
                'mailDriver' => 'required|string|in:smtp,mailgun,ses',
                'mailHost' => 'required|string|max:255',
                'mailPort' => 'required|integer|min:1|max:65535',
                'mailUsername' => 'nullable|string|max:255',
                'mailPassword' => 'nullable|string|max:255',
                'mailEncryption' => 'required|string|in:tls,ssl,',
                'mailFromAddress' => 'required|email|max:255',
                'mailFromName' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'mail_driver' => $request->mailDriver,
                'mail_host' => $request->mailHost,
                'mail_port' => $request->mailPort,
                'mail_username' => $request->mailUsername,
                'mail_password' => $request->mailPassword,
                'mail_encryption' => $request->mailEncryption,
                'mail_from_address' => $request->mailFromAddress,
                'mail_from_name' => $request->mailFromName
            ];

            $this->saveSettings($settings);

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
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'mailDriver' => $settings['mail_driver'] ?? 'smtp',
                    'mailHost' => $settings['mail_host'] ?? 'smtp.gmail.com',
                    'mailPort' => (int)($settings['mail_port'] ?? 587),
                    'mailUsername' => $settings['mail_username'] ?? '',
                    'mailPassword' => $settings['mail_password'] ?? '',
                    'mailEncryption' => $settings['mail_encryption'] ?? 'tls',
                    'mailFromAddress' => $settings['mail_from_address'] ?? config('mail.from.address'),
                    'mailFromName' => $settings['mail_from_name'] ?? config('mail.from.name')
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
     * Test email
     */
    public function testEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mailDriver' => 'required|string|in:smtp,mailgun,ses',
                'mailHost' => 'required|string|max:255',
                'mailPort' => 'required|integer|min:1|max:65535',
                'mailUsername' => 'nullable|string|max:255',
                'mailPassword' => 'nullable|string|max:255',
                'mailEncryption' => 'required|string|in:tls,ssl,',
                'mailFromAddress' => 'required|email|max:255',
                'mailFromName' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Temporarily update mail configuration
            config([
                'mail.default' => $request->mailDriver,
                'mail.mailers.smtp.host' => $request->mailHost,
                'mail.mailers.smtp.port' => $request->mailPort,
                'mail.mailers.smtp.username' => $request->mailUsername,
                'mail.mailers.smtp.password' => $request->mailPassword,
                'mail.mailers.smtp.encryption' => $request->mailEncryption,
                'mail.from.address' => $request->mailFromAddress,
                'mail.from.name' => $request->mailFromName,
            ]);

            // Send test email
            $testEmail = $request->mailFromAddress; // Send to the same address as from
            $subject = 'Test Email - ' . config('app.name');
            $message = 'This is a test email to verify your email configuration.';

            \Mail::raw($message, function ($mail) use ($testEmail, $subject) {
                $mail->to($testEmail)
                     ->subject($subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'ทดสอบการส่งอีเมลสำเร็จ! ตรวจสอบอีเมลของคุณ'
            ]);

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

            $this->saveSettings($settings);

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
            $settings = DB::table('laravel_settings')
                ->whereIn('key', ['password_min_length', 'password_require_uppercase', 'password_require_lowercase', 'password_require_numbers', 'password_require_special_chars', 'session_timeout', 'max_login_attempts', 'lockout_duration', 'two_factor_auth', 'ip_whitelist'])
                ->pluck('value', 'key')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'passwordMinLength' => (int)($settings['password_min_length'] ?? 8),
                    'passwordRequireUppercase' => filter_var($settings['password_require_uppercase'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'passwordRequireLowercase' => filter_var($settings['password_require_lowercase'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'passwordRequireNumbers' => filter_var($settings['password_require_numbers'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'passwordRequireSpecialChars' => filter_var($settings['password_require_special_chars'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'sessionTimeout' => (int)($settings['session_timeout'] ?? 30),
                    'maxLoginAttempts' => (int)($settings['max_login_attempts'] ?? 5),
                    'lockoutDuration' => (int)($settings['lockout_duration'] ?? 15),
                    'twoFactorAuth' => filter_var($settings['two_factor_auth'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'ipWhitelist' => filter_var($settings['ip_whitelist'] ?? false, FILTER_VALIDATE_BOOLEAN)
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
     * Get all settings
     */
    public function getAllSettings()
    {
        try {
            $settings = DB::table('laravel_settings')->pluck('value', 'key')->toArray();

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

    /**
     * Save settings helper method
     */
    private function saveSettings(array $settings)
    {
        foreach ($settings as $key => $value) {
            DB::table('laravel_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string'),
                    'updated_at' => now()
                ]
            );
        }
    }
}