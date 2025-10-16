<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user for testing
        $user = User::first();
        
        if (!$user) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // Create sample audit logs
        $auditLogs = [
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'login',
                'description' => 'เข้าสู่ระบบ',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'settings_update',
                'description' => 'อัปเดตการตั้งค่า Audit Log',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'old_values' => ['audit_enabled' => false, 'audit_retention' => 30],
                'new_values' => ['audit_enabled' => true, 'audit_retention' => 90],
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'profile_update',
                'description' => 'แก้ไขโปรไฟล์ผู้ใช้',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'old_values' => ['name' => 'ผู้ใช้เดิม'],
                'new_values' => ['name' => $user->name],
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subHours(3)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'password_change',
                'description' => 'เปลี่ยนรหัสผ่าน',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'logout',
                'description' => 'ออกจากระบบ',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'login',
                'description' => 'เข้าสู่ระบบ',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'status' => 'failed',
                'error_message' => 'รหัสผ่านไม่ถูกต้อง',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'export',
                'description' => 'ส่งออกข้อมูลผู้ใช้',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => 'audit_clear',
                'description' => 'ล้าง Audit Logs เก่า',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'status' => 'success',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5)
            ]
        ];

        foreach ($auditLogs as $logData) {
            AuditLog::create($logData);
        }

        $this->command->info('Audit logs seeded successfully!');
    }
}
