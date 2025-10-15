<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Helpers\SettingsHelper;

class CacheService
{
    /**
     * Cache TTL constants (in minutes)
     */
    const USER_CACHE_TTL = 60; // 1 hour
    const ROLE_CACHE_TTL = 120; // 2 hours
    const PERMISSION_CACHE_TTL = 180; // 3 hours
    const SETTINGS_CACHE_TTL = 60; // 1 hour
    const STATS_CACHE_TTL = 30; // 30 minutes

    /**
     * Get user with roles and permissions (cached)
     */
    public static function getUserWithRoles($userId)
    {
        $cacheKey = "user_roles_permissions_{$userId}";
        
        return Cache::remember($cacheKey, self::USER_CACHE_TTL, function () use ($userId) {
            return User::with(['roles.permissions'])
                ->find($userId);
        });
    }

    /**
     * Get all roles with permissions (cached)
     */
    public static function getAllRolesWithPermissions()
    {
        $cacheKey = 'all_roles_with_permissions';
        
        return Cache::remember($cacheKey, self::ROLE_CACHE_TTL, function () {
            return Role::with('permissions')->get();
        });
    }

    /**
     * Get all permissions (cached)
     */
    public static function getAllPermissions()
    {
        $cacheKey = 'all_permissions';
        
        return Cache::remember($cacheKey, self::PERMISSION_CACHE_TTL, function () {
            return Permission::all();
        });
    }

    /**
     * Get user permissions (cached)
     */
    public static function getUserPermissions($userId)
    {
        $cacheKey = "user_permissions_{$userId}";
        
        return Cache::remember($cacheKey, self::USER_CACHE_TTL, function () use ($userId) {
            $user = User::with(['roles.permissions'])->find($userId);
            
            if (!$user) {
                return collect();
            }
            
            $permissions = collect();
            foreach ($user->roles as $role) {
                $permissions = $permissions->merge($role->permissions);
            }
            
            return $permissions->unique('id');
        });
    }

    /**
     * Get dashboard statistics (cached)
     */
    public static function getDashboardStats()
    {
        $cacheKey = 'dashboard_stats';
        
        return Cache::remember($cacheKey, self::STATS_CACHE_TTL, function () {
            return [
                'total_users' => User::count(),
                'active_users' => User::where('status', 'active')->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'recent_logins' => User::whereNotNull('last_login_at')
                    ->where('last_login_at', '>=', now()->subDays(7))
                    ->count(),
            ];
        });
    }

    /**
     * Get audit log statistics (cached)
     */
    public static function getAuditStats($days = 7)
    {
        $cacheKey = "audit_stats_{$days}";
        
        return Cache::remember($cacheKey, self::STATS_CACHE_TTL, function () use ($days) {
            $since = now()->subDays($days);
            
            return [
                'total_logs' => DB::table('laravel_audit_logs')
                    ->where('created_at', '>=', $since)
                    ->count(),
                'successful_logs' => DB::table('laravel_audit_logs')
                    ->where('created_at', '>=', $since)
                    ->where('status', 'success')
                    ->count(),
                'failed_logs' => DB::table('laravel_audit_logs')
                    ->where('created_at', '>=', $since)
                    ->where('status', 'failed')
                    ->count(),
                'unique_users' => DB::table('laravel_audit_logs')
                    ->where('created_at', '>=', $since)
                    ->distinct('user_id')
                    ->count('user_id'),
                'top_actions' => DB::table('laravel_audit_logs')
                    ->where('created_at', '>=', $since)
                    ->select('action', DB::raw('COUNT(*) as count'))
                    ->groupBy('action')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->get(),
            ];
        });
    }

    /**
     * Get system settings (cached)
     */
    public static function getSystemSettings()
    {
        $cacheKey = 'system_settings';
        
        return Cache::remember($cacheKey, self::SETTINGS_CACHE_TTL, function () {
            return SettingsHelper::getAll();
        });
    }

    /**
     * Get user activity summary (cached)
     */
    public static function getUserActivitySummary($userId, $days = 30)
    {
        $cacheKey = "user_activity_{$userId}_{$days}";
        
        return Cache::remember($cacheKey, self::STATS_CACHE_TTL, function () use ($userId, $days) {
            $since = now()->subDays($days);
            
            return [
                'total_activities' => DB::table('laravel_audit_logs')
                    ->where('user_id', $userId)
                    ->where('created_at', '>=', $since)
                    ->count(),
                'login_count' => DB::table('laravel_audit_logs')
                    ->where('user_id', $userId)
                    ->where('action', 'login')
                    ->where('created_at', '>=', $since)
                    ->count(),
                'last_activity' => DB::table('laravel_audit_logs')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->value('created_at'),
                'most_used_actions' => DB::table('laravel_audit_logs')
                    ->where('user_id', $userId)
                    ->where('created_at', '>=', $since)
                    ->select('action', DB::raw('COUNT(*) as count'))
                    ->groupBy('action')
                    ->orderBy('count', 'desc')
                    ->limit(3)
                    ->get(),
            ];
        });
    }

    /**
     * Clear user-related cache
     */
    public static function clearUserCache($userId)
    {
        Cache::forget("user_roles_permissions_{$userId}");
        Cache::forget("user_permissions_{$userId}");
        Cache::forget("user_activity_{$userId}_30");
        Cache::forget("user_activity_{$userId}_7");
    }

    /**
     * Clear role-related cache
     */
    public static function clearRoleCache()
    {
        Cache::forget('all_roles_with_permissions');
        Cache::forget('all_permissions');
    }

    /**
     * Clear settings cache
     */
    public static function clearSettingsCache()
    {
        Cache::forget('system_settings');
    }

    /**
     * Clear all cache
     */
    public static function clearAllCache()
    {
        Cache::flush();
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats()
    {
        $keys = [
            'user_roles_permissions_*',
            'all_roles_with_permissions',
            'all_permissions',
            'user_permissions_*',
            'dashboard_stats',
            'audit_stats_*',
            'system_settings',
            'user_activity_*',
        ];

        $stats = [
            'total_keys' => 0,
            'memory_usage' => 0,
            'hit_rate' => 0,
        ];

        // This is a simplified version - in production you might want to use Redis INFO
        try {
            $stats['total_keys'] = Cache::get('cache_stats_total_keys', 0);
            $stats['memory_usage'] = Cache::get('cache_stats_memory_usage', 0);
            $stats['hit_rate'] = Cache::get('cache_stats_hit_rate', 0);
        } catch (\Exception $e) {
            // Fallback if cache driver doesn't support stats
            $stats['total_keys'] = 0;
            $stats['memory_usage'] = 0;
            $stats['hit_rate'] = 0;
        }

        return $stats;
    }
}
