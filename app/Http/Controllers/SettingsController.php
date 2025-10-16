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
                'auditEnabled' => 'nullable|boolean',
                'auditRetention' => 'nullable|integer|min:3|max:365',
                'auditLevel' => 'nullable|string|in:basic,detailed,comprehensive'
            ]);

            if ($validator->fails()) {
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'audit_enabled' => $request->boolean('auditEnabled', true),
                'audit_retention' => $request->input('auditRetention', 90),
                'audit_level' => $request->input('auditLevel', 'basic')
            ];

            // Save each setting to database using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            // Log the settings change
            \App\Models\AuditLog::createLog([
                'user_id' => session('admin_user_id'),
                'user_email' => session('admin_user_email'),
                'action' => 'settings_update',
                'description' => 'อัปเดตการตั้งค่า Audit Log',
                'old_values' => [
                    'audit_enabled' => SettingsHelper::get('audit_enabled', true),
                    'audit_retention' => SettingsHelper::get('audit_retention', 90),
                    'audit_level' => SettingsHelper::get('audit_level', 'basic')
                ],
                'new_values' => $settings,
                'status' => 'success'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่า Audit Log สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::saveAuditSettings'
            );
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
                    'auditEnabled' => (bool)($settings['audit_enabled'] ?? true),
                    'auditRetention' => (int)($settings['audit_retention'] ?? 90),
                    'auditLevel' => $settings['audit_level'] ?? 'basic'
                ]
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getAuditSettings'
            );
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
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::saveAuditSettings'
            );
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getAuditSettings'
            );
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
                'memoryUsage' => $this->getSystemMemoryUsage(),
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดข้อมูลระบบได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getSystemInfo'
            );
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

    private function getSystemMemoryUsage()
    {
        try {
            // Try to get system memory info from /proc/meminfo (Linux)
            if (is_readable('/proc/meminfo')) {
                $meminfo = file_get_contents('/proc/meminfo');
                preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $total);
                preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $available);
                
                if (isset($total[1]) && isset($available[1])) {
                    $totalKB = (int)$total[1];
                    $availableKB = (int)$available[1];
                    $usedKB = $totalKB - $availableKB;
                    
                    return $this->formatBytes($usedKB * 1024) . ' / ' . $this->formatBytes($totalKB * 1024);
                }
            }
            
            // Fallback for Windows or other systems
            if (function_exists('shell_exec')) {
                $output = shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value 2>nul');
                if ($output) {
                    preg_match('/TotalVisibleMemorySize=(\d+)/', $output, $total);
                    preg_match('/FreePhysicalMemory=(\d+)/', $output, $free);
                    
                    if (isset($total[1]) && isset($free[1])) {
                        $totalKB = (int)$total[1];
                        $freeKB = (int)$free[1];
                        $usedKB = $totalKB - $freeKB;
                        
                        return $this->formatBytes($usedKB * 1024) . ' / ' . $this->formatBytes($totalKB * 1024);
                    }
                }
            }
            
            // Final fallback - show PHP memory usage
            $phpMemory = memory_get_usage(true);
            $phpPeak = memory_get_peak_usage(true);
            return 'PHP: ' . $this->formatBytes($phpMemory) . ' (Peak: ' . $this->formatBytes($phpPeak) . ')';
            
        } catch (\Exception $e) {
            // Fallback to PHP memory usage
            $phpMemory = memory_get_usage(true);
            return 'PHP: ' . $this->formatBytes($phpMemory);
        }
    }

    private function calculateMemoryUsagePercent()
    {
        try {
            // Try to get system memory info from /proc/meminfo (Linux)
            if (is_readable('/proc/meminfo')) {
                $meminfo = file_get_contents('/proc/meminfo');
                preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $total);
                preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $available);
                
                if (isset($total[1]) && isset($available[1])) {
                    $totalKB = (int)$total[1];
                    $availableKB = (int)$available[1];
                    $usedKB = $totalKB - $availableKB;
                    
                    return round(($usedKB / $totalKB) * 100, 1);
                }
            }
            
            // Fallback for Windows or other systems
            if (function_exists('shell_exec')) {
                $output = shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value 2>nul');
                if ($output) {
                    preg_match('/TotalVisibleMemorySize=(\d+)/', $output, $total);
                    preg_match('/FreePhysicalMemory=(\d+)/', $output, $free);
                    
                    if (isset($total[1]) && isset($free[1])) {
                        $totalKB = (int)$total[1];
                        $freeKB = (int)$free[1];
                        $usedKB = $totalKB - $freeKB;
                        
                        return round(($usedKB / $totalKB) * 100, 1);
                    }
                }
            }
            
            // Final fallback - calculate from PHP memory limit
            $memoryLimit = ini_get('memory_limit');
            $memoryLimitBytes = $this->parseMemoryLimit($memoryLimit);
            $memoryUsage = memory_get_usage(true);
            
            if ($memoryLimitBytes > 0) {
                return round(($memoryUsage / $memoryLimitBytes) * 100, 1);
            }
            
            return 0;
            
        } catch (\Exception $e) {
            // Fallback to PHP memory calculation
            $memoryLimit = ini_get('memory_limit');
            $memoryLimitBytes = $this->parseMemoryLimit($memoryLimit);
            $memoryUsage = memory_get_usage(true);
            
            if ($memoryLimitBytes > 0) {
                return round(($memoryUsage / $memoryLimitBytes) * 100, 1);
            }
            
            return 0;
        }
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
        try {
            // Get disk usage for the storage directory
            $storagePath = storage_path();
            $totalBytes = disk_total_space($storagePath);
            $freeBytes = disk_free_space($storagePath);
            
            if ($totalBytes && $freeBytes) {
                $usedBytes = $totalBytes - $freeBytes;
                return $this->formatBytes($usedBytes) . ' / ' . $this->formatBytes($totalBytes);
            }
            
            // Fallback: try to get system disk usage
            if (function_exists('shell_exec')) {
                if (PHP_OS_FAMILY === 'Windows') {
                    // Windows: get disk usage using wmic
                    $output = shell_exec('wmic logicaldisk get size,freespace,caption /value 2>nul');
                    if ($output) {
                        $lines = explode("\n", $output);
                        $totalSize = 0;
                        $totalFree = 0;
                        
                        foreach ($lines as $line) {
                            if (strpos($line, 'Size=') === 0) {
                                $totalSize += (int)substr($line, 5);
                            } elseif (strpos($line, 'FreeSpace=') === 0) {
                                $totalFree += (int)substr($line, 10);
                            }
                        }
                        
                        if ($totalSize > 0) {
                            $totalUsed = $totalSize - $totalFree;
                            return $this->formatBytes($totalUsed) . ' / ' . $this->formatBytes($totalSize);
                        }
                    }
                } else {
                    // Linux: get disk usage using df command
                    $output = shell_exec('df -h / 2>/dev/null');
                    if ($output) {
                        $lines = explode("\n", $output);
                        if (isset($lines[1])) {
                            $parts = preg_split('/\s+/', $lines[1]);
                            if (count($parts) >= 4) {
                                $total = $parts[1];
                                $used = $parts[2];
                                return $used . ' / ' . $total;
                            }
                        }
                    }
                }
            }
            
            return 'Unknown';
            
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function calculateDiskUsagePercent()
    {
        try {
            // Get disk usage for the storage directory
            $storagePath = storage_path();
            $totalBytes = disk_total_space($storagePath);
            $freeBytes = disk_free_space($storagePath);
            
            if ($totalBytes && $freeBytes) {
                $usedBytes = $totalBytes - $freeBytes;
                return round(($usedBytes / $totalBytes) * 100, 1);
            }
            
            // Fallback: try to get system disk usage percentage
            if (function_exists('shell_exec')) {
                if (PHP_OS_FAMILY === 'Windows') {
                    // Windows: get disk usage using wmic
                    $output = shell_exec('wmic logicaldisk get size,freespace,caption /value 2>nul');
                    if ($output) {
                        $lines = explode("\n", $output);
                        $totalSize = 0;
                        $totalFree = 0;
                        
                        foreach ($lines as $line) {
                            if (strpos($line, 'Size=') === 0) {
                                $totalSize += (int)substr($line, 5);
                            } elseif (strpos($line, 'FreeSpace=') === 0) {
                                $totalFree += (int)substr($line, 10);
                            }
                        }
                        
                        if ($totalSize > 0) {
                            $totalUsed = $totalSize - $totalFree;
                            return round(($totalUsed / $totalSize) * 100, 1);
                        }
                    }
                } else {
                    // Linux: get disk usage using df command
                    $output = shell_exec('df / 2>/dev/null | tail -1');
                    if ($output) {
                        $parts = preg_split('/\s+/', trim($output));
                        if (count($parts) >= 5) {
                            $totalKB = (int)$parts[1];
                            $usedKB = (int)$parts[2];
                            
                            if ($totalKB > 0) {
                                return round(($usedKB / $totalKB) * 100, 1);
                            }
                        }
                    }
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCpuLoad()
    {
        try {
            // Try Linux /proc/loadavg first
            if (is_readable('/proc/loadavg')) {
                $loadavg = file_get_contents('/proc/loadavg');
                $loads = explode(' ', trim($loadavg));
                if (isset($loads[0])) {
                    return round((float)$loads[0], 2);
                }
            }
            
            // Try Windows WMI
            if (function_exists('shell_exec')) {
                $output = shell_exec('wmic cpu get loadpercentage /value 2>nul');
                if ($output) {
                    preg_match('/LoadPercentage=(\d+)/', $output, $matches);
                    if (isset($matches[1])) {
                        return round((float)$matches[1], 2);
                    }
                }
                
                // Alternative Windows method using PowerShell
                $psOutput = shell_exec('powershell "Get-WmiObject -Class Win32_Processor | Select-Object -ExpandProperty LoadPercentage" 2>nul');
                if ($psOutput && is_numeric(trim($psOutput))) {
                    return round((float)trim($psOutput), 2);
                }
            }
            
            // Try sys_getloadavg() if available
            if (function_exists('sys_getloadavg')) {
                $load = sys_getloadavg();
                if (isset($load[0])) {
                    return round($load[0], 2);
                }
            }
            
            // Fallback - try to estimate from process count
            if (function_exists('shell_exec')) {
                if (PHP_OS_FAMILY === 'Windows') {
                    $processCount = shell_exec('tasklist /fo csv | find /c /v ""');
                } else {
                    $processCount = shell_exec('ps aux | wc -l');
                }
                
                if ($processCount) {
                    $count = (int)trim($processCount);
                    // Rough estimation: more processes = higher load
                    if ($count > 200) return 'High';
                    if ($count > 100) return 'Medium';
                    return 'Low';
                }
            }
            
            return 'N/A';
            
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getActiveUsers()
    {
        try {
            // Method 1: Count database sessions (Laravel sessions)
            try {
                $activeUsers = DB::table('sessions')
                    ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
                    ->count();
                
                if ($activeUsers > 0) {
                    return $activeUsers;
                }
            } catch (\Exception $e) {
                // Database sessions table might not exist
            }
            
            // Method 2: Count file-based sessions
            try {
                $sessionPath = session_save_path();
                if (empty($sessionPath)) {
                    $sessionPath = sys_get_temp_dir();
                }
                
                if (is_dir($sessionPath)) {
                    $sessionFiles = glob($sessionPath . '/sess_*');
                    $activeCount = 0;
                    $cutoffTime = time() - (30 * 60); // 30 minutes ago
                    
                    foreach ($sessionFiles as $file) {
                        if (filemtime($file) > $cutoffTime) {
                            $activeCount++;
                        }
                    }
                    
                    if ($activeCount > 0) {
                        return $activeCount;
                    }
                }
            } catch (\Exception $e) {
                // File sessions might not be accessible
            }
            
            // Method 3: Count system users (Linux/Unix)
            if (function_exists('shell_exec') && PHP_OS_FAMILY !== 'Windows') {
                try {
                    // Count logged-in users
                    $users = shell_exec('who | wc -l');
                    if ($users && is_numeric(trim($users))) {
                        return (int)trim($users);
                    }
                    
                    // Alternative: count unique users
                    $uniqueUsers = shell_exec('who | cut -d" " -f1 | sort -u | wc -l');
                    if ($uniqueUsers && is_numeric(trim($uniqueUsers))) {
                        return (int)trim($uniqueUsers);
                    }
                } catch (\Exception $e) {
                    // System commands might not be available
                }
            }
            
            // Method 4: Count Windows logged-in users
            if (function_exists('shell_exec') && PHP_OS_FAMILY === 'Windows') {
                try {
                    $users = shell_exec('query user 2>nul');
                    if ($users) {
                        $lines = explode("\n", $users);
                        $userCount = 0;
                        foreach ($lines as $line) {
                            if (strpos($line, 'Active') !== false || strpos($line, 'Disc') !== false) {
                                $userCount++;
                            }
                        }
                        if ($userCount > 0) {
                            return $userCount;
                        }
                    }
                } catch (\Exception $e) {
                    // Windows commands might not be available
                }
            }
            
            // Method 5: Cache-based fallback
            try {
                $activeUsers = Cache::get('active_users_count', 0);
                return $activeUsers;
            } catch (\Exception $e) {
                return 'Unknown';
            }
            
        } catch (\Exception $e) {
            return 'Unknown';
        }
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
        try {
            // Check for backup files in storage/backups directory
            $backupPath = storage_path('backups');
            if (is_dir($backupPath)) {
                $files = glob($backupPath . '/*.sql');
                if (!empty($files)) {
                    $latestFile = max($files);
                    $lastModified = filemtime($latestFile);
                    return date('Y-m-d H:i:s', $lastModified);
                }
            }
            
            // Check for backup files in storage/app/backups
            $appBackupPath = storage_path('app/backups');
            if (is_dir($appBackupPath)) {
                $files = glob($appBackupPath . '/*');
                if (!empty($files)) {
                    $latestFile = max($files);
                    $lastModified = filemtime($latestFile);
                    return date('Y-m-d H:i:s', $lastModified);
                }
            }
            
            return 'ไม่มีการสำรองข้อมูล';
        } catch (\Exception $e) {
            return 'Unknown';
        }
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
        try {
            // Try to get application start time from cache
            $startTime = Cache::get('app_start_time');
            if (!$startTime) {
                // Set start time if not exists
                $startTime = time();
                Cache::put('app_start_time', $startTime, now()->addDays(1));
            }
            return $startTime;
        } catch (\Exception $e) {
            // Fallback to a reasonable default
            return time() - 3600; // Assume 1 hour uptime
        }
    }

    /**
     * Export system information
     */
    public function exportSystemInfo()
    {
        try {
            // Get system info data
            $systemInfoResponse = $this->getSystemInfo();
            $systemInfoData = json_decode($systemInfoResponse->getContent(), true);
            
            if (!$systemInfoData['success']) {
                throw new \Exception('ไม่สามารถโหลดข้อมูลระบบได้');
            }
            
            $data = $systemInfoData['data'];
            
            // Create export filename
            $filename = 'system_info_' . date('Y-m-d_H-i-s') . '.json';
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            
            // Write data to temporary file
            file_put_contents($tempPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถส่งออกข้อมูลระบบได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::exportSystemInfo'
            );
        }
    }

    /**
     * Get log file content
     */
    public function getLogFile($filename)
    {
        try {
            // Validate filename to prevent directory traversal
            $allowedFiles = ['laravel.log', 'error.log', 'access.log'];
            if (!in_array($filename, $allowedFiles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไฟล์ log ที่ระบุไม่ได้รับอนุญาต'
                ], 403);
            }

            $logPath = storage_path('logs/' . $filename);
            
            if (!file_exists($logPath)) {
                // Return empty content for non-existent files instead of 404
                return response()->json([
                    'success' => true,
                    'data' => [
                        'content' => "ไม่มีไฟล์ {$filename} ในระบบ\n\nไฟล์ log นี้ยังไม่ได้ถูกสร้างขึ้น หรือไม่มีข้อมูลในไฟล์นี้"
                    ]
                ]);
            }

            $content = file_get_contents($logPath);
            
            // Check if file is empty
            if (empty(trim($content))) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'content' => "ไฟล์ {$filename} ว่างเปล่า\n\nยังไม่มีข้อมูล log ในไฟล์นี้"
                    ]
                ]);
            }

            $lines = explode("\n", $content);
            $lastLines = array_slice($lines, -100); // Get last 100 lines

            return response()->json([
                'success' => true,
                'data' => [
                    'content' => implode("\n", $lastLines)
                ]
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอ่านไฟล์ log ได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getLogFile'
            );
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
            $skippedFiles = [];

            foreach ($logFiles as $file) {
                $logPath = storage_path('logs/' . $file);
                if (file_exists($logPath)) {
                    file_put_contents($logPath, '');
                    $clearedFiles[] = $file;
                } else {
                    $skippedFiles[] = $file;
                }
            }

            $message = 'ล้าง Logs สำเร็จ';
            if (count($clearedFiles) > 0) {
                $message .= ' (' . implode(', ', $clearedFiles) . ')';
            }
            if (count($skippedFiles) > 0) {
                $message .= ' - ข้ามไฟล์ที่ไม่มีอยู่: ' . implode(', ', $skippedFiles);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถล้าง Logs ได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::clearLogs'
            );
        }
    }

    /**
     * Download log files
     */
    public function downloadLogs()
    {
        try {
            $logFiles = ['laravel.log', 'error.log', 'access.log'];
            $tempDir = sys_get_temp_dir();
            $zipFileName = 'system_logs_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = $tempDir . '/' . $zipFileName;
            
            // Create ZIP file
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('ไม่สามารถสร้างไฟล์ ZIP ได้');
            }
            
            $addedFiles = 0;
            foreach ($logFiles as $file) {
                $logPath = storage_path('logs/' . $file);
                if (file_exists($logPath)) {
                    $zip->addFile($logPath, $file);
                    $addedFiles++;
                } else {
                    // Add empty file with message for non-existent files
                    $zip->addFromString($file, "ไม่มีไฟล์ {$file} ในระบบ\n\nไฟล์ log นี้ยังไม่ได้ถูกสร้างขึ้น หรือไม่มีข้อมูลในไฟล์นี้");
                    $addedFiles++;
                }
            }
            
            $zip->close();
            
            if ($addedFiles === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบไฟล์ logs สำหรับดาวน์โหลด'
                ], 404);
            }
            
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถดาวน์โหลด Logs ได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::downloadLogs'
            );
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
     * Check for available updates
     */
    public function checkForUpdates(Request $request)
    {
        try {
            $updateChannel = $request->input('channel', 'stable');
            
            // Validate channel
            if (!in_array($updateChannel, ['stable', 'beta', 'dev'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'ช่องทางการอัปเดตไม่ถูกต้อง'
                ], 400);
            }

            // Get current Laravel version
            $currentVersion = app()->version();
            
            // Simulate checking for updates based on channel
            $updateInfo = $this->getUpdateInfo($currentVersion, $updateChannel);
            
            return response()->json([
                'success' => true,
                'hasUpdates' => $updateInfo['hasUpdates'],
                'currentVersion' => $currentVersion,
                'latestVersion' => $updateInfo['latestVersion'],
                'channel' => $updateChannel,
                'updateInfo' => $updateInfo
            ]);

        } catch (\Exception $e) {
            \Log::error('Check for updates error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการตรวจสอบการอัปเดต'
            ], 500);
        }
    }

    /**
     * Get update information based on channel
     */
    private function getUpdateInfo($currentVersion, $channel)
    {
        try {
            switch ($channel) {
                case 'stable':
                    return $this->checkStableUpdates($currentVersion);
                    
                case 'beta':
                    return $this->checkBetaUpdates($currentVersion);
                    
                case 'dev':
                    return $this->checkDevUpdates($currentVersion);
                    
                default:
                    return [
                        'hasUpdates' => false,
                        'latestVersion' => $currentVersion,
                        'channel' => $channel,
                        'description' => 'ไม่ทราบช่องทาง',
                        'releaseNotes' => 'ไม่มีข้อมูลการอัปเดต'
                    ];
            }
        } catch (\Exception $e) {
            \Log::error('Error checking updates for channel ' . $channel . ': ' . $e->getMessage());
            
            // Fallback to simulation if API fails
            return $this->getSimulatedUpdateInfo($currentVersion, $channel);
        }
    }

    /**
     * Check stable updates from Packagist
     */
    private function checkStableUpdates($currentVersion)
    {
        try {
            $response = \Http::timeout(10)->get('https://packagist.org/packages/laravel/framework.json');
            
            if ($response->successful()) {
                $data = $response->json();
                $versions = $data['package']['versions'] ?? [];
                
                // Get latest stable version (not dev, not alpha, not beta)
                $stableVersions = array_filter($versions, function($version) {
                    return !str_contains($version['version'], 'dev') && 
                           !str_contains($version['version'], 'alpha') && 
                           !str_contains($version['version'], 'beta') &&
                           !str_contains($version['version'], 'rc');
                });
                
                if (!empty($stableVersions)) {
                    $latestVersion = array_key_first($stableVersions);
                    $hasUpdates = version_compare($currentVersion, $latestVersion, '<');
                    
                    return [
                        'hasUpdates' => $hasUpdates,
                        'latestVersion' => $latestVersion,
                        'channel' => 'stable',
                        'description' => 'เวอร์ชันเสถียรที่แนะนำสำหรับการใช้งานทั่วไป',
                        'releaseNotes' => $stableVersions[$latestVersion]['description'] ?? 'ปรับปรุงประสิทธิภาพและแก้ไขข้อบกพร่อง',
                        'source' => 'Packagist API'
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to check stable updates from Packagist: ' . $e->getMessage());
        }
        
        // Fallback to simulation
        return $this->getSimulatedUpdateInfo($currentVersion, 'stable');
    }

    /**
     * Check beta updates from GitHub
     */
    private function checkBetaUpdates($currentVersion)
    {
        try {
            $response = \Http::timeout(10)->get('https://api.github.com/repos/laravel/framework/releases?per_page=20');
            
            if ($response->successful()) {
                $releases = $response->json();
                
                // Find latest beta/pre-release
                $betaReleases = array_filter($releases, function($release) {
                    return $release['prerelease'] === true || 
                           str_contains($release['tag_name'], 'beta') || 
                           str_contains($release['tag_name'], 'rc');
                });
                
                if (!empty($betaReleases)) {
                    $latestRelease = $betaReleases[0];
                    $latestVersion = ltrim($latestRelease['tag_name'], 'v');
                    $hasUpdates = version_compare($currentVersion, $latestVersion, '<');
                    
                    return [
                        'hasUpdates' => $hasUpdates,
                        'latestVersion' => $latestVersion,
                        'channel' => 'beta',
                        'description' => 'เวอร์ชันทดสอบที่มีฟีเจอร์ใหม่แต่ยังไม่เสถียร',
                        'releaseNotes' => $latestRelease['body'] ?? 'ฟีเจอร์ใหม่ที่กำลังทดสอบ',
                        'source' => 'GitHub API'
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to check beta updates from GitHub: ' . $e->getMessage());
        }
        
        // Fallback to simulation
        return $this->getSimulatedUpdateInfo($currentVersion, 'beta');
    }

    /**
     * Check development updates from GitHub
     */
    private function checkDevUpdates($currentVersion)
    {
        try {
            $response = \Http::timeout(10)->get('https://api.github.com/repos/laravel/framework/commits?per_page=1');
            
            if ($response->successful()) {
                $commits = $response->json();
                
                if (!empty($commits)) {
                    $latestCommit = $commits[0];
                    $latestVersion = 'dev-' . substr($latestCommit['sha'], 0, 7);
                    
                    // For dev, we'll consider it as "newer" if the commit is recent
                    $commitDate = \Carbon\Carbon::parse($latestCommit['commit']['committer']['date']);
                    $hasUpdates = $commitDate->isAfter(\Carbon\Carbon::now()->subDays(7)); // Updates if commit is within 7 days
                    
                    return [
                        'hasUpdates' => $hasUpdates,
                        'latestVersion' => $latestVersion,
                        'channel' => 'dev',
                        'description' => 'เวอร์ชันพัฒนาที่มีฟีเจอร์ล่าสุดแต่อาจมีข้อบกพร่อง',
                        'releaseNotes' => $latestCommit['commit']['message'] ?? 'การเปลี่ยนแปลงล่าสุดในโค้ด',
                        'source' => 'GitHub Commits API'
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to check dev updates from GitHub: ' . $e->getMessage());
        }
        
        // Fallback to simulation
        return $this->getSimulatedUpdateInfo($currentVersion, 'dev');
    }

    /**
     * Fallback simulation when APIs are not available
     */
    private function getSimulatedUpdateInfo($currentVersion, $channel)
    {
        $baseVersion = '11.0.0';
        
        switch ($channel) {
            case 'stable':
                $latestVersion = '11.2.0';
                $hasUpdates = version_compare($currentVersion, $latestVersion, '<');
                break;
                
            case 'beta':
                $latestVersion = '11.3.0-beta.1';
                $hasUpdates = version_compare($currentVersion, '11.2.0', '<');
                break;
                
            case 'dev':
                $latestVersion = '11.4.0-dev';
                $hasUpdates = version_compare($currentVersion, '11.1.0', '<');
                break;
                
            default:
                $latestVersion = $currentVersion;
                $hasUpdates = false;
        }
        
        return [
            'hasUpdates' => $hasUpdates,
            'latestVersion' => $latestVersion,
            'channel' => $channel,
            'description' => $this->getChannelDescription($channel),
            'releaseNotes' => $this->getReleaseNotes($latestVersion, $channel),
            'source' => 'Simulation (API unavailable)'
        ];
    }

    /**
     * Get channel description
     */
    private function getChannelDescription($channel)
    {
        $descriptions = [
            'stable' => 'เวอร์ชันเสถียรที่แนะนำสำหรับการใช้งานทั่วไป',
            'beta' => 'เวอร์ชันทดสอบที่มีฟีเจอร์ใหม่แต่ยังไม่เสถียร',
            'dev' => 'เวอร์ชันพัฒนาที่มีฟีเจอร์ล่าสุดแต่อาจมีข้อบกพร่อง'
        ];
        
        return $descriptions[$channel] ?? 'ไม่ทราบช่องทาง';
    }

    /**
     * Get release notes
     */
    private function getReleaseNotes($version, $channel)
    {
        $notes = [
            'stable' => [
                '11.2.0' => 'ปรับปรุงประสิทธิภาพ, แก้ไขข้อบกพร่อง, เพิ่มฟีเจอร์ใหม่'
            ],
            'beta' => [
                '11.3.0-beta.1' => 'ฟีเจอร์ใหม่ที่กำลังทดสอบ, อาจมีข้อบกพร่อง'
            ],
            'dev' => [
                '11.4.0-dev' => 'ฟีเจอร์ล่าสุดที่กำลังพัฒนา, ไม่แนะนำสำหรับการใช้งานจริง'
            ]
        ];
        
        return $notes[$channel][$version] ?? 'ไม่มีข้อมูลการอัปเดต';
    }

    /**
     * Get update settings
     */
    public function getUpdateSettings()
    {
        try {
            $settings = SettingsHelper::getMultiple([
                'auto_update',
                'update_channel', 
                'backup_before_update',
                'notify_on_update'
            ]);

            // Set default values if not exists
            $defaultSettings = [
                'auto_update' => true,
                'update_channel' => 'stable',
                'backup_before_update' => true,
                'notify_on_update' => true
            ];

            $settings = array_merge($defaultSettings, $settings);

            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            \Log::error('Get update settings error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการโหลดการตั้งค่าอัปเดต'
            ], 500);
        }
    }

    /**
     * Save update settings
     */
    public function saveUpdateSettings(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'autoUpdate' => 'required|boolean',
                'updateChannel' => 'required|string|in:stable,beta,dev',
                'backupBeforeUpdate' => 'required|boolean',
                'notifyOnUpdate' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Update settings validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $settings = [
                'auto_update' => $request->autoUpdate,
                'update_channel' => $request->updateChannel,
                'backup_before_update' => $request->backupBeforeUpdate,
                'notify_on_update' => $request->notifyOnUpdate
            ];

            // Save each setting to database using SettingsHelper
            foreach ($settings as $key => $value) {
                SettingsHelper::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่าอัปเดตสำเร็จ'
            ]);

        } catch (\Exception $e) {
            \Log::error('Update settings save error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่าอัปเดต'
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
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::saveAuditSettings'
            );
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getAuditSettings'
            );
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
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::saveAuditSettings'
            );
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getAuditSettings'
            );
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
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::saveAuditSettings'
            );
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
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
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
                \Log::error('Audit settings validation failed:', $validator->errors()->toArray());
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::saveAuditSettings'
            );
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getAuditSettings'
            );
        }
    }

    /**
     * Get audit logs with pagination
     */
    public function getAuditLogs(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);
            
            // Get audit logs from database with pagination
            $auditLogs = \App\Models\AuditLog::orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            
            $formattedLogs = $auditLogs->map(function ($log) {
                // Get user name from user_email if user_id is not available
                $userName = 'ไม่ทราบ';
                if ($log->user_id && $log->user) {
                    $userName = $log->user->name;
                } elseif ($log->user_email) {
                    // Try to get user name from email
                    $user = \App\Models\User::where('email', $log->user_email)->first();
                    $userName = $user ? $user->name : $log->user_email;
                }

                return [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'user_name' => $userName,
                    'action' => $this->getFormattedAction($log->action),
                    'description' => $log->description,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'status' => $log->status,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedLogs,
                'pagination' => [
                    'current_page' => $auditLogs->currentPage(),
                    'last_page' => $auditLogs->lastPage(),
                    'per_page' => $auditLogs->perPage(),
                    'total' => $auditLogs->total(),
                    'from' => $auditLogs->firstItem(),
                    'to' => $auditLogs->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting audit logs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get formatted action name
     */
    private function getFormattedAction($action)
    {
        $actions = [
            'login' => 'เข้าสู่ระบบ',
            'logout' => 'ออกจากระบบ',
            'create' => 'สร้างข้อมูล',
            'update' => 'แก้ไขข้อมูล',
            'delete' => 'ลบข้อมูล',
            'view' => 'ดูข้อมูล',
            'export' => 'ส่งออกข้อมูล',
            'import' => 'นำเข้าข้อมูล',
            'settings_update' => 'แก้ไขการตั้งค่า',
            'password_change' => 'เปลี่ยนรหัสผ่าน',
            'profile_update' => 'แก้ไขโปรไฟล์',
            'audit_clear' => 'ล้าง Audit Logs',
            'user_create' => 'สร้างผู้ใช้',
            'user_update' => 'แก้ไขผู้ใช้',
            'user_delete' => 'ลบผู้ใช้',
            'role_create' => 'สร้างบทบาท',
            'role_update' => 'แก้ไขบทบาท',
            'role_delete' => 'ลบบทบาท',
            'permission_create' => 'สร้างสิทธิ์',
            'permission_update' => 'แก้ไขสิทธิ์',
            'permission_delete' => 'ลบสิทธิ์'
        ];

        return $actions[$action] ?? $action;
    }

    /**
     * Export audit logs
     */
    public function exportAuditLogs()
    {
        try {
            // Get audit logs from database
            $auditLogs = \App\Models\AuditLog::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'user_name' => $log->user ? $log->user->name : ($log->user_email ?? 'ไม่ทราบ'),
                        'action' => $log->getFormattedActionAttribute(),
                        'description' => $log->description,
                        'ip_address' => $log->ip_address,
                        'status' => $log->getFormattedStatusAttribute(),
                        'created_at' => $log->created_at->format('Y-m-d H:i:s')
                    ];
                });

            // Create CSV content with UTF-8 BOM for Thai language support
            $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
            
            // Start CSV content with UTF-8 BOM
            $csvContent = "\xEF\xBB\xBF";
            
            // Add CSV headers
            $csvContent .= "ID,ผู้ใช้,การกระทำ,รายละเอียด,IP Address,สถานะ,วันที่\n";
            
            // Add data rows
            foreach ($auditLogs as $log) {
                $csvContent .= sprintf(
                    "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                    $log['id'],
                    str_replace('"', '""', $log['user_name']), // Escape quotes
                    str_replace('"', '""', $log['action']),
                    str_replace('"', '""', $log['description'] ?? ''),
                    str_replace('"', '""', $log['ip_address'] ?? ''),
                    str_replace('"', '""', $log['status']),
                    str_replace('"', '""', $log['created_at'])
                );
            }

            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Pragma', 'no-cache');

        } catch (\Exception $e) {
            \Log::error('Error exporting audit logs: ' . $e->getMessage());
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
            // Get retention setting from database
            $retentionDays = SettingsHelper::get('audit_retention', 90);
            
            // Calculate cutoff date
            $cutoffDate = now()->subDays($retentionDays);
            
            // Delete old audit logs
            $deletedCount = \App\Models\AuditLog::where('created_at', '<', $cutoffDate)->delete();
            
            // Log the clearing action
            \App\Models\AuditLog::createLog([
                'user_id' => session('admin_user_id'),
                'user_email' => session('admin_user_email'),
                'action' => 'audit_clear',
                'description' => "ล้าง Audit Logs เก่ากว่า {$retentionDays} วัน",
                'status' => 'success'
            ]);

            return response()->json([
                'success' => true,
                'message' => "ล้าง Audit Logs สำเร็จ (ลบ {$deletedCount} รายการ)",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Error clearing audit logs: ' . $e->getMessage());
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
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถโหลดการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
                'SettingsController::getAuditSettings'
            );
        }
    }
}