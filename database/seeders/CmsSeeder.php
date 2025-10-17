<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'group' => 'dashboard'],
            
            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'group' => 'users'],
            
            // Roles
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'group' => 'roles'],
            
            // Permissions
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'group' => 'permissions'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions', 'group' => 'permissions'],
            ['name' => 'permissions.edit', 'display_name' => 'Edit Permissions', 'group' => 'permissions'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions', 'group' => 'permissions'],
            
            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'group' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'group' => 'settings'],
            
            // Audit Logs
            ['name' => 'audit_logs.view', 'display_name' => 'View Audit Logs', 'group' => 'audit_logs'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system access',
            'is_system' => true,
        ]);

        $editorRole = Role::create([
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Content editor with limited access',
            'is_system' => false,
        ]);

        // Assign all permissions to admin role
        $adminRole->permissions()->sync(Permission::pluck('id'));

        // Assign limited permissions to editor role
        $editorPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'users.view',
            'settings.view',
        ])->pluck('id');
        
        $editorRole->permissions()->sync($editorPermissions);

        // Create Admin User
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $adminUser->roles()->attach($adminRole->id);

        // Create Default Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'CMS System', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Content Management System', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'admin@example.com', 'type' => 'string', 'group' => 'email'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'enable_audit_log', 'value' => 'true', 'type' => 'boolean', 'group' => 'security'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
