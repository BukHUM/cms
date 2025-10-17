<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AuditLog extends Model
{
    protected $table = 'core_audit_logs';
    
    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the auditable model.
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    /**
     * Scope to filter by auditable type.
     */
    public function scopeAuditableType(Builder $query, string $type): Builder
    {
        return $query->where('auditable_type', $type);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser(Builder $query, $user): Builder
    {
        if (is_object($user)) {
            return $query->where('user_type', get_class($user))
                        ->where('user_id', $user->id);
        }
        
        return $query->where('user_id', $user);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange(Builder $query, Carbon $from, Carbon $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope to search in user names, emails, or auditable types.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhere('auditable_type', 'like', "%{$search}%")
            ->orWhere('event', 'like', "%{$search}%")
            ->orWhere('tags', 'like', "%{$search}%");
        });
    }

    /**
     * Get formatted event name.
     */
    public function getFormattedEventAttribute(): string
    {
        return ucfirst($this->event);
    }

    /**
     * Get formatted auditable type name.
     */
    public function getFormattedAuditableTypeAttribute(): string
    {
        return class_basename($this->auditable_type);
    }

    /**
     * Get event color class.
     */
    public function getEventColorAttribute(): string
    {
        $colors = [
            'created' => 'bg-green-100 text-green-800',
            'updated' => 'bg-blue-100 text-blue-800',
            'deleted' => 'bg-red-100 text-red-800',
            'restored' => 'bg-yellow-100 text-yellow-800',
        ];

        return $colors[$this->event] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get user display name.
     */
    public function getUserDisplayNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name ?? $this->user->email ?? 'Unknown User';
        }

        return 'System';
    }

    /**
     * Get changes summary.
     */
    public function getChangesSummaryAttribute(): string
    {
        switch ($this->event) {
            case 'created':
                return 'สร้างใหม่';
            case 'updated':
                $count = $this->new_values ? count($this->new_values) : 0;
                return "แก้ไข {$count} ฟิลด์";
            case 'deleted':
                return 'ลบข้อมูล';
            case 'restored':
                return 'กู้คืนข้อมูล';
            default:
                return ucfirst($this->event);
        }
    }

    /**
     * Get formatted old values.
     */
    public function getFormattedOldValuesAttribute(): ?string
    {
        if (!$this->old_values) {
            return null;
        }

        return json_encode($this->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get formatted new values.
     */
    public function getFormattedNewValuesAttribute(): ?string
    {
        if (!$this->new_values) {
            return null;
        }

        return json_encode($this->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get changed fields count.
     */
    public function getChangedFieldsCountAttribute(): int
    {
        if (!$this->old_values || !$this->new_values) {
            return 0;
        }

        $changed = 0;
        $allKeys = array_unique(array_merge(
            array_keys($this->old_values),
            array_keys($this->new_values)
        ));

        foreach ($allKeys as $key) {
            $oldValue = $this->old_values[$key] ?? null;
            $newValue = $this->new_values[$key] ?? null;
            
            if ($oldValue !== $newValue) {
                $changed++;
            }
        }

        return $changed;
    }

    /**
     * Check if this log has changes.
     */
    public function hasAuditChanges(): bool
    {
        return $this->old_values !== null || $this->new_values !== null;
    }

    /**
     * Get all available event types.
     */
    public static function getEventTypes(): array
    {
        return self::distinct()->pluck('event')->sort()->values()->toArray();
    }

    /**
     * Get all available auditable types.
     */
    public static function getAuditableTypes(): array
    {
        return self::distinct()->pluck('auditable_type')->sort()->values()->toArray();
    }

    /**
     * Get audit logs statistics.
     */
    public static function getStatistics(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = self::query();

        if ($from && $to) {
            $query->dateRange($from, $to);
        }

        return [
            'total' => $query->count(),
            'created' => $query->clone()->event('created')->count(),
            'updated' => $query->clone()->event('updated')->count(),
            'deleted' => $query->clone()->event('deleted')->count(),
            'restored' => $query->clone()->event('restored')->count(),
        ];
    }
}
