<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
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
                'value' => 'CMS Backend',
                'type' => 'string',
                'group' => 'general',
                'description' => 'ชื่อเว็บไซต์ที่แสดงในระบบ',
                'is_public' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'ระบบจัดการเนื้อหาและผู้ใช้งาน',
                'type' => 'string',
                'group' => 'general',
                'description' => 'คำอธิบายเว็บไซต์',
                'is_public' => true,
            ],
            [
                'key' => 'site_url',
                'value' => 'http://localhost:8000',
                'type' => 'url',
                'group' => 'general',
                'description' => 'URL หลักของเว็บไซต์',
                'is_public' => true,
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Bangkok',
                'type' => 'string',
                'group' => 'general',
                'description' => 'เขตเวลาของระบบ',
                'is_public' => true,
            ],
            [
                'key' => 'language',
                'value' => 'th',
                'type' => 'string',
                'group' => 'general',
                'description' => 'ภาษาหลักของระบบ',
                'is_public' => true,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'โหมดบำรุงรักษาระบบ',
                'is_public' => false,
            ],

            // System Settings
            [
                'key' => 'app_version',
                'value' => '1.0.0',
                'type' => 'string',
                'group' => 'system',
                'description' => 'เวอร์ชันของแอปพลิเคชัน',
                'is_public' => true,
            ],
            [
                'key' => 'max_file_size',
                'value' => '10485760',
                'type' => 'integer',
                'group' => 'system',
                'description' => 'ขนาดไฟล์สูงสุดที่อัปโหลดได้ (bytes)',
                'is_public' => true,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => '["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "xls", "xlsx"]',
                'type' => 'json',
                'group' => 'system',
                'description' => 'ประเภทไฟล์ที่อนุญาตให้อัปโหลด',
                'is_public' => true,
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'integer',
                'group' => 'system',
                'description' => 'ระยะเวลาสิ้นสุดของ session (นาที)',
                'is_public' => false,
            ],
            [
                'key' => 'enable_registration',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'เปิดใช้งานการสมัครสมาชิกใหม่',
                'is_public' => true,
            ],

            // Email Settings
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@example.com',
                'type' => 'email',
                'group' => 'email',
                'description' => 'อีเมลผู้ส่ง',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'CMS Backend',
                'type' => 'string',
                'group' => 'email',
                'description' => 'ชื่อผู้ส่งอีเมล',
                'is_public' => false,
            ],
            [
                'key' => 'mail_smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP Host',
                'is_public' => false,
            ],
            [
                'key' => 'mail_smtp_port',
                'value' => '587',
                'type' => 'integer',
                'group' => 'email',
                'description' => 'SMTP Port',
                'is_public' => false,
            ],
            [
                'key' => 'mail_smtp_encryption',
                'value' => 'tls',
                'type' => 'string',
                'group' => 'email',
                'description' => 'การเข้ารหัส SMTP',
                'is_public' => false,
            ],
            [
                'key' => 'enable_email_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'เปิดใช้งานการแจ้งเตือนทางอีเมล',
                'is_public' => true,
            ],

            // Security Settings
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'ความยาวขั้นต่ำของรหัสผ่าน',
                'is_public' => true,
            ],
            [
                'key' => 'password_require_special',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'ต้องการอักขระพิเศษในรหัสผ่าน',
                'is_public' => true,
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'จำนวนครั้งสูงสุดในการล็อกอินผิด',
                'is_public' => false,
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'ระยะเวลาล็อกบัญชี (นาที)',
                'is_public' => false,
            ],
            [
                'key' => 'enable_2fa',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'เปิดใช้งานการยืนยันตัวตนสองขั้นตอน',
                'is_public' => true,
            ],
            [
                'key' => 'session_regenerate',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'สร้าง session ID ใหม่ทุกครั้งที่ล็อกอิน',
                'is_public' => false,
            ],

            // Appearance Settings
            [
                'key' => 'theme_color',
                'value' => '#3B82F6',
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'สีธีมหลักของระบบ',
                'is_public' => true,
            ],
            [
                'key' => 'sidebar_collapsed',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'appearance',
                'description' => 'ซ่อน sidebar เริ่มต้น',
                'is_public' => true,
            ],
            [
                'key' => 'items_per_page',
                'value' => '15',
                'type' => 'integer',
                'group' => 'appearance',
                'description' => 'จำนวนรายการต่อหน้า',
                'is_public' => true,
            ],
            [
                'key' => 'show_avatars',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'appearance',
                'description' => 'แสดงรูปโปรไฟล์ผู้ใช้งาน',
                'is_public' => true,
            ],
            [
                'key' => 'enable_animations',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'appearance',
                'description' => 'เปิดใช้งานแอนิเมชัน',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
