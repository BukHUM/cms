<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'core_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'group_name',
        'description',
        'is_active',
        'is_public',
        'sort_order',
        'validation_rules',
        'default_value',
        'options',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'sort_order' => 'integer',
        'validation_rules' => 'array',
        'options' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created this setting
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this setting
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for public settings
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for settings by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for settings by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group_name', $group);
    }

    /**
     * Scope for ordered settings
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('key');
    }

    /**
     * Get setting value with type casting
     */
    public function getTypedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $this->value;
            case 'float':
                return (float) $this->value;
            case 'array':
                return json_decode($this->value, true) ?? [];
            case 'json':
                return json_decode($this->value, true) ?? null;
            default:
                return $this->value;
        }
    }

    /**
     * Set setting value with type conversion
     */
    public function setTypedValue($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            case 'integer':
                $this->value = (string) (int) $value;
                break;
            case 'float':
                $this->value = (string) (float) $value;
                break;
            case 'array':
            case 'json':
                $this->value = json_encode($value);
                break;
            default:
                $this->value = (string) $value;
        }
    }

    /**
     * Validate setting value
     */
    public function validateValue($value)
    {
        if (empty($this->validation_rules)) {
            return true;
        }

        $rules = $this->validation_rules;
        
        // Basic validation
        if (isset($rules['required']) && $rules['required'] && empty($value)) {
            return false;
        }

        if (isset($rules['min']) && $value < $rules['min']) {
            return false;
        }

        if (isset($rules['max']) && $value > $rules['max']) {
            return false;
        }

        if (isset($rules['min_length']) && strlen($value) < $rules['min_length']) {
            return false;
        }

        if (isset($rules['max_length']) && strlen($value) > $rules['max_length']) {
            return false;
        }

        return true;
    }

    /**
     * Get setting by key
     */
    public static function get($key, $default = null, $category = null)
    {
        $query = static::where('key', $key)->where('is_active', true);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        $setting = $query->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->typed_value;
    }

    /**
     * Set setting by key
     */
    public static function set($key, $value, $type = 'string', $category = 'general', $group = 'default')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category,
                'group_name' => $group,
                'is_active' => true,
            ]
        );
    }

    /**
     * Get settings by category
     */
    public static function getByCategory($category, $group = null)
    {
        $query = static::where('category', $category)->where('is_active', true);
        
        if ($group) {
            $query->where('group_name', $group);
        }
        
        return $query->ordered()->get();
    }

    /**
     * Get all settings grouped by category
     */
    public static function getAllGrouped()
    {
        return static::where('is_active', true)
            ->ordered()
            ->get()
            ->groupBy('category');
    }

    /**
     * Get available categories
     */
    public static function getCategories()
    {
        return [
            'general' => 'การตั้งค่าทั่วไป',
            'performance' => 'การตั้งค่าประสิทธิภาพ',
            'backup' => 'การตั้งค่าสำรองข้อมูล',
            'email' => 'การตั้งค่าอีเมล',
            'security' => 'การตั้งค่าความปลอดภัย',
            'system' => 'การตั้งค่าระบบ',
        ];
    }

    /**
     * Get available types
     */
    public static function getTypes()
    {
        return [
            'string' => 'String',
            'boolean' => 'Boolean',
            'integer' => 'Integer',
            'float' => 'Float',
            'email' => 'Email',
            'url' => 'URL',
            'json' => 'JSON',
            'array' => 'Array',
        ];
    }

    /**
     * Check if setting is system setting
     */
    public function isSystemSetting()
    {
        return $this->category === 'system';
    }

    /**
     * Get formatted value for display
     */
    public function getFormattedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return $this->typed_value ? 'เปิด' : 'ปิด';
            case 'json':
            case 'array':
                return json_encode($this->typed_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            default:
                return $this->typed_value;
        }
    }

    /**
     * Get type icon for display
     */
    public function getTypeIconAttribute()
    {
        $icons = [
            'string' => 'fas fa-font',
            'boolean' => 'fas fa-toggle-on',
            'integer' => 'fas fa-hashtag',
            'float' => 'fas fa-calculator',
            'email' => 'fas fa-envelope',
            'url' => 'fas fa-link',
            'json' => 'fas fa-code',
            'array' => 'fas fa-list',
        ];

        return $icons[$this->type] ?? 'fas fa-cog';
    }
}