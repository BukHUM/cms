<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\CacheService;

class PerformanceMonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:monitor 
                            {action : The action to perform (check|optimize|report)}
                            {--table= : Specific table to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor and optimize database performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $table = $this->option('table');

        switch ($action) {
            case 'check':
                $this->checkPerformance($table);
                break;
            case 'optimize':
                $this->optimizePerformance();
                break;
            case 'report':
                $this->generateReport();
                break;
            default:
                $this->error('Invalid action. Use: check, optimize, or report');
                return 1;
        }

        return 0;
    }

    /**
     * Check performance
     */
    private function checkPerformance($table = null)
    {
        $this->info('Checking database performance...');
        
        if ($table) {
            $this->checkTablePerformance($table);
        } else {
            $this->checkAllTablesPerformance();
        }
    }

    /**
     * Check all tables performance
     */
    private function checkAllTablesPerformance()
    {
        $tables = [
            'laravel_users',
            'laravel_audit_logs',
            'laravel_login_attempts',
            'laravel_settings',
            'laravel_roles',
            'laravel_permissions',
            'laravel_user_roles',
            'laravel_role_permissions'
        ];

        foreach ($tables as $table) {
            $this->checkTablePerformance($table);
        }
    }

    /**
     * Check specific table performance
     */
    private function checkTablePerformance($table)
    {
        try {
            // Check if table exists
            $exists = DB::select("SHOW TABLES LIKE '{$table}'");
            if (empty($exists)) {
                $this->warn("Table {$table} does not exist.");
                return;
            }

            // Get table info
            $tableInfo = DB::select("
                SELECT 
                    TABLE_ROWS as row_count,
                    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) as size_mb,
                    ROUND((DATA_LENGTH / 1024 / 1024), 2) as data_size_mb,
                    ROUND((INDEX_LENGTH / 1024 / 1024), 2) as index_size_mb
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_NAME = '{$table}'
            ")[0];

            // Get indexes
            $indexes = DB::select("SHOW INDEX FROM {$table}");

            $this->info("Table: {$table}");
            $this->line("  Rows: " . number_format($tableInfo->row_count));
            $this->line("  Size: {$tableInfo->size_mb} MB");
            $this->line("  Data: {$tableInfo->data_size_mb} MB");
            $this->line("  Index: {$tableInfo->index_size_mb} MB");
            $this->line("  Indexes: " . count($indexes));
            
            // Check for missing indexes
            $this->checkMissingIndexes($table);
            
            $this->line('');

        } catch (\Exception $e) {
            $this->error("Error checking table {$table}: " . $e->getMessage());
        }
    }

    /**
     * Check for missing indexes
     */
    private function checkMissingIndexes($table)
    {
        $recommendations = [];

        switch ($table) {
            case 'laravel_users':
                $recommendations = [
                    'email' => 'CREATE INDEX idx_email ON laravel_users (email)',
                    'status' => 'CREATE INDEX idx_status ON laravel_users (status)',
                    'last_login_at' => 'CREATE INDEX idx_last_login ON laravel_users (last_login_at)'
                ];
                break;
            case 'laravel_audit_logs':
                $recommendations = [
                    'user_id' => 'CREATE INDEX idx_user_id ON laravel_audit_logs (user_id)',
                    'created_at' => 'CREATE INDEX idx_created_at ON laravel_audit_logs (created_at)',
                    'action' => 'CREATE INDEX idx_action ON laravel_audit_logs (action)'
                ];
                break;
        }

        if (!empty($recommendations)) {
            $this->warn("  Recommended indexes:");
            foreach ($recommendations as $column => $sql) {
                $this->line("    {$column}: {$sql}");
            }
        }
    }

    /**
     * Optimize performance
     */
    private function optimizePerformance()
    {
        $this->info('Optimizing database performance...');
        
        // Run OPTIMIZE TABLE on all tables
        $tables = [
            'laravel_users',
            'laravel_audit_logs',
            'laravel_login_attempts',
            'laravel_settings',
            'laravel_roles',
            'laravel_permissions',
            'laravel_user_roles',
            'laravel_role_permissions'
        ];

        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
                $this->info("Optimized table: {$table}");
            } catch (\Exception $e) {
                $this->warn("Could not optimize table {$table}: " . $e->getMessage());
            }
        }

        // Clear cache
        CacheService::clearAllCache();
        $this->info('Cleared all cache.');

        $this->info('Performance optimization completed.');
    }

    /**
     * Generate performance report
     */
    private function generateReport()
    {
        $this->info('Generating performance report...');
        
        // Database statistics
        $dbStats = DB::select("SHOW STATUS WHERE Variable_name IN (
            'Uptime',
            'Questions',
            'Slow_queries',
            'Connections',
            'Threads_connected',
            'Max_used_connections'
        )");

        $this->info('Database Statistics:');
        $this->line('===================');
        
        foreach ($dbStats as $stat) {
            $this->line("{$stat->Variable_name}: {$stat->Value}");
        }

        // Cache statistics
        $this->line('');
        $this->info('Cache Statistics:');
        $this->line('================');
        
        $cacheStats = CacheService::getCacheStats();
        $this->line("Total Keys: {$cacheStats['total_keys']}");
        $this->line("Memory Usage: {$cacheStats['memory_usage']} MB");
        $this->line("Hit Rate: {$cacheStats['hit_rate']}%");

        // Table statistics
        $this->line('');
        $this->info('Table Statistics:');
        $this->line('================');
        
        $tables = DB::select("
            SELECT 
                TABLE_NAME as table_name,
                TABLE_ROWS as row_count,
                ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) as size_mb
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = DATABASE()
            ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC
        ");

        $this->table(
            ['Table', 'Rows', 'Size (MB)'],
            array_map(function($table) {
                return [
                    $table->table_name,
                    number_format($table->row_count),
                    $table->size_mb
                ];
            }, $tables)
        );
    }
}
