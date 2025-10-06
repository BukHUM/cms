<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceController extends Controller
{
    /**
     * Get slow queries from MySQL
     */
    public function getSlowQueries()
    {
        try {
            // First try to get slow queries from MySQL slow query log
            $slowQueries = DB::select("
                SELECT 
                    sql_text as query,
                    query_time as time,
                    count as count,
                    db as table_name
                FROM (
                    SELECT 
                        SUBSTRING(sql_text, 1, 100) as sql_text,
                        AVG(query_time) as query_time,
                        COUNT(*) as count,
                        db
                    FROM mysql.slow_log 
                    WHERE start_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                    GROUP BY SUBSTRING(sql_text, 1, 100), db
                    ORDER BY query_time DESC
                    LIMIT 10
                ) as slow_queries
            ");

            // If slow_log table doesn't exist or is empty, use processlist as fallback
            if (empty($slowQueries)) {
                $slowQueries = DB::select("
                    SELECT 
                        SUBSTRING(INFO, 1, 100) as query,
                        TIME as time,
                        1 as count,
                        DB as table_name
                    FROM INFORMATION_SCHEMA.PROCESSLIST 
                    WHERE COMMAND != 'Sleep' 
                    AND TIME > 1
                    ORDER BY TIME DESC
                    LIMIT 10
                ");
            }

            // If still empty, provide sample data
            if (empty($slowQueries)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $slowQueries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Unable to fetch slow queries: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get duplicate queries analysis
     */
    public function getDuplicateQueries()
    {
        try {
            // Try to get query statistics from MySQL slow log
            $duplicateQueries = DB::select("
                SELECT 
                    SUBSTRING(sql_text, 1, 80) as query,
                    COUNT(*) as count,
                    AVG(query_time) as avg_time,
                    db as table_name
                FROM mysql.slow_log 
                WHERE start_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY SUBSTRING(sql_text, 1, 80), db
                HAVING COUNT(*) > 5
                ORDER BY COUNT(*) DESC
                LIMIT 10
            ");

            // If slow_log table doesn't exist or is empty, provide sample data
            if (empty($duplicateQueries)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Add impact level based on count
            foreach ($duplicateQueries as &$query) {
                if ($query->count > 60) {
                    $query->impact = 'High';
                } elseif ($query->count > 30) {
                    $query->impact = 'Medium';
                } else {
                    $query->impact = 'Low';
                }
            }

            return response()->json([
                'success' => true,
                'data' => $duplicateQueries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Unable to fetch duplicate queries: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        try {
            // Get MySQL status variables using SHOW STATUS
            $metrics = DB::select("SHOW STATUS WHERE Variable_name IN (
                'Uptime',
                'Questions',
                'Slow_queries',
                'Connections',
                'Threads_connected',
                'Max_used_connections',
                'Qcache_hits',
                'Qcache_inserts',
                'Qcache_not_cached',
                'Innodb_buffer_pool_read_requests',
                'Innodb_buffer_pool_reads',
                'Innodb_buffer_pool_pages_data',
                'Innodb_buffer_pool_pages_total',
                'Innodb_rows_read',
                'Innodb_rows_inserted',
                'Innodb_rows_updated',
                'Innodb_rows_deleted',
                'Table_locks_waited',
                'Table_locks_immediate',
                'Created_tmp_tables',
                'Created_tmp_disk_tables',
                'Select_scan',
                'Select_range',
                'Sort_merge_passes',
                'Sort_range',
                'Sort_scan'
            )");

            $result = [];
            foreach ($metrics as $metric) {
                $result[$metric->Variable_name] = $metric->Value;
            }

            // Calculate additional metrics
            $responseTime = $this->calculateResponseTime();
            $memoryUsage = $this->getMemoryUsage();
            $cacheHitRate = $this->calculateCacheHitRate();

            // Calculate buffer pool hit rate
            $bufferPoolHitRate = 0;
            if (isset($result['Innodb_buffer_pool_read_requests']) && isset($result['Innodb_buffer_pool_reads'])) {
                $requests = (int)$result['Innodb_buffer_pool_read_requests'];
                $reads = (int)$result['Innodb_buffer_pool_reads'];
                if ($requests > 0) {
                    $bufferPoolHitRate = round((($requests - $reads) / $requests) * 100, 2);
                }
            }

            // Calculate table lock wait ratio
            $tableLockWaitRatio = 0;
            if (isset($result['Table_locks_immediate']) && isset($result['Table_locks_waited'])) {
                $immediate = (int)$result['Table_locks_immediate'];
                $waited = (int)$result['Table_locks_waited'];
                $total = $immediate + $waited;
                if ($total > 0) {
                    $tableLockWaitRatio = round(($waited / $total) * 100, 2);
                }
            }

            // Calculate temporary table ratio
            $tmpTableRatio = 0;
            if (isset($result['Created_tmp_tables']) && isset($result['Created_tmp_disk_tables'])) {
                $tmpTables = (int)$result['Created_tmp_tables'];
                $tmpDiskTables = (int)$result['Created_tmp_disk_tables'];
                if ($tmpTables > 0) {
                    $tmpTableRatio = round(($tmpDiskTables / $tmpTables) * 100, 2);
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'response_time' => $responseTime,
                    'memory_usage' => $memoryUsage,
                    'cache_hit_rate' => $cacheHitRate,
                    'active_connections' => $result['Threads_connected'] ?? 0,
                    'total_queries' => $result['Questions'] ?? 0,
                    'slow_queries' => $result['Slow_queries'] ?? 0,
                    'uptime' => $result['Uptime'] ?? 0,
                    'max_connections' => $result['Max_used_connections'] ?? 0,
                    'buffer_pool_hit_rate' => $bufferPoolHitRate,
                    'table_lock_wait_ratio' => $tableLockWaitRatio,
                    'tmp_table_ratio' => $tmpTableRatio,
                    'innodb_rows_read' => $result['Innodb_rows_read'] ?? 0,
                    'innodb_rows_inserted' => $result['Innodb_rows_inserted'] ?? 0,
                    'innodb_rows_updated' => $result['Innodb_rows_updated'] ?? 0,
                    'innodb_rows_deleted' => $result['Innodb_rows_deleted'] ?? 0,
                    'select_scan' => $result['Select_scan'] ?? 0,
                    'select_range' => $result['Select_range'] ?? 0,
                    'sort_merge_passes' => $result['Sort_merge_passes'] ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            // Provide fallback metrics when MySQL queries fail
            $fallbackData = [
                'response_time' => $this->calculateResponseTime(),
                'memory_usage' => $this->getMemoryUsage(),
                'cache_hit_rate' => $this->calculateCacheHitRate(),
                'active_connections' => 5,
                'total_queries' => 1000,
                'slow_queries' => 10,
                'uptime' => 3600,
                'max_connections' => 10,
                'buffer_pool_hit_rate' => 95.5,
                'table_lock_wait_ratio' => 2.1,
                'tmp_table_ratio' => 15.3,
                'innodb_rows_read' => 50000,
                'innodb_rows_inserted' => 1000,
                'innodb_rows_updated' => 500,
                'innodb_rows_deleted' => 100,
                'select_scan' => 25,
                'select_range' => 150,
                'sort_merge_passes' => 5
            ];

            return response()->json([
                'success' => true,
                'data' => $fallbackData,
                'message' => 'Using fallback metrics: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get table statistics
     */
    public function getTableStatistics()
    {
        try {
            $currentDb = config('database.connections.mysql.database');
            
            // Get table statistics
            $tables = DB::select("
                SELECT 
                    TABLE_NAME as table_name,
                    TABLE_ROWS as row_count,
                    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) as size_mb,
                    ROUND((DATA_LENGTH / 1024 / 1024), 2) as data_size_mb,
                    ROUND((INDEX_LENGTH / 1024 / 1024), 2) as index_size_mb,
                    ROUND((DATA_FREE / 1024 / 1024), 2) as free_space_mb,
                    ENGINE as engine,
                    TABLE_COLLATION as collation
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = ?
                ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC
                LIMIT 20
            ", [$currentDb]);

            return response()->json([
                'success' => true,
                'data' => $tables
            ]);

        } catch (\Exception $e) {
            $fallbackData = [
                (object)[
                    'table_name' => 'laravel_users',
                    'row_count' => 150,
                    'size_mb' => 0.05,
                    'data_size_mb' => 0.03,
                    'index_size_mb' => 0.02,
                    'free_space_mb' => 0.00,
                    'engine' => 'InnoDB',
                    'collation' => 'utf8mb4_unicode_ci'
                ],
                (object)[
                    'table_name' => 'laravel_sessions',
                    'row_count' => 25,
                    'size_mb' => 0.02,
                    'data_size_mb' => 0.01,
                    'index_size_mb' => 0.01,
                    'free_space_mb' => 0.00,
                    'engine' => 'InnoDB',
                    'collation' => 'utf8mb4_unicode_ci'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $fallbackData,
                'message' => 'Using fallback data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get index usage statistics
     */
    public function getIndexStatistics()
    {
        try {
            $currentDb = config('database.connections.mysql.database');
            
            // Get index usage statistics
            $indexes = DB::select("
                SELECT 
                    TABLE_NAME as table_name,
                    INDEX_NAME as index_name,
                    COLUMN_NAME as column_name,
                    CARDINALITY as cardinality,
                    CASE 
                        WHEN NON_UNIQUE = 0 THEN 'UNIQUE'
                        WHEN INDEX_TYPE = 'FULLTEXT' THEN 'FULLTEXT'
                        ELSE 'INDEX'
                    END as index_type,
                    CASE 
                        WHEN CARDINALITY = 0 THEN 'Unused'
                        WHEN CARDINALITY < 10 THEN 'Low'
                        WHEN CARDINALITY < 100 THEN 'Medium'
                        ELSE 'High'
                    END as usage_level
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = ?
                ORDER BY TABLE_NAME, CARDINALITY DESC
                LIMIT 50
            ", [$currentDb]);

            return response()->json([
                'success' => true,
                'data' => $indexes
            ]);

        } catch (\Exception $e) {
            $fallbackData = [
                (object)[
                    'table_name' => 'laravel_users',
                    'index_name' => 'PRIMARY',
                    'column_name' => 'id',
                    'cardinality' => 150,
                    'index_type' => 'UNIQUE',
                    'usage_level' => 'High'
                ],
                (object)[
                    'table_name' => 'laravel_users',
                    'index_name' => 'users_email_unique',
                    'column_name' => 'email',
                    'cardinality' => 150,
                    'index_type' => 'UNIQUE',
                    'usage_level' => 'High'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $fallbackData,
                'message' => 'Using fallback data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get connection statistics
     */
    public function getConnectionStatistics()
    {
        try {
            // Get connection statistics
            $connections = DB::select("
                SELECT 
                    USER as user,
                    HOST as host,
                    DB as database_name,
                    COMMAND as command,
                    TIME as duration,
                    STATE as state,
                    COUNT(*) as connection_count
                FROM INFORMATION_SCHEMA.PROCESSLIST 
                WHERE DB IS NOT NULL
                GROUP BY USER, HOST, DB, COMMAND, STATE
                ORDER BY connection_count DESC
                LIMIT 20
            ");

            return response()->json([
                'success' => true,
                'data' => $connections
            ]);

        } catch (\Exception $e) {
            $fallbackData = [
                (object)[
                    'user' => 'root',
                    'host' => 'localhost',
                    'database_name' => 'laravel_backend',
                    'command' => 'Sleep',
                    'duration' => 0,
                    'state' => '',
                    'connection_count' => 1
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $fallbackData,
                'message' => 'Using fallback data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate response time
     */
    private function calculateResponseTime()
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $end = microtime(true);
            return round(($end - $start) * 1000, 2); // Convert to milliseconds
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get memory usage
     */
    private function getMemoryUsage()
    {
        try {
            $memoryUsage = memory_get_usage(true);
            return round($memoryUsage / 1024 / 1024, 2); // Convert to MB
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calculate cache hit rate
     */
    private function calculateCacheHitRate()
    {
        try {
            // Simple cache hit rate calculation
            $cacheHits = Cache::get('cache_hits', 0);
            $cacheMisses = Cache::get('cache_misses', 0);
            
            if ($cacheHits + $cacheMisses == 0) {
                return 0;
            }
            
            return round(($cacheHits / ($cacheHits + $cacheMisses)) * 100, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Save performance settings
     */
    public function savePerformanceSettings(Request $request)
    {
        try {
            $request->validate([
                'cache_enabled' => 'boolean',
                'cache_driver' => 'string|in:file,redis,memcached',
                'cache_ttl' => 'integer|min:1|max:1440',
                'query_logging' => 'boolean',
                'slow_query_threshold' => 'integer|min:100|max:10000',
                'memory_limit' => 'integer|min:128|max:2048',
                'max_execution_time' => 'integer|min:10|max:300',
                'compression_enabled' => 'boolean'
            ]);

            $settings = [
                'cache_enabled' => $request->boolean('cache_enabled'),
                'cache_driver' => $request->input('cache_driver', 'file'),
                'cache_ttl' => $request->input('cache_ttl', 60),
                'query_logging' => $request->boolean('query_logging'),
                'slow_query_threshold' => $request->input('slow_query_threshold', 1000),
                'memory_limit' => $request->input('memory_limit', 256),
                'max_execution_time' => $request->input('max_execution_time', 30),
                'compression_enabled' => $request->boolean('compression_enabled')
            ];

            // Save to database or config file
            $this->updateConfigFile($settings);

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการตั้งค่า Performance เรียบร้อยแล้ว',
                'data' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่า: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update configuration file with new settings
     */
    private function updateConfigFile($settings)
    {
        try {
            // Update cache.php config
            $cacheConfigPath = config_path('cache.php');
            if (file_exists($cacheConfigPath)) {
                $cacheConfig = file_get_contents($cacheConfigPath);
                
                // Update default cache driver
                $cacheConfig = preg_replace(
                    "/'default'\s*=>\s*env\('CACHE_DRIVER',\s*'[^']*'\)/",
                    "'default' => env('CACHE_DRIVER', '{$settings['cache_driver']}')",
                    $cacheConfig
                );
                
                file_put_contents($cacheConfigPath, $cacheConfig);
            }

            // Update database.php config for query logging
            $dbConfigPath = config_path('database.php');
            if (file_exists($dbConfigPath)) {
                $dbConfig = file_get_contents($dbConfigPath);
                
                // Find MySQL connection config
                if (preg_match('/mysql\s*=>\s*\[([\s\S]*?)\]/', $dbConfig, $matches)) {
                    $mysqlConfig = $matches[1];
                    
                    if ($settings['query_logging']) {
                        // Enable query logging
                        if (preg_match("/'logging'\s*=>\s*false/", $mysqlConfig)) {
                            $mysqlConfig = preg_replace(
                                "/'logging'\s*=>\s*false/",
                                "'logging' => true",
                                $mysqlConfig
                            );
                        } elseif (!preg_match("/'logging'\s*=>/", $mysqlConfig)) {
                            // Add logging if it doesn't exist
                            $mysqlConfig = rtrim($mysqlConfig, ",\s") . ",\n            'logging' => true";
                        }
                    } else {
                        // Disable query logging
                        if (preg_match("/'logging'\s*=>\s*true/", $mysqlConfig)) {
                            $mysqlConfig = preg_replace(
                                "/'logging'\s*=>\s*true/",
                                "'logging' => false",
                                $mysqlConfig
                            );
                        } elseif (!preg_match("/'logging'\s*=>/", $mysqlConfig)) {
                            // Add logging if it doesn't exist
                            $mysqlConfig = rtrim($mysqlConfig, ",\s") . ",\n            'logging' => false";
                        }
                    }
                    
                    // Replace the MySQL config section
                    $dbConfig = preg_replace(
                        '/mysql\s*=>\s*\[([\s\S]*?)\]/',
                        "mysql => [{$mysqlConfig}]",
                        $dbConfig
                    );
                }
                
                file_put_contents($dbConfigPath, $dbConfig);
            }

            // Update .env file
            $envPath = base_path('.env');
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                
                // Update cache settings
                $envContent = preg_replace(
                    "/CACHE_DRIVER=.*/",
                    "CACHE_DRIVER={$settings['cache_driver']}",
                    $envContent
                );
                
                // Add or update performance settings
                $performanceSettings = [
                    "CACHE_TTL={$settings['cache_ttl']}",
                    "SLOW_QUERY_THRESHOLD={$settings['slow_query_threshold']}",
                    "MEMORY_LIMIT={$settings['memory_limit']}",
                    "MAX_EXECUTION_TIME={$settings['max_execution_time']}",
                    "COMPRESSION_ENABLED=" . ($settings['compression_enabled'] ? 'true' : 'false')
                ];
                
                foreach ($performanceSettings as $setting) {
                    $key = explode('=', $setting)[0];
                    if (preg_match("/^{$key}=.*/m", $envContent)) {
                        $envContent = preg_replace("/^{$key}=.*/m", $setting, $envContent);
                    } else {
                        $envContent .= "\n{$setting}";
                    }
                }
                
                file_put_contents($envPath, $envContent);
            }

            // Clear config cache
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');

        } catch (\Exception $e) {
            throw new \Exception('ไม่สามารถอัพเดตไฟล์ config ได้: ' . $e->getMessage());
        }
    }

    /**
     * Get current performance settings
     */
    public function getPerformanceSettings()
    {
        try {
            // Read from .env file
            $envPath = base_path('.env');
            $envSettings = [];
            
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                $lines = explode("\n", $envContent);
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
                        [$key, $value] = explode('=', $line, 2);
                        $envSettings[trim($key)] = trim($value);
                    }
                }
            }
            
            // Get current settings with fallbacks
            $settings = [
                'cache_enabled' => config('cache.default') !== 'array',
                'cache_driver' => config('cache.default'),
                'cache_ttl' => (int) ($envSettings['CACHE_TTL'] ?? 60),
                'query_logging' => $this->getQueryLoggingStatus(),
                'slow_query_threshold' => (int) ($envSettings['SLOW_QUERY_THRESHOLD'] ?? 1000),
                'memory_limit' => (int) ($envSettings['MEMORY_LIMIT'] ?? 256),
                'max_execution_time' => (int) ($envSettings['MAX_EXECUTION_TIME'] ?? 30),
                'compression_enabled' => filter_var($envSettings['COMPRESSION_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN)
            ];

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
     * Get query logging status from database config
     */
    private function getQueryLoggingStatus()
    {
        try {
            // Read from database.php file directly
            $dbConfigPath = config_path('database.php');
            if (file_exists($dbConfigPath)) {
                $dbConfig = file_get_contents($dbConfigPath);
                
                // Look for MySQL connection config section
                if (preg_match('/mysql\s*=>\s*\[([\s\S]*?)\]/', $dbConfig, $matches)) {
                    $mysqlConfig = $matches[1];
                    
                    // Check if logging is enabled
                    if (preg_match("/'logging'\s*=>\s*true/", $mysqlConfig)) {
                        return true;
                    }
                }
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Test query logging functionality
     */
    public function testQueryLogging()
    {
        try {
            // Check if query logging is enabled
            $isEnabled = $this->getQueryLoggingStatus();
            
            // Debug information
            $debugInfo = [
                'file_exists' => file_exists(config_path('database.php')),
                'is_enabled' => $isEnabled,
                'db_config_content' => 'N/A'
            ];
            
            // Read database config content for debugging
            $dbConfigPath = config_path('database.php');
            if (file_exists($dbConfigPath)) {
                $dbConfig = file_get_contents($dbConfigPath);
                $debugInfo['db_config_content'] = substr($dbConfig, 0, 500) . '...';
            }
            
            // Execute a test query
            $testQuery = "SELECT 1 as test_value";
            $result = DB::select($testQuery);
            
            // Check if query was logged
            $logPath = storage_path('logs/laravel.log');
            $queryLogged = false;
            
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $queryLogged = strpos($logContent, $testQuery) !== false;
            }

            return response()->json([
                'success' => true,
                'message' => 'ทดสอบ Query Logging สำเร็จ',
                'data' => [
                    'query_logging_enabled' => $isEnabled,
                    'test_query_executed' => !empty($result),
                    'query_logged' => $queryLogged,
                    'log_file_exists' => file_exists($logPath),
                    'debug_info' => $debugInfo
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการทดสอบ Query Logging: ' . $e->getMessage()
            ], 500);
        }
    }
}