<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test roles if they don't exist
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'display_name' => 'ผู้ดูแลระบบ',
            'description' => 'ผู้ดูแลระบบที่มีสิทธิ์เต็ม',
            'is_active' => true,
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user',
        ], [
            'display_name' => 'ผู้ใช้งานทั่วไป',
            'description' => 'ผู้ใช้งานทั่วไปในระบบ',
            'is_active' => true,
        ]);

        // Create test users
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user1 = User::firstOrCreate([
            'email' => 'user1@example.com',
        ], [
            'name' => 'user1',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user2 = User::firstOrCreate([
            'email' => 'user2@example.com',
        ], [
            'name' => 'user2',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'password' => Hash::make('password'),
            'is_active' => false,
            'email_verified_at' => null,
        ]);

        // Assign roles
        if (!$admin->hasRole('admin')) {
            $admin->roles()->attach($adminRole->id);
        }

        if (!$user1->hasRole('user')) {
            $user1->roles()->attach($userRole->id);
        }

        if (!$user2->hasRole('user')) {
            $user2->roles()->attach($userRole->id);
        }

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('User 1: user1@example.com / password');
        $this->command->info('User 2: user2@example.com / password');
    }
}