<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PerformancePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'performance.view',
                'display_name' => 'View Performance Settings',
                'description' => 'สามารถดูการตั้งค่าประสิทธิภาพ',
                'group' => 'performance',
                'is_active' => true,
            ],
            [
                'name' => 'performance.edit',
                'display_name' => 'Edit Performance Settings',
                'description' => 'สามารถแก้ไขการตั้งค่าประสิทธิภาพ',
                'group' => 'performance',
                'is_active' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}