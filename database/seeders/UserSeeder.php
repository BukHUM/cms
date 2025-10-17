<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all available roles
        $roles = Role::all();
        
        if ($roles->isEmpty()) {
            $this->command->warn('No roles found. Please run RoleSeeder first.');
            return;
        }

        // Thai names for realistic data
        $thaiNames = [
            'สมชาย ใจดี', 'สมหญิง รักดี', 'วิชัย เก่งมาก', 'สุภาพ ใจงาม', 'ประเสริฐ ดีเลิศ',
            'มาลี สวยงาม', 'สมศักดิ์ เก่งกาจ', 'นิตยา ใจดี', 'สมพร รักดี', 'วิชัย เก่งมาก',
            'สุภาพ ใจงาม', 'ประเสริฐ ดีเลิศ', 'มาลี สวยงาม', 'สมศักดิ์ เก่งกาจ', 'นิตยา ใจดี',
            'สมพร รักดี', 'วิชัย เก่งมาก', 'สุภาพ ใจงาม', 'ประเสริฐ ดีเลิศ', 'มาลี สวยงาม',
            'สมศักดิ์ เก่งกาจ', 'นิตยา ใจดี', 'สมพร รักดี', 'วิชัย เก่งมาก', 'สุภาพ ใจงาม'
        ];

        // Status options
        $statuses = ['active', 'inactive', 'pending'];

        // Phone number prefixes
        $phonePrefixes = ['081', '082', '083', '084', '085', '086', '087', '088', '089'];

        $this->command->info('Creating 25 users...');

        for ($i = 0; $i < 25; $i++) {
            // Generate unique email
            $email = 'user' . ($i + 1) . '@example.com';
            
            // Check if user already exists
            if (User::where('email', $email)->exists()) {
                continue;
            }

            // Generate phone number
            $phonePrefix = $phonePrefixes[array_rand($phonePrefixes)];
            $phoneSuffix = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $phone = $phonePrefix . '-' . substr($phoneSuffix, 0, 3) . '-' . substr($phoneSuffix, 3, 4);

            // Create user
            $user = User::create([
                'name' => $thaiNames[$i],
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make('password123'), // Default password
                'status' => $statuses[array_rand($statuses)],
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'last_login_at' => now()->subDays(rand(1, 7)),
            ]);

            // Assign random roles (1-3 roles per user)
            $numberOfRoles = rand(1, min(3, $roles->count()));
            $randomRoles = $roles->random($numberOfRoles);
            
            foreach ($randomRoles as $role) {
                $user->roles()->attach($role->id);
            }

            $this->command->line("Created user: {$user->name} ({$user->email}) with {$numberOfRoles} role(s)");
        }

        $this->command->info('Successfully created 25 users!');
        
        // Display summary
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $pendingUsers = User::where('status', 'pending')->count();
        
        $this->command->info("Summary:");
        $this->command->info("- Active users: {$activeUsers}");
        $this->command->info("- Inactive users: {$inactiveUsers}");
        $this->command->info("- Pending users: {$pendingUsers}");
        $this->command->info("- Total users: " . User::count());
    }
}
