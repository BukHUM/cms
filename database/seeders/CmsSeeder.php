<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Role
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'ผู้ดูแลระบบ - มีสิทธิ์เข้าถึงทุกฟีเจอร์',
                'is_system' => true,
            ]
        );

        // Create Editor Role
        $editorRole = Role::updateOrCreate(
            ['name' => 'editor'],
            [
                'display_name' => 'Editor',
                'description' => 'ผู้แก้ไขเนื้อหา - มีสิทธิ์จำกัด',
                'is_system' => false,
            ]
        );

        // Create Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role to admin user
        if (!$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminUser->roles()->attach($adminRole->id);
        }

        // Create Test Editor User
        $editorUser = User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('editor123'),
                'first_name' => 'Editor',
                'last_name' => 'User',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign editor role to editor user
        if (!$editorUser->roles()->where('role_id', $editorRole->id)->exists()) {
            $editorUser->roles()->attach($editorRole->id);
        }

        $this->command->info('CMS Users and Roles created successfully!');
        $this->command->info('Admin Login: admin@example.com / admin123');
        $this->command->info('Editor Login: editor@example.com / editor123');
    }
}
