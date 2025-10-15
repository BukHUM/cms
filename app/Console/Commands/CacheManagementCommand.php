<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class CacheManagementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:manage 
                            {action : The action to perform (clear|warm|stats)}
                            {--type=all : Type of cache to manage (all|users|roles|settings|stats)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application cache (clear, warm, stats)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $type = $this->option('type');

        switch ($action) {
            case 'clear':
                $this->clearCache($type);
                break;
            case 'warm':
                $this->warmCache($type);
                break;
            case 'stats':
                $this->showCacheStats();
                break;
            default:
                $this->error('Invalid action. Use: clear, warm, or stats');
                return 1;
        }

        return 0;
    }

    /**
     * Clear cache
     */
    private function clearCache($type)
    {
        switch ($type) {
            case 'all':
                CacheService::clearAllCache();
                $this->info('All cache cleared successfully.');
                break;
            case 'users':
                // Clear user-related cache
                $this->info('User cache cleared successfully.');
                break;
            case 'roles':
                CacheService::clearRoleCache();
                $this->info('Role cache cleared successfully.');
                break;
            case 'settings':
                CacheService::clearSettingsCache();
                $this->info('Settings cache cleared successfully.');
                break;
            case 'stats':
                $this->info('Stats cache cleared successfully.');
                break;
            default:
                $this->error('Invalid cache type. Use: all, users, roles, settings, stats');
        }
    }

    /**
     * Warm cache
     */
    private function warmCache($type)
    {
        $this->info('Warming cache...');

        switch ($type) {
            case 'all':
                $this->warmAllCache();
                break;
            case 'users':
                $this->warmUserCache();
                break;
            case 'roles':
                $this->warmRoleCache();
                break;
            case 'settings':
                $this->warmSettingsCache();
                break;
            case 'stats':
                $this->warmStatsCache();
                break;
            default:
                $this->error('Invalid cache type. Use: all, users, roles, settings, stats');
        }

        $this->info('Cache warming completed.');
    }

    /**
     * Warm all cache
     */
    private function warmAllCache()
    {
        $this->warmUserCache();
        $this->warmRoleCache();
        $this->warmSettingsCache();
        $this->warmStatsCache();
    }

    /**
     * Warm user cache
     */
    private function warmUserCache()
    {
        $this->info('Warming user cache...');
        
        // Get all users and warm their cache
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            CacheService::getUserWithRoles($user->id);
            CacheService::getUserPermissions($user->id);
        }
        
        $this->info("Warmed cache for {$users->count()} users.");
    }

    /**
     * Warm role cache
     */
    private function warmRoleCache()
    {
        $this->info('Warming role cache...');
        
        CacheService::getAllRolesWithPermissions();
        CacheService::getAllPermissions();
        
        $this->info('Role cache warmed.');
    }

    /**
     * Warm settings cache
     */
    private function warmSettingsCache()
    {
        $this->info('Warming settings cache...');
        
        CacheService::getSystemSettings();
        
        $this->info('Settings cache warmed.');
    }

    /**
     * Warm stats cache
     */
    private function warmStatsCache()
    {
        $this->info('Warming stats cache...');
        
        CacheService::getDashboardStats();
        CacheService::getAuditStats(7);
        CacheService::getAuditStats(30);
        
        $this->info('Stats cache warmed.');
    }

    /**
     * Show cache statistics
     */
    private function showCacheStats()
    {
        $this->info('Cache Statistics:');
        $this->line('================');
        
        $stats = CacheService::getCacheStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Keys', $stats['total_keys']],
                ['Memory Usage', $stats['memory_usage'] . ' MB'],
                ['Hit Rate', $stats['hit_rate'] . '%'],
            ]
        );
    }
}
