<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditLogService
{
    /**
     * Create an audit log entry.
     */
    public static function log(
        string $event,
        Model $auditable,
        array $oldValues = null,
        array $newValues = null,
        Model $user = null,
        Request $request = null,
        string $tags = null
    ): AuditLog {
        $auditLog = new AuditLog([
            'event' => $event,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'tags' => $tags,
        ]);

        // Set user information
        if ($user) {
            $auditLog->user_type = get_class($user);
            $auditLog->user_id = $user->id;
        }

        // Set request information
        if ($request) {
            $auditLog->url = $request->fullUrl();
            $auditLog->ip_address = $request->ip();
            $auditLog->user_agent = $request->userAgent();
        }

        $auditLog->save();

        return $auditLog;
    }

    /**
     * Log a created event.
     */
    public static function logCreated(
        Model $auditable,
        Model $user = null,
        Request $request = null,
        string $tags = null
    ): AuditLog {
        return self::log(
            'created',
            $auditable,
            null,
            $auditable->toArray(),
            $user,
            $request,
            $tags
        );
    }

    /**
     * Log an updated event.
     */
    public static function logUpdated(
        Model $auditable,
        array $oldValues,
        array $newValues,
        Model $user = null,
        Request $request = null,
        string $tags = null
    ): AuditLog {
        return self::log(
            'updated',
            $auditable,
            $oldValues,
            $newValues,
            $user,
            $request,
            $tags
        );
    }

    /**
     * Log a deleted event.
     */
    public static function logDeleted(
        Model $auditable,
        Model $user = null,
        Request $request = null,
        string $tags = null
    ): AuditLog {
        return self::log(
            'deleted',
            $auditable,
            $auditable->toArray(),
            null,
            $user,
            $request,
            $tags
        );
    }

    /**
     * Log a restored event.
     */
    public static function logRestored(
        Model $auditable,
        Model $user = null,
        Request $request = null,
        string $tags = null
    ): AuditLog {
        return self::log(
            'restored',
            $auditable,
            null,
            $auditable->toArray(),
            $user,
            $request,
            $tags
        );
    }

    /**
     * Get audit logs with filters.
     */
    public static function getFilteredLogs(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = AuditLog::with(['user', 'auditable'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (isset($filters['event']) && $filters['event']) {
            $query->event($filters['event']);
        }

        if (isset($filters['auditable_type']) && $filters['auditable_type']) {
            $query->auditableType($filters['auditable_type']);
        }

        if (isset($filters['user_id']) && $filters['user_id']) {
            $query->byUser($filters['user_id']);
        }

        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $from = Carbon::parse($filters['date_from'])->startOfDay();
            $to = Carbon::parse($filters['date_to'])->endOfDay();
            $query->dateRange($from, $to);
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        return $query->paginate($filters['per_page'] ?? 20);
    }

    /**
     * Get audit logs statistics.
     */
    public static function getStatistics(array $filters = []): array
    {
        $query = AuditLog::query();

        // Apply date range filter
        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $from = Carbon::parse($filters['date_from'])->startOfDay();
            $to = Carbon::parse($filters['date_to'])->endOfDay();
            $query->dateRange($from, $to);
        }

        $stats = [
            'total' => $query->count(),
            'created' => $query->clone()->event('created')->count(),
            'updated' => $query->clone()->event('updated')->count(),
            'deleted' => $query->clone()->event('deleted')->count(),
            'restored' => $query->clone()->event('restored')->count(),
        ];

        // Calculate percentages
        $total = $stats['total'];
        if ($total > 0) {
            $stats['percentages'] = [
                'created' => round(($stats['created'] / $total) * 100, 2),
                'updated' => round(($stats['updated'] / $total) * 100, 2),
                'deleted' => round(($stats['deleted'] / $total) * 100, 2),
                'restored' => round(($stats['restored'] / $total) * 100, 2),
            ];
        } else {
            $stats['percentages'] = [
                'created' => 0,
                'updated' => 0,
                'deleted' => 0,
                'restored' => 0,
            ];
        }

        return $stats;
    }

    /**
     * Get recent audit logs.
     */
    public static function getRecentLogs(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::with(['user', 'auditable'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by user.
     */
    public static function getLogsByUser($user, int $limit = 20): \Illuminate\Pagination\LengthAwarePaginator
    {
        return AuditLog::with(['auditable'])
            ->byUser($user)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get audit logs by auditable model.
     */
    public static function getLogsByAuditable(Model $auditable, int $limit = 20): \Illuminate\Pagination\LengthAwarePaginator
    {
        return AuditLog::with(['user'])
            ->where('auditable_type', get_class($auditable))
            ->where('auditable_id', $auditable->id)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Clean up old audit logs.
     */
    public static function cleanupOldLogs(int $daysToKeep = 365): int
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep);
        
        return AuditLog::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Export audit logs to CSV.
     */
    public static function exportToCsv(array $filters = []): string
    {
        $logs = self::getFilteredLogs($filters);
        
        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/exports/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        $file = fopen($filepath, 'w');
        
        // CSV Headers
        fputcsv($file, [
            'ID',
            'วันที่/เวลา',
            'ผู้ใช้',
            'เหตุการณ์',
            'ประเภทข้อมูล',
            'ข้อมูลที่เปลี่ยนแปลง',
            'ค่าเดิม',
            'ค่าใหม่',
            'IP Address',
            'URL',
            'Tags'
        ]);

        // CSV Data
        foreach ($logs as $log) {
            fputcsv($file, [
                $log->id,
                $log->created_at->format('d/m/Y H:i:s'),
                $log->user_display_name,
                $log->event,
                $log->formatted_auditable_type,
                $log->auditable_id,
                $log->formatted_old_values,
                $log->formatted_new_values,
                $log->ip_address,
                $log->url,
                $log->tags
            ]);
        }

        fclose($file);
        
        return $filepath;
    }

    /**
     * Get audit log summary for dashboard.
     */
    public static function getDashboardSummary(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today' => AuditLog::whereDate('created_at', $today)->count(),
            'yesterday' => AuditLog::whereDate('created_at', $yesterday)->count(),
            'this_week' => AuditLog::where('created_at', '>=', $thisWeek)->count(),
            'this_month' => AuditLog::where('created_at', '>=', $thisMonth)->count(),
            'recent_activities' => self::getRecentLogs(5),
        ];
    }
}
