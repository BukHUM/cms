<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'core_settings';
    
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'value' => 'string',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_public', false);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function getFormattedValueAttribute()
    {
        return $this->castValue($this->value, $this->type);
    }

    public function getTypeIconAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return 'fas fa-toggle-on';
            case 'integer':
            case 'float':
                return 'fas fa-hashtag';
            case 'json':
                return 'fas fa-code';
            case 'email':
                return 'fas fa-envelope';
            case 'url':
                return 'fas fa-link';
            default:
                return 'fas fa-text-width';
        }
    }

    public function getTypeColorAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return 'bg-green-100 text-green-800';
            case 'integer':
            case 'float':
                return 'bg-blue-100 text-blue-800';
            case 'json':
                return 'bg-purple-100 text-purple-800';
            case 'email':
                return 'bg-yellow-100 text-yellow-800';
            case 'url':
                return 'bg-indigo-100 text-indigo-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getGroupColorAttribute()
    {
        switch ($this->group) {
            case 'general':
                return 'bg-blue-100 text-blue-800';
            case 'system':
                return 'bg-purple-100 text-purple-800';
            case 'email':
                return 'bg-yellow-100 text-yellow-800';
            case 'security':
                return 'bg-red-100 text-red-800';
            case 'appearance':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function canBeDeleted()
    {
        // System settings cannot be deleted
        return $this->group !== 'system';
    }

    public function isSystemSetting()
    {
        return $this->group === 'system';
    }
}
