<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class SystemInfoController extends Controller
{
    /**
     * Display system information
     */
    public function index()
    {
        $systemInfo = $this->getSystemInfo();
        
        return view('backend.settings-systeminfo.index', compact('systemInfo'));
    }

    /**
     * Get comprehensive system information
     */
    private function getSystemInfo()
    {
        return [
            'server' => $this->getServerInfo(),
            'php' => $this->getPhpInfo(),
            'laravel' => $this->getLaravelInfo(),
            'database' => $this->getDatabaseInfo(),
            'cache' => $this->getCacheInfo(),
            'storage' => $this->getStorageInfo(),
            'packages' => $this->getPackagesInfo(),
            'environment' => $this->getEnvironmentInfo(),
            'performance' => $this->getPerformanceInfo(),
        ];
    }

    /**
     * Get server information
     */
    private function getServerInfo()
    {
        return [
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'server_port' => $_SERVER['SERVER_PORT'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'script_filename' => $_SERVER['SCRIPT_FILENAME'] ?? 'Unknown',
            'request_time' => $_SERVER['REQUEST_TIME'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'max_input_time' => ini_get('max_input_time'),
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_file_uploads' => ini_get('max_file_uploads'),
        ];
    }

    /**
     * Get PHP information
     */
    private function getPhpInfo()
    {
        return [
            'version' => PHP_VERSION,
            'sapi' => PHP_SAPI,
            'extensions' => get_loaded_extensions(),
            'zend_version' => zend_version(),
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'memory_limit' => ini_get('memory_limit'),
            'error_reporting' => error_reporting(),
            'display_errors' => ini_get('display_errors'),
            'log_errors' => ini_get('log_errors'),
            'error_log' => ini_get('error_log'),
        ];
    }

    /**
     * Get Laravel information
     */
    private function getLaravelInfo()
    {
        return [
            'version' => app()->version(),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'fallback_locale' => config('app.fallback_locale'),
            'key' => config('app.key') ? 'Set' : 'Not Set',
            'cipher' => config('app.cipher'),
            'providers' => count(config('app.providers')),
            'aliases' => count(config('app.aliases')),
        ];
    }

    /**
     * Get database information
     */
    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            
            return [
                'driver' => $connection->getDriverName(),
                'version' => $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION),
                'database' => $connection->getDatabaseName(),
                'host' => $connection->getConfig('host'),
                'port' => $connection->getConfig('port'),
                'charset' => $connection->getConfig('charset'),
                'collation' => $connection->getConfig('collation'),
                'prefix' => $connection->getConfig('prefix'),
                'strict' => $connection->getConfig('strict'),
                'engine' => $connection->getConfig('engine'),
                'migrations' => $this->getMigrationInfo(),
                'tables' => $this->getTablesInfo(),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get migration information
     */
    private function getMigrationInfo()
    {
        try {
            $migrations = DB::table('core_migrations')->count();
            $pending = Artisan::call('migrate:status');
            
            return [
                'total' => $migrations,
                'status' => 'Connected',
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'status' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get tables information
     */
    private function getTablesInfo()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            return count($tables);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Get cache information
     */
    private function getCacheInfo()
    {
        $driver = config('cache.default');
        
        try {
            Cache::put('system_info_test', 'test', 60);
            $test = Cache::get('system_info_test');
            Cache::forget('system_info_test');
            
            return [
                'driver' => $driver,
                'status' => $test === 'test' ? 'Working' : 'Not Working',
                'config' => config("cache.stores.{$driver}"),
            ];
        } catch (\Exception $e) {
            return [
                'driver' => $driver,
                'status' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get storage information
     */
    private function getStorageInfo()
    {
        $storagePath = storage_path();
        $publicPath = public_path();
        
        return [
            'storage_path' => $storagePath,
            'public_path' => $publicPath,
            'storage_size' => $this->getDirectorySize($storagePath),
            'public_size' => $this->getDirectorySize($publicPath),
            'logs_size' => $this->getDirectorySize($storagePath . '/logs'),
            'cache_size' => $this->getDirectorySize($storagePath . '/framework/cache'),
            'sessions_size' => $this->getDirectorySize($storagePath . '/framework/sessions'),
            'views_size' => $this->getDirectorySize($storagePath . '/framework/views'),
        ];
    }

    /**
     * Get packages information
     */
    private function getPackagesInfo()
    {
        try {
            $composerLock = base_path('composer.lock');
            if (File::exists($composerLock)) {
                $composerData = json_decode(File::get($composerLock), true);
                $packages = $composerData['packages'] ?? [];
                
                return [
                    'total_packages' => count($packages),
                    'laravel_packages' => array_filter($packages, function($package) {
                        return strpos($package['name'], 'laravel/') === 0;
                    }),
                    'dev_packages' => array_filter($packages, function($package) {
                        return isset($package['dev']) && $package['dev'];
                    }),
                ];
            }
            
            return ['error' => 'composer.lock not found'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get environment information
     */
    private function getEnvironmentInfo()
    {
        return [
            'app_env' => app()->environment(),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'app_timezone' => config('app.timezone'),
            'app_locale' => config('app.locale'),
            'db_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
            'log_level' => config('logging.level'),
            'log_channel' => config('logging.default'),
        ];
    }

    /**
     * Get performance information
     */
    private function getPerformanceInfo()
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        // Simulate some operations
        DB::select('SELECT 1');
        Cache::get('test');
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        return [
            'execution_time' => round(($endTime - $startTime) * 1000, 2) . ' ms',
            'memory_used' => $this->formatBytes($endMemory - $startMemory),
            'current_memory' => $this->formatBytes($endMemory),
            'peak_memory' => $this->formatBytes(memory_get_peak_usage(true)),
            'load_time' => round((microtime(true) - $_SERVER['REQUEST_TIME']) * 1000, 2) . ' ms',
        ];
    }

    /**
     * Get directory size
     */
    private function getDirectorySize($path)
    {
        if (!File::exists($path)) {
            return '0 B';
        }
        
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        
        return $this->formatBytes($size);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Export system information
     */
    public function export()
    {
        $systemInfo = $this->getSystemInfo();
        
        $filename = 'system_info_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($systemInfo, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

}
