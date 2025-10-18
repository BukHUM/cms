<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'core_performance_settings';

    protected $fillable = [
        'name',
        'key',
        'value',
        'type',
        'description',
        'is_active',
        'category',
        'sort_order',
        'validation_rules',
        'default_value',
        'options',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'validation_rules' => 'array',
        'options' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created this performance setting
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this performance setting
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
     * Scope for settings by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for ordered settings
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
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
     * Get performance categories
     */
    public static function getCategories()
    {
        return [
            'cache' => 'Cache Settings',
            'database' => 'Database Settings',
            'memory' => 'Memory Settings',
            'session' => 'Session Settings',
            'queue' => 'Queue Settings',
            'logging' => 'Logging Settings',
            'optimization' => 'Optimization Settings',
        ];
    }

    /**
     * Get performance types
     */
    public static function getTypes()
    {
        return [
            'string' => 'String',
            'integer' => 'Integer',
            'float' => 'Float',
            'boolean' => 'Boolean',
            'array' => 'Array',
            'json' => 'JSON',
        ];
    }
}
