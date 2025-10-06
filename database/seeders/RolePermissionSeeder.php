<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างบทบาทเริ่มต้น
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'ผู้ดูแลระบบสูงสุด มีสิทธิ์เข้าถึงทุกฟังก์ชัน',
                'color' => '#dc3545',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'ผู้ดูแลระบบ มีสิทธิ์จัดการผู้ใช้และระบบ',
                'color' => '#fd7e14',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'ผู้ดูแลเนื้อหา มีสิทธิ์จัดการเนื้อหาและผู้ใช้ทั่วไป',
                'color' => '#20c997',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'ผู้ใช้ทั่วไป มีสิทธิ์เข้าถึงฟังก์ชันพื้นฐาน',
                'color' => '#6c757d',
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('laravel_roles')->insert($roles);

        // สร้างสิทธิ์เริ่มต้น
        $permissions = [
            // ระบบผู้ใช้
            [
                'name' => 'จัดการผู้ใช้',
                'slug' => 'users.manage',
                'description' => 'สามารถจัดการผู้ใช้ทั้งหมด',
                'group' => 'users',
                'action' => 'manage',
                'resource' => 'users',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ดูผู้ใช้',
                'slug' => 'users.view',
                'description' => 'สามารถดูข้อมูลผู้ใช้',
                'group' => 'users',
                'action' => 'view',
                'resource' => 'users',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'เพิ่มผู้ใช้',
                'slug' => 'users.create',
                'description' => 'สามารถเพิ่มผู้ใช้ใหม่',
                'group' => 'users',
                'action' => 'create',
                'resource' => 'users',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'แก้ไขผู้ใช้',
                'slug' => 'users.update',
                'description' => 'สามารถแก้ไขข้อมูลผู้ใช้',
                'group' => 'users',
                'action' => 'update',
                'resource' => 'users',
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ลบผู้ใช้',
                'slug' => 'users.delete',
                'description' => 'สามารถลบผู้ใช้',
                'group' => 'users',
                'action' => 'delete',
                'resource' => 'users',
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ระบบบทบาทและสิทธิ์
            [
                'name' => 'จัดการบทบาท',
                'slug' => 'roles.manage',
                'description' => 'สามารถจัดการบทบาททั้งหมด',
                'group' => 'roles',
                'action' => 'manage',
                'resource' => 'roles',
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'จัดการสิทธิ์',
                'slug' => 'permissions.manage',
                'description' => 'สามารถจัดการสิทธิ์ทั้งหมด',
                'group' => 'permissions',
                'action' => 'manage',
                'resource' => 'permissions',
                'is_active' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ระบบการตั้งค่า
            [
                'name' => 'จัดการการตั้งค่า',
                'slug' => 'settings.manage',
                'description' => 'สามารถจัดการการตั้งค่าระบบ',
                'group' => 'settings',
                'action' => 'manage',
                'resource' => 'settings',
                'is_active' => true,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ระบบ Audit Log
            [
                'name' => 'ดู Audit Log',
                'slug' => 'audit.view',
                'description' => 'สามารถดู Audit Log',
                'group' => 'audit',
                'action' => 'view',
                'resource' => 'audit',
                'is_active' => true,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'จัดการ Audit Log',
                'slug' => 'audit.manage',
                'description' => 'สามารถจัดการ Audit Log',
                'group' => 'audit',
                'action' => 'manage',
                'resource' => 'audit',
                'is_active' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ระบบ Dashboard
            [
                'name' => 'เข้าถึง Dashboard',
                'slug' => 'dashboard.access',
                'description' => 'สามารถเข้าถึง Dashboard',
                'group' => 'dashboard',
                'action' => 'access',
                'resource' => 'dashboard',
                'is_active' => true,
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('laravel_permissions')->insert($permissions);

        // กำหนดสิทธิ์ให้กับบทบาท
        $rolePermissions = [
            // Super Admin - มีสิทธิ์ทั้งหมด
            ['role_id' => 1, 'permission_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 11, 'created_at' => now(), 'updated_at' => now()],

            // Admin - มีสิทธิ์ส่วนใหญ่ยกเว้นการจัดการสิทธิ์
            ['role_id' => 2, 'permission_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 11, 'created_at' => now(), 'updated_at' => now()],

            // Moderator - มีสิทธิ์จำกัด
            ['role_id' => 3, 'permission_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'permission_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'permission_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'permission_id' => 11, 'created_at' => now(), 'updated_at' => now()],

            // User - มีสิทธิ์พื้นฐาน
            ['role_id' => 4, 'permission_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 4, 'permission_id' => 11, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('laravel_role_permissions')->insert($rolePermissions);
    }
}