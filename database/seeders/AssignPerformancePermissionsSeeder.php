<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class AssignPerformancePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found!');
            return;
        }

        // Get performance permissions
        $performancePermissions = Permission::where('group', 'performance')->get();
        
        if ($performancePermissions->isEmpty()) {
            $this->command->error('Performance permissions not found!');
            return;
        }

        // Assign permissions to admin role
        foreach ($performancePermissions as $permission) {
            $adminRole->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $this->command->info('Performance permissions assigned to admin role successfully!');
    }
}