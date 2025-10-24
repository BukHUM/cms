<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run essential seeders
        $this->call([
            CmsSeeder::class,           // Users and Roles
            PermissionSeeder::class,    // Permissions and assign to roles
            AllSettingsSeeder::class,   // Basic settings
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('You can now login with:');
        $this->command->info('Admin: admin@example.com / admin123');
        $this->command->info('Editor: editor@example.com / editor123');
    }
}
