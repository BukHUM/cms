<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SecuritySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            'password_min_length' => '8',
            'password_require_uppercase' => 'true',
            'password_require_lowercase' => 'true',
            'password_require_numbers' => 'true',
            'password_require_special' => 'true',
            'password_expiry_days' => '90',
            'max_login_attempts' => '5',
            'lockout_duration' => '15',
            'session_timeout' => '120',
            'enable_2fa' => 'false',
            'enable_captcha' => 'false',
            'enable_ip_whitelist' => 'false',
            'ip_whitelist' => '',
            'enable_audit_log' => 'true',
            'enable_login_notifications' => 'true',
            'enable_failed_login_alerts' => 'true',
            'enable_password_history' => 'true',
            'password_history_count' => '5',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'category' => 'security',
                    'group_name' => 'default',
                    'description' => $this->getSettingDescription($key),
                    'is_active' => true,
                    'is_public' => false,
                ]
            );
        }
    }

    private function getSettingType($key)
    {
        $types = [
            'password_min_length' => 'integer',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_special' => 'boolean',
            'password_expiry_days' => 'integer',
            'max_login_attempts' => 'integer',
            'lockout_duration' => 'integer',
            'session_timeout' => 'integer',
            'enable_2fa' => 'boolean',
            'enable_captcha' => 'boolean',
            'enable_ip_whitelist' => 'boolean',
            'ip_whitelist' => 'string',
            'enable_audit_log' => 'boolean',
            'enable_login_notifications' => 'boolean',
            'enable_failed_login_alerts' => 'boolean',
            'enable_password_history' => 'boolean',
            'password_history_count' => 'integer',
        ];

        return $types[$key] ?? 'string';
    }

    private function getSettingDescription($key)
    {
        $descriptions = [
            'password_min_length' => 'ความยาวขั้นต่ำของรหัสผ่าน',
            'password_require_uppercase' => 'ต้องการตัวอักษรพิมพ์ใหญ่',
            'password_require_lowercase' => 'ต้องการตัวอักษรพิมพ์เล็ก',
            'password_require_numbers' => 'ต้องการตัวเลข',
            'password_require_special' => 'ต้องการอักขระพิเศษ',
            'password_expiry_days' => 'จำนวนวันหมดอายุของรหัสผ่าน',
            'max_login_attempts' => 'จำนวนครั้งสูงสุดในการล็อกอินผิด',
            'lockout_duration' => 'ระยะเวลาล็อกบัญชี (นาที)',
            'session_timeout' => 'ระยะเวลาสิ้นสุดของ session (นาที)',
            'enable_2fa' => 'เปิดใช้งานการยืนยันตัวตนสองขั้นตอน',
            'enable_captcha' => 'เปิดใช้งาน CAPTCHA',
            'enable_ip_whitelist' => 'เปิดใช้งาน IP Whitelist',
            'ip_whitelist' => 'รายการ IP ที่อนุญาต',
            'enable_audit_log' => 'เปิดใช้งาน Audit Log',
            'enable_login_notifications' => 'เปิดใช้งานการแจ้งเตือนการล็อกอิน',
            'enable_failed_login_alerts' => 'เปิดใช้งานการแจ้งเตือนการล็อกอินผิด',
            'enable_password_history' => 'เปิดใช้งานประวัติรหัสผ่าน',
            'password_history_count' => 'จำนวนรหัสผ่านเก่าที่เก็บไว้',
        ];

        return $descriptions[$key] ?? '';
    }
}