<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'ดู Dashboard',
                'description' => 'สามารถเข้าถึงหน้า Dashboard',
                'group' => 'Dashboard',
                'is_active' => true,
            ],
            [
                'name' => 'dashboard.statistics',
                'display_name' => 'ดูสถิติ',
                'description' => 'สามารถดูสถิติต่างๆ ในระบบ',
                'group' => 'Dashboard',
                'is_active' => true,
            ],

            // User Management
            [
                'name' => 'user.view',
                'display_name' => 'ดูข้อมูลผู้ใช้',
                'description' => 'สามารถดูรายการและรายละเอียดของผู้ใช้',
                'group' => 'User Management',
                'is_active' => true,
            ],
            [
                'name' => 'user.create',
                'display_name' => 'สร้างผู้ใช้',
                'description' => 'สามารถสร้างผู้ใช้ใหม่',
                'group' => 'User Management',
                'is_active' => true,
            ],
            [
                'name' => 'user.edit',
                'display_name' => 'แก้ไขผู้ใช้',
                'description' => 'สามารถแก้ไขข้อมูลผู้ใช้',
                'group' => 'User Management',
                'is_active' => true,
            ],
            [
                'name' => 'user.delete',
                'display_name' => 'ลบผู้ใช้',
                'description' => 'สามารถลบผู้ใช้',
                'group' => 'User Management',
                'is_active' => true,
            ],
            [
                'name' => 'user.toggle-status',
                'display_name' => 'เปลี่ยนสถานะผู้ใช้',
                'description' => 'สามารถเปิด/ปิดใช้งานผู้ใช้',
                'group' => 'User Management',
                'is_active' => true,
            ],

            // Role Management
            [
                'name' => 'role.view',
                'display_name' => 'ดูข้อมูลบทบาท',
                'description' => 'สามารถดูรายการและรายละเอียดของบทบาท',
                'group' => 'Role Management',
                'is_active' => true,
            ],
            [
                'name' => 'role.create',
                'display_name' => 'สร้างบทบาท',
                'description' => 'สามารถสร้างบทบาทใหม่',
                'group' => 'Role Management',
                'is_active' => true,
            ],
            [
                'name' => 'role.edit',
                'display_name' => 'แก้ไขบทบาท',
                'description' => 'สามารถแก้ไขข้อมูลบทบาท',
                'group' => 'Role Management',
                'is_active' => true,
            ],
            [
                'name' => 'role.delete',
                'display_name' => 'ลบบทบาท',
                'description' => 'สามารถลบบทบาท',
                'group' => 'Role Management',
                'is_active' => true,
            ],
            [
                'name' => 'role.assign-permissions',
                'display_name' => 'กำหนดสิทธิ์ให้บทบาท',
                'description' => 'สามารถกำหนดสิทธิ์ให้กับบทบาท',
                'group' => 'Role Management',
                'is_active' => true,
            ],

            // Permission Management
            [
                'name' => 'permission.view',
                'display_name' => 'ดูข้อมูลสิทธิ์',
                'description' => 'สามารถดูรายการและรายละเอียดของสิทธิ์',
                'group' => 'Permission Management',
                'is_active' => true,
            ],
            [
                'name' => 'permission.create',
                'display_name' => 'สร้างสิทธิ์',
                'description' => 'สามารถสร้างสิทธิ์ใหม่',
                'group' => 'Permission Management',
                'is_active' => true,
            ],
            [
                'name' => 'permission.edit',
                'display_name' => 'แก้ไขสิทธิ์',
                'description' => 'สามารถแก้ไขข้อมูลสิทธิ์',
                'group' => 'Permission Management',
                'is_active' => true,
            ],
            [
                'name' => 'permission.delete',
                'display_name' => 'ลบสิทธิ์',
                'description' => 'สามารถลบสิทธิ์',
                'group' => 'Permission Management',
                'is_active' => true,
            ],

            // Backend Access
            [
                'name' => 'backend.access',
                'display_name' => 'เข้าถึง Backend',
                'description' => 'สามารถเข้าถึงระบบ Backend',
                'group' => 'Backend Access',
                'is_active' => true,
            ],

            // Settings
            [
                'name' => 'settings.view',
                'display_name' => 'ดูการตั้งค่า',
                'description' => 'สามารถดูการตั้งค่าระบบ',
                'group' => 'Settings',
                'is_active' => true,
            ],
            [
                'name' => 'settings.create',
                'display_name' => 'สร้างการตั้งค่า',
                'description' => 'สามารถสร้างการตั้งค่าใหม่',
                'group' => 'Settings',
                'is_active' => true,
            ],
            [
                'name' => 'settings.edit',
                'display_name' => 'แก้ไขการตั้งค่า',
                'description' => 'สามารถแก้ไขการตั้งค่าระบบ',
                'group' => 'Settings',
                'is_active' => true,
            ],
            [
                'name' => 'settings.delete',
                'display_name' => 'ลบการตั้งค่า',
                'description' => 'สามารถลบการตั้งค่า',
                'group' => 'Settings',
                'is_active' => true,
            ],
            [
                'name' => 'performance.view',
                'display_name' => 'ดูการตั้งค่าประสิทธิภาพ',
                'description' => 'สามารถดูการตั้งค่าประสิทธิภาพ',
                'group' => 'Settings',
                'is_active' => true,
            ],
            [
                'name' => 'performance.edit',
                'display_name' => 'แก้ไขการตั้งค่าประสิทธิภาพ',
                'description' => 'สามารถแก้ไขการตั้งค่าประสิทธิภาพ',
                'group' => 'Settings',
                'is_active' => true,
            ],
            [
                'name' => 'performance.cache-clear',
                'display_name' => 'ล้าง Cache',
                'description' => 'สามารถล้าง Cache ของระบบ',
                'group' => 'Settings',
                'is_active' => true,
            ],

            // Audit Logs
            [
                'name' => 'audit-log.view',
                'display_name' => 'ดู Audit Log',
                'description' => 'สามารถดูประวัติการใช้งานระบบ',
                'group' => 'Audit Logs',
                'is_active' => true,
            ],
            [
                'name' => 'audit-log.export',
                'display_name' => 'ส่งออก Audit Log',
                'description' => 'สามารถส่งออกข้อมูล Audit Log',
                'group' => 'Audit Logs',
                'is_active' => true,
            ],

            // Backup Settings
            [
                'name' => 'backup.view',
                'display_name' => 'ดูการตั้งค่าสำรองข้อมูล',
                'description' => 'สามารถดูการตั้งค่าสำรองข้อมูล',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
            [
                'name' => 'backup.create',
                'display_name' => 'สร้างการตั้งค่าสำรองข้อมูล',
                'description' => 'สามารถสร้างการตั้งค่าสำรองข้อมูลใหม่',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
            [
                'name' => 'backup.edit',
                'display_name' => 'แก้ไขการตั้งค่าสำรองข้อมูล',
                'description' => 'สามารถแก้ไขการตั้งค่าสำรองข้อมูล',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
            [
                'name' => 'backup.delete',
                'display_name' => 'ลบการตั้งค่าสำรองข้อมูล',
                'description' => 'สามารถลบการตั้งค่าสำรองข้อมูล',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
            [
                'name' => 'settings-backup-access',
                'display_name' => 'เข้าถึงการตั้งค่าสำรองข้อมูล',
                'description' => 'สามารถเข้าถึงหน้าตั้งค่าสำรองข้อมูล',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
            [
                'name' => 'backup-manage',
                'display_name' => 'จัดการสำรองข้อมูล',
                'description' => 'สามารถจัดการการสำรองข้อมูล',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
            [
                'name' => 'system-backup',
                'display_name' => 'สำรองข้อมูลระบบ',
                'description' => 'สามารถสำรองข้อมูลระบบ',
                'group' => 'Backup Settings',
                'is_active' => true,
            ],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command->info('Permissions seeded successfully!');
    }

    private function assignPermissionsToRoles()
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();

        if ($adminRole) {
            // Admin gets all permissions
            $adminRole->permissions()->sync(Permission::pluck('id'));
        }

        if ($editorRole) {
            // Editor gets limited permissions
            $editorPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'dashboard.statistics',
                'user.view',
                'settings.view',
            ])->pluck('id');
            
            $editorRole->permissions()->sync($editorPermissions);
        }
    }
}