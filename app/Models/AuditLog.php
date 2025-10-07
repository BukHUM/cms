<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'laravel_audit_logs';

    protected $fillable = [
        'user_id',
        'user_email',
        'action',
        'resource_type',
        'resource_id',
        'description',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'status',
        'error_message',
        'session_id'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the audit log.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get audit logs with pagination
     */
    public static function getRecentLogs($limit = 10)
    {
        return self::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by user
     */
    public static function getLogsByUser($userId, $limit = 20)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by action
     */
    public static function getLogsByAction($action, $limit = 20)
    {
        return self::where('action', $action)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by date range
     */
    public static function getLogsByDateRange($startDate, $endDate, $limit = 50)
    {
        return self::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit statistics
     */
    public static function getStatistics($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_logs' => self::where('created_at', '>=', $startDate)->count(),
            'success_logs' => self::where('created_at', '>=', $startDate)
                ->where('status', 'success')->count(),
            'failed_logs' => self::where('created_at', '>=', $startDate)
                ->where('status', 'failed')->count(),
            'error_logs' => self::where('created_at', '>=', $startDate)
                ->where('status', 'error')->count(),
            'unique_users' => self::where('created_at', '>=', $startDate)
                ->distinct('user_id')->count('user_id'),
            'actions_count' => self::where('created_at', '>=', $startDate)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get()
        ];
    }

    /**
     * Create audit log entry
     */
    public static function createLog($data)
    {
        return self::create([
            'user_id' => $data['user_id'] ?? null,
            'user_email' => $data['user_email'] ?? null,
            'action' => $data['action'],
            'resource_type' => $data['resource_type'] ?? null,
            'resource_id' => $data['resource_id'] ?? null,
            'description' => $data['description'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'status' => $data['status'] ?? 'success',
            'error_message' => $data['error_message'] ?? null,
            'session_id' => $data['session_id'] ?? session()->getId()
        ]);
    }

    /**
     * Get formatted action name
     */
    public function getFormattedActionAttribute()
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
            'profile_update' => 'แก้ไขโปรไฟล์'
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Get formatted status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'success' => 'bg-success',
            'failed' => 'bg-danger',
            'error' => 'bg-warning'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get formatted status text
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'success' => 'สำเร็จ',
            'failed' => 'ล้มเหลว',
            'error' => 'ข้อผิดพลาด'
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}