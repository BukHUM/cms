<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use Carbon\Carbon;

class NaturalAuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลที่ดูเป็นธรรมชาติจากการใช้งานจริง
        $naturalLogs = [
            // วันนี้ - การใช้งานล่าสุด
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'login',
                'description' => 'เข้าสู่ระบบ Admin Panel',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subMinutes(2),
                'updated_at' => Carbon::now()->subMinutes(2)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'view',
                'resource_type' => 'settings',
                'description' => 'เข้าดูหน้า Settings',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subMinutes(1),
                'updated_at' => Carbon::now()->subMinutes(1)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'settings_update',
                'description' => 'แก้ไขการตั้งค่าทั่วไป',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subSeconds(30),
                'updated_at' => Carbon::now()->subSeconds(30)
            ],
            
            // เมื่อวาน - การใช้งานปกติ
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'login',
                'description' => 'เข้าสู่ระบบ Admin Panel',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::yesterday()->addHours(9)->addMinutes(30),
                'updated_at' => Carbon::yesterday()->addHours(9)->addMinutes(30)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'view',
                'resource_type' => 'users',
                'description' => 'ดูรายการผู้ใช้',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::yesterday()->addHours(9)->addMinutes(45),
                'updated_at' => Carbon::yesterday()->addHours(9)->addMinutes(45)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'create',
                'resource_type' => 'user',
                'description' => 'สร้างผู้ใช้ใหม่: john.doe@example.com',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::yesterday()->addHours(10)->addMinutes(15),
                'updated_at' => Carbon::yesterday()->addHours(10)->addMinutes(15)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'logout',
                'description' => 'ออกจากระบบ Admin Panel',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::yesterday()->addHours(12),
                'updated_at' => Carbon::yesterday()->addHours(12)
            ],
            
            // 2 วันที่แล้ว - การใช้งานอื่นๆ
            [
                'user_id' => '2',
                'user_email' => 'manager@example.com',
                'action' => 'login',
                'description' => 'เข้าสู่ระบบ Admin Panel',
                'ip_address' => '192.168.1.102',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(2)->addHours(14),
                'updated_at' => Carbon::now()->subDays(2)->addHours(14)
            ],
            [
                'user_id' => '2',
                'user_email' => 'manager@example.com',
                'action' => 'view',
                'resource_type' => 'reports',
                'description' => 'ดูรายงานการใช้งาน',
                'ip_address' => '192.168.1.102',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(2)->addHours(14)->addMinutes(20),
                'updated_at' => Carbon::now()->subDays(2)->addHours(14)->addMinutes(20)
            ],
            [
                'user_id' => '2',
                'user_email' => 'manager@example.com',
                'action' => 'export',
                'resource_type' => 'reports',
                'description' => 'ส่งออกรายงาน Excel',
                'ip_address' => '192.168.1.102',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(2)->addHours(14)->addMinutes(35),
                'updated_at' => Carbon::now()->subDays(2)->addHours(14)->addMinutes(35)
            ],
            [
                'user_id' => '2',
                'user_email' => 'manager@example.com',
                'action' => 'logout',
                'description' => 'ออกจากระบบ Admin Panel',
                'ip_address' => '192.168.1.102',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(2)->addHours(15),
                'updated_at' => Carbon::now()->subDays(2)->addHours(15)
            ],
            
            // 3 วันที่แล้ว - การเข้าสู่ระบบที่ล้มเหลว
            [
                'user_id' => null,
                'user_email' => 'hacker@example.com',
                'action' => 'login',
                'description' => 'พยายามเข้าสู่ระบบ',
                'ip_address' => '203.0.113.1',
                'status' => 'failed',
                'error_message' => 'รหัสผ่านไม่ถูกต้อง',
                'created_at' => Carbon::now()->subDays(3)->addHours(2),
                'updated_at' => Carbon::now()->subDays(3)->addHours(2)
            ],
            [
                'user_id' => null,
                'user_email' => 'hacker@example.com',
                'action' => 'login',
                'description' => 'พยายามเข้าสู่ระบบ',
                'ip_address' => '203.0.113.1',
                'status' => 'failed',
                'error_message' => 'รหัสผ่านไม่ถูกต้อง',
                'created_at' => Carbon::now()->subDays(3)->addHours(2)->addMinutes(5),
                'updated_at' => Carbon::now()->subDays(3)->addHours(2)->addMinutes(5)
            ],
            [
                'user_id' => null,
                'user_email' => 'hacker@example.com',
                'action' => 'login',
                'description' => 'พยายามเข้าสู่ระบบ',
                'ip_address' => '203.0.113.1',
                'status' => 'failed',
                'error_message' => 'บัญชีถูกระงับชั่วคราว',
                'created_at' => Carbon::now()->subDays(3)->addHours(2)->addMinutes(10),
                'updated_at' => Carbon::now()->subDays(3)->addHours(2)->addMinutes(10)
            ],
            
            // 4 วันที่แล้ว - การใช้งานปกติ
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'login',
                'description' => 'เข้าสู่ระบบ Admin Panel',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(4)->addHours(8),
                'updated_at' => Carbon::now()->subDays(4)->addHours(8)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'settings_update',
                'description' => 'แก้ไขการตั้งค่าความปลอดภัย',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(4)->addHours(8)->addMinutes(30),
                'updated_at' => Carbon::now()->subDays(4)->addHours(8)->addMinutes(30)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'view',
                'resource_type' => 'audit',
                'description' => 'ดู Audit Logs',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(4)->addHours(9),
                'updated_at' => Carbon::now()->subDays(4)->addHours(9)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'export',
                'resource_type' => 'audit',
                'description' => 'ส่งออก Audit Logs',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(4)->addHours(9)->addMinutes(15),
                'updated_at' => Carbon::now()->subDays(4)->addHours(9)->addMinutes(15)
            ],
            [
                'user_id' => '1',
                'user_email' => 'admin@example.com',
                'action' => 'logout',
                'description' => 'ออกจากระบบ Admin Panel',
                'ip_address' => '192.168.1.100',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(4)->addHours(10),
                'updated_at' => Carbon::now()->subDays(4)->addHours(10)
            ]
        ];

        foreach ($naturalLogs as $log) {
            AuditLog::create($log);
        }

        $this->command->info('สร้างข้อมูล Audit Log ที่เป็นธรรมชาติสำเร็จ!');
    }
}
