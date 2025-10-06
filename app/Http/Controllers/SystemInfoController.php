<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SystemInfoController extends Controller
{
    public function updateTimezone(Request $request)
    {
        try {
            $request->validate([
                'timezone' => 'required|string|max:50'
            ]);
            
            $timezone = $request->input('timezone');
            
            // Validate timezone
            try {
                new \DateTimeZone($timezone);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid timezone: ' . $timezone
                ], 400);
            }
            
            // Store timezone in cache
            \Cache::put('user_timezone', $timezone, 3600); // Cache for 1 hour
            
            // Also store in database if settings table exists
            if (\Schema::hasTable('settings')) {
                \DB::table('settings')->updateOrInsert(
                    ['key' => 'timezone'],
                    ['value' => $timezone, 'updated_at' => now()]
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Timezone updated successfully',
                'timezone' => $timezone
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getSystemInfo()
    {
        try {
            // Get real database connections count
            $dbConnections = $this->getDatabaseConnections();
            
            // Get real active users (from sessions)
            $activeUsers = $this->getActiveUsers();
            
            // Get real database size
            $dbSize = $this->getDatabaseSize();
            
            // Get real uptime
            $uptime = $this->getUptime();
            
            // Get real disk usage
            $diskUsage = $this->getDiskUsage();
            
            // Get real CPU load
            $cpuLoad = $this->getCPULoad();
            
            // Get real last backup
            $lastBackup = $this->getLastBackup();
            
            // Get real memory usage
            $memoryUsage = $this->getSystemMemoryUsage();
            
            // Get real server time with timezone
            $serverTime = $this->getServerTime();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'db_connections' => $dbConnections,
                    'active_users' => $activeUsers,
                    'db_size' => $dbSize,
                    'uptime' => $uptime,
                    'memory_usage' => memory_get_usage(true),
                    'memory_peak' => memory_get_peak_usage(true),
                    'memory_limit' => ini_get('memory_limit'),
                    'system_memory' => $memoryUsage,
                    'disk_usage' => $diskUsage,
                    'cpu_load' => $cpuLoad,
                    'last_backup' => $lastBackup,
                    'server_time' => $serverTime,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    private function getDatabaseConnections()
    {
        try {
            // Get active connections from MySQL
            $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            return $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getActiveUsers()
    {
        try {
            // Count active sessions (simplified approach)
            $activeSessions = DB::table('laravel_sessions')
                ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
                ->count();
            
            return $activeSessions;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getDatabaseSize()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [$dbName]);
            
            return $result[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getUptime()
    {
        try {
            // Get server uptime (simplified approach)
            $uptimeFile = '/proc/uptime';
            if (file_exists($uptimeFile)) {
                $uptime = file_get_contents($uptimeFile);
                $uptime = explode(' ', $uptime)[0];
                $days = floor($uptime / 86400);
                return $days;
            }
            
            // Fallback: use cache to track uptime
            $uptime = Cache::get('system_uptime', 0);
            if ($uptime === 0) {
                Cache::put('system_uptime', 1, now()->addDays(1));
                return 1;
            }
            
            return $uptime;
        } catch (\Exception $e) {
            return 1;
        }
    }
    
    private function getDiskUsage()
    {
        try {
            // Get disk usage for the application directory
            $path = base_path();
            $totalBytes = disk_total_space($path);
            $freeBytes = disk_free_space($path);
            $usedBytes = $totalBytes - $freeBytes;
            
            $totalGB = round($totalBytes / (1024 * 1024 * 1024), 2);
            $usedGB = round($usedBytes / (1024 * 1024 * 1024), 2);
            $percentage = round(($usedBytes / $totalBytes) * 100, 1);
            
            return [
                'total' => $totalGB,
                'used' => $usedGB,
                'free' => round($freeBytes / (1024 * 1024 * 1024), 2),
                'percentage' => $percentage
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'used' => 0,
                'free' => 0,
                'percentage' => 0
            ];
        }
    }
    
    private function getCPULoad()
    {
        try {
            // Try to get CPU load from /proc/loadavg (Linux)
            if (file_exists('/proc/loadavg')) {
                $loadavg = file_get_contents('/proc/loadavg');
                $loads = explode(' ', $loadavg);
                $load1min = floatval($loads[0]);
                
                // Get number of CPU cores from /proc/cpuinfo
                $cores = 1;
                if (file_exists('/proc/cpuinfo')) {
                    $cpuinfo = file_get_contents('/proc/cpuinfo');
                    
                    // Count physical cores (not threads)
                    $physicalCores = 0;
                    $coresPerSocket = 0;
                    $sockets = 0;
                    
                    // Get cores per socket
                    preg_match_all('/cpu cores\s*:\s*(\d+)/', $cpuinfo, $matches);
                    if (!empty($matches[1])) {
                        $coresPerSocket = intval($matches[1][0]);
                    }
                    
                    // Get number of sockets
                    preg_match_all('/physical id\s*:\s*(\d+)/', $cpuinfo, $matches);
                    if (!empty($matches[1])) {
                        $sockets = count(array_unique($matches[1])) + 1;
                    }
                    
                    // Calculate total physical cores
                    if ($coresPerSocket > 0 && $sockets > 0) {
                        $physicalCores = $coresPerSocket * $sockets;
                    } else {
                        // Fallback: count unique core IDs
                        preg_match_all('/core id\s*:\s*(\d+)/', $cpuinfo, $matches);
                        if (!empty($matches[1])) {
                            $physicalCores = count(array_unique($matches[1]));
                        }
                    }
                    
                    $cores = $physicalCores > 0 ? $physicalCores : 1;
                }
                
                $percentage = min(100, ($load1min / $cores) * 100);
                
                return [
                    'load' => round($percentage, 1),
                    'cores' => $cores,
                    'threads' => substr_count($cpuinfo, 'processor'),
                    'load1min' => $load1min
                ];
            }
            
            // For Windows systems
            if (PHP_OS_FAMILY === 'Windows') {
                // Try to get CPU info from WMI
                $cores = 1;
                $load = 0;
                
                // Get CPU cores from system info
                if (function_exists('shell_exec')) {
                    $cpuInfo = shell_exec('wmic cpu get NumberOfCores,NumberOfLogicalProcessors /value 2>nul');
                    if ($cpuInfo) {
                        preg_match('/NumberOfCores=(\d+)/', $cpuInfo, $matches);
                        if (isset($matches[1])) {
                            $cores = intval($matches[1]); // Use physical cores only
                        }
                        
                        preg_match('/NumberOfLogicalProcessors=(\d+)/', $cpuInfo, $matches);
                        if (isset($matches[1])) {
                            $threads = intval($matches[1]); // Total threads
                        }
                    }
                    
                    // Get CPU usage
                    $cpuUsage = shell_exec('wmic cpu get loadpercentage /value 2>nul');
                    if ($cpuUsage) {
                        preg_match('/LoadPercentage=(\d+)/', $cpuUsage, $matches);
                        if (isset($matches[1])) {
                            $load = intval($matches[1]);
                        }
                    }
                }
                
                return [
                    'load' => $load,
                    'cores' => $cores,
                    'threads' => $threads ?? $cores,
                    'load1min' => $load / 100
                ];
            }
            
            // Fallback for other systems
            return [
                'load' => 0,
                'cores' => 1,
                'load1min' => 0
            ];
        } catch (\Exception $e) {
            return [
                'load' => 0,
                'cores' => 1,
                'load1min' => 0
            ];
        }
    }
    
    private function getSystemMemoryUsage()
    {
        try {
            // For Linux systems
            if (file_exists('/proc/meminfo')) {
                $meminfo = file_get_contents('/proc/meminfo');
                
                $total = 0;
                $free = 0;
                $available = 0;
                
                preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $matches);
                if (isset($matches[1])) {
                    $total = intval($matches[1]) * 1024; // Convert KB to bytes
                }
                
                preg_match('/MemFree:\s+(\d+)\s+kB/', $meminfo, $matches);
                if (isset($matches[1])) {
                    $free = intval($matches[1]) * 1024;
                }
                
                preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $matches);
                if (isset($matches[1])) {
                    $available = intval($matches[1]) * 1024;
                }
                
                $used = $total - $available;
                $percentage = $total > 0 ? round(($used / $total) * 100, 1) : 0;
                
                return [
                    'total' => $total,
                    'used' => $used,
                    'free' => $free,
                    'available' => $available,
                    'percentage' => $percentage
                ];
            }
            
            // For Windows systems
            if (PHP_OS_FAMILY === 'Windows') {
                if (function_exists('shell_exec')) {
                    $memoryInfo = shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value 2>nul');
                    
                    $total = 0;
                    $free = 0;
                    
                    if ($memoryInfo) {
                        preg_match('/TotalVisibleMemorySize=(\d+)/', $memoryInfo, $matches);
                        if (isset($matches[1])) {
                            $total = intval($matches[1]) * 1024; // Convert KB to bytes
                        }
                        
                        preg_match('/FreePhysicalMemory=(\d+)/', $memoryInfo, $matches);
                        if (isset($matches[1])) {
                            $free = intval($matches[1]) * 1024;
                        }
                    }
                    
                    $used = $total - $free;
                    $percentage = $total > 0 ? round(($used / $total) * 100, 1) : 0;
                    
                    return [
                        'total' => $total,
                        'used' => $used,
                        'free' => $free,
                        'available' => $free,
                        'percentage' => $percentage
                    ];
                }
            }
            
            // Fallback
            return [
                'total' => 0,
                'used' => 0,
                'free' => 0,
                'available' => 0,
                'percentage' => 0
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'used' => 0,
                'free' => 0,
                'available' => 0,
                'percentage' => 0
            ];
        }
    }
    
    private function getServerTime()
    {
        try {
            // Get timezone from user settings (stored in cache or database)
            $timezone = $this->getUserTimezone();
            
            // Set timezone
            date_default_timezone_set($timezone);
            
            // Get current time in user's selected timezone
            $now = new \DateTime();
            $now->setTimezone(new \DateTimeZone($timezone));
            
            return [
                'time' => $now->format('Y-m-d H:i:s'),
                'timezone' => $timezone,
                'timestamp' => $now->getTimestamp(),
                'formatted' => $now->format('d/m/Y H:i:s') . ' (' . $timezone . ')'
            ];
        } catch (\Exception $e) {
            return [
                'time' => date('Y-m-d H:i:s'),
                'timezone' => 'UTC',
                'timestamp' => time(),
                'formatted' => date('d/m/Y H:i:s') . ' (UTC)'
            ];
        }
    }
    
    private function getUserTimezone()
    {
        try {
            // Try to get timezone from cache first (user settings)
            $timezone = \Cache::get('user_timezone', null);
            
            if ($timezone) {
                return $timezone;
            }
            
            // Try to get from database settings table if exists
            if (\Schema::hasTable('settings')) {
                $setting = \DB::table('settings')
                    ->where('key', 'timezone')
                    ->first();
                
                if ($setting && $setting->value) {
                    // Cache the timezone for future use
                    \Cache::put('user_timezone', $setting->value, 3600); // Cache for 1 hour
                    return $setting->value;
                }
            }
            
            // Fallback to config
            return config('app.timezone', 'Asia/Bangkok');
        } catch (\Exception $e) {
            return config('app.timezone', 'Asia/Bangkok');
        }
    }
    
    private function getLastBackup()
    {
        try {
            // Check for backup files in storage/app/backups
            $backupPath = storage_path('app/backups');
            
            if (is_dir($backupPath)) {
                $files = glob($backupPath . '/*.sql');
                if (!empty($files)) {
                    $latestFile = max($files);
                    $lastModified = filemtime($latestFile);
                    return date('Y-m-d H:i:s', $lastModified);
                }
            }
            
            // Check for Laravel backup files
            $laravelBackupPath = storage_path('app/backups');
            if (is_dir($laravelBackupPath)) {
                $files = glob($laravelBackupPath . '/*');
                if (!empty($files)) {
                    $latestFile = max($files);
                    $lastModified = filemtime($latestFile);
                    return date('Y-m-d H:i:s', $lastModified);
                }
            }
            
            // Check cache for last backup time
            $lastBackup = Cache::get('last_backup_time');
            if ($lastBackup) {
                return $lastBackup;
            }
            
            // No backup found
            return 'ไม่พบข้อมูลสำรอง';
        } catch (\Exception $e) {
            return 'ไม่สามารถตรวจสอบได้';
        }
    }
}
