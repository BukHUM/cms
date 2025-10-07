<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => config('app.name', 'Laravel Admin Panel'),
                'type' => 'string',
                'description' => 'ชื่อเว็บไซต์'
            ],
            [
                'key' => 'site_url',
                'value' => config('app.url', 'http://localhost'),
                'type' => 'string',
                'description' => 'URL เว็บไซต์'
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Bangkok',
                'type' => 'string',
                'description' => 'เขตเวลา'
            ],
            [
                'key' => 'language',
                'value' => 'th',
                'type' => 'string',
                'description' => 'ภาษา'
            ],
            [
                'key' => 'site_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานเว็บไซต์'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'โหมดบำรุงรักษา'
            ],
            [
                'key' => 'debug_mode',
                'value' => config('app.debug', false) ? '1' : '0',
                'type' => 'boolean',
                'description' => 'โหมด Debug'
            ],
            [
                'key' => 'auto_save',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'การบันทึกอัตโนมัติ'
            ],
            [
                'key' => 'notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'การแจ้งเตือน'
            ],
            [
                'key' => 'analytics',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'การวิเคราะห์ข้อมูล'
            ],
            [
                'key' => 'updates',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'การอัปเดตอัตโนมัติ'
            ],

            // Email Settings
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => 'string',
                'description' => 'Mail Driver'
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.gmail.com',
                'type' => 'string',
                'description' => 'Mail Host'
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'type' => 'integer',
                'description' => 'Mail Port'
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'type' => 'string',
                'description' => 'Mail Username'
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => 'string',
                'description' => 'Mail Password'
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => 'string',
                'description' => 'Mail Encryption'
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@example.com',
                'type' => 'string',
                'description' => 'Mail From Address'
            ],
            [
                'key' => 'mail_from_name',
                'value' => config('app.name', 'Laravel Admin Panel'),
                'type' => 'string',
                'description' => 'Mail From Name'
            ],
            [
                'key' => 'mail_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานการส่งอีเมล'
            ],

            // Security Settings
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'description' => 'ความยาวรหัสผ่านขั้นต่ำ'
            ],
            [
                'key' => 'password_require_uppercase',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'ต้องมีตัวอักษรพิมพ์ใหญ่'
            ],
            [
                'key' => 'password_require_lowercase',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'ต้องมีตัวอักษรพิมพ์เล็ก'
            ],
            [
                'key' => 'password_require_numbers',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'ต้องมีตัวเลข'
            ],
            [
                'key' => 'password_require_special_chars',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'ต้องมีอักขระพิเศษ'
            ],
            [
                'key' => 'session_timeout',
                'value' => '30',
                'type' => 'integer',
                'description' => 'ระยะเวลา Session (นาที)'
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'type' => 'integer',
                'description' => 'ระยะเวลาล็อค (นาที)'
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'description' => 'จำนวนครั้งการเข้าสู่ระบบสูงสุด'
            ],
            [
                'key' => 'two_factor_auth',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Two-Factor Authentication'
            ],
            [
                'key' => 'ip_whitelist',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'IP Whitelist'
            ],

            // Backup Settings
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'string',
                'description' => 'ความถี่การสำรองข้อมูล'
            ],
            [
                'key' => 'backup_time',
                'value' => '02:00',
                'type' => 'string',
                'description' => 'เวลาสำรองข้อมูล'
            ],
            [
                'key' => 'backup_retention',
                'value' => '30',
                'type' => 'integer',
                'description' => 'เก็บไฟล์สำรอง (วัน)'
            ],
            [
                'key' => 'backup_location',
                'value' => 'local',
                'type' => 'string',
                'description' => 'ตำแหน่งเก็บไฟล์สำรอง'
            ],
            [
                'key' => 'backup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานการสำรองข้อมูลอัตโนมัติ'
            ],

            // Audit Settings
            [
                'key' => 'audit_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งาน Audit Log'
            ],
            [
                'key' => 'audit_retention',
                'value' => '90',
                'type' => 'integer',
                'description' => 'เก็บข้อมูล Audit Log (วัน)'
            ],
            [
                'key' => 'audit_level',
                'value' => 'basic',
                'type' => 'string',
                'description' => 'ระดับการบันทึก Audit Log'
            ],

            // Performance Settings
            [
                'key' => 'cache_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งาน Cache'
            ],
            [
                'key' => 'cache_driver',
                'value' => 'file',
                'type' => 'string',
                'description' => 'Cache Driver'
            ],
            [
                'key' => 'cache_ttl',
                'value' => '60',
                'type' => 'integer',
                'description' => 'Cache TTL (นาที)'
            ],
            [
                'key' => 'query_logging',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'บันทึก Query Log'
            ],
            [
                'key' => 'slow_query_threshold',
                'value' => '1000',
                'type' => 'integer',
                'description' => 'Slow Query Threshold (ms)'
            ],
            [
                'key' => 'memory_limit',
                'value' => '256',
                'type' => 'integer',
                'description' => 'Memory Limit (MB)'
            ],
            [
                'key' => 'max_execution_time',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Max Execution Time (วินาที)'
            ],
            [
                'key' => 'compression_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งาน Compression'
            ],
            [
                'key' => 'slow_query_log_enabled',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'เปิดใช้งาน Slow Query Log'
            ],
        ];

        // Insert settings into database
        foreach ($settings as $setting) {
            DB::table('laravel_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
