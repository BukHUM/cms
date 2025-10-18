<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettingsUpdate extends Model
{
    use HasFactory;

    protected $table = 'core_settings_updates';

    protected $fillable = [
        'update_type',
        'component_name',
        'current_version',
        'target_version',
        'description',
        'changelog',
        'dependencies',
        'backup_files',
        'status',
        'error_message',
        'execution_log',
        'scheduled_at',
        'started_at',
        'completed_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'dependencies' => 'array',
        'backup_files' => 'array',
        'execution_log' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('update_type', $type);
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'failed' => 'badge-danger',
            'cancelled' => 'badge-secondary',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'รอดำเนินการ',
            'in_progress' => 'กำลังดำเนินการ',
            'completed' => 'สำเร็จ',
            'failed' => 'ล้มเหลว',
            'cancelled' => 'ยกเลิก',
        ];

        return $texts[$this->status] ?? 'ไม่ทราบสถานะ';
    }

    public function getUpdateTypeTextAttribute()
    {
        $types = [
            'core' => 'Laravel Core',
            'package' => 'Package',
            'config' => 'Configuration',
        ];

        return $types[$this->update_type] ?? $this->update_type;
    }

    // Methods
    public function canStart()
    {
        return $this->status === 'pending';
    }

    public function canCancel()
    {
        return in_array($this->status, ['pending', 'in_progress']);
    }

    public function canRetry()
    {
        return $this->status === 'failed';
    }

    public function startUpdate()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function completeUpdate($log = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'execution_log' => $log,
        ]);
    }

    public function failUpdate($errorMessage, $log = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'execution_log' => $log,
        ]);
    }

    public function cancelUpdate()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }
}
