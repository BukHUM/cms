<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laravel_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'bio',
        'avatar',
        'status',
        'last_login_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the roles for the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'laravel_user_roles', 'user_id', 'role_id');
    }

    /**
     * Check if user has role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('slug', $permissions);
            })
            ->exists();
    }

    /**
     * Assign role to user
     */
    public function assignRole(Role $role): void
    {
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Sync roles for user
     */
    public function syncRoles(array $roles): void
    {
        $this->roles()->sync($roles);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Get user's display name
     */
    public function getDisplayName(): string
    {
        return $this->name ?: 'User';
    }

    /**
     * Get user's primary role
     */
    public function getPrimaryRole(): ?Role
    {
        return $this->roles()->orderBy('sort_order')->first();
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        $primaryRole = $this->getPrimaryRole();
        return $primaryRole ? $primaryRole->name : 'User';
    }

    /**
     * Get user's status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'pending' => 'bg-warning',
            'suspended' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get user's status display name
     */
    public function getStatusDisplayName(): string
    {
        return match($this->status) {
            'active' => 'ใช้งาน',
            'inactive' => 'ไม่ใช้งาน',
            'pending' => 'รอการยืนยัน',
            'suspended' => 'ระงับการใช้งาน',
            default => 'ไม่ทราบสถานะ'
        };
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get user's avatar URL
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Return default avatar based on user's name
        $initials = $this->getInitials();
        return "https://ui-avatars.com/api/?name={$initials}&background=3b82f6&color=ffffff&size=200";
    }

    /**
     * Get user's initials
     */
    public function getInitials(): string
    {
        $name = trim($this->name);
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }

    /**
     * Get user's full profile data
     */
    public function getProfileData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'avatar_url' => $this->getAvatarUrl(),
            'status' => $this->status,
            'status_display' => $this->getStatusDisplayName(),
            'role' => $this->getRoleDisplayName(),
            'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
