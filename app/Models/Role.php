<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'core_roles';
    
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'core_role_permissions');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'core_user_roles');
    }

    // Helper methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeNonSystem($query)
    {
        return $query->where('is_system', false);
    }

    public function getPermissionNamesAttribute()
    {
        return $this->permissions->pluck('name')->toArray();
    }

    public function getUserCountAttribute()
    {
        return $this->users()->count();
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('name', $permission)->exists();
        }
        
        return $this->permissions()->where('permission_id', $permission->id)->exists();
    }

    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        
        return $this->permissions()->whereIn('name', $permissions)->count() === count($permissions);
    }

    public function canBeDeleted()
    {
        return !$this->is_system && $this->users()->count() === 0;
    }
}
