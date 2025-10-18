<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // ข้อมูลเว็บไซต์
            'site_name' => 'CMS Backend System',
            'site_description' => 'ระบบจัดการเนื้อหาแบบครบวงจร',
            'site_keywords' => 'CMS, Backend, Laravel, Management',
            'site_author' => 'Admin',
            'site_version' => '1.0.0',
            'site_language' => 'th',
            'site_timezone' => 'Asia/Bangkok',
            'site_currency' => 'THB',
            'site_logo' => '',
            'site_favicon' => '',
            
            // การตั้งค่าระบบ
            'maintenance_mode' => 'false',
            'maintenance_message' => 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ',
            'enable_registration' => 'true',
            'enable_comments' => 'true',
            
            // การตั้งค่าการแสดงผล
            'default_pagination' => '20',
            'max_upload_size' => '10',
            'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'category' => 'general',
                    'group_name' => 'site',
                    'description' => $this->getSettingDescription($key),
                    'is_active' => true,
                    'is_public' => $this->isPublicSetting($key),
                ]
            );
        }
    }

    private function getSettingType($key)
    {
        $types = [
            'site_name' => 'string',
            'site_description' => 'string',
            'site_keywords' => 'string',
            'site_author' => 'string',
            'site_version' => 'string',
            'site_language' => 'string',
            'site_timezone' => 'string',
            'site_currency' => 'string',
            'site_logo' => 'string',
            'site_favicon' => 'string',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'string',
            'enable_registration' => 'boolean',
            'enable_comments' => 'boolean',
            'default_pagination' => 'integer',
            'max_upload_size' => 'integer',
            'allowed_file_types' => 'string',
        ];

        return $types[$key] ?? 'string';
    }

    private function getSettingDescription($key)
    {
        $descriptions = [
            'site_name' => 'ชื่อเว็บไซต์',
            'site_description' => 'คำอธิบายเว็บไซต์',
            'site_keywords' => 'คำสำคัญของเว็บไซต์',
            'site_author' => 'ผู้เขียนเว็บไซต์',
            'site_version' => 'เวอร์ชันของเว็บไซต์',
            'site_language' => 'ภาษาหลักของเว็บไซต์',
            'site_timezone' => 'เขตเวลาของเว็บไซต์',
            'site_currency' => 'สกุลเงินหลัก',
            'site_logo' => 'โลโก้เว็บไซต์',
            'site_favicon' => 'ไอคอนเว็บไซต์',
            'maintenance_mode' => 'โหมดบำรุงรักษา',
            'maintenance_message' => 'ข้อความโหมดบำรุงรักษา',
            'enable_registration' => 'เปิดใช้งานการสมัครสมาชิก',
            'enable_comments' => 'เปิดใช้งานระบบความคิดเห็น',
            'default_pagination' => 'จำนวนรายการต่อหน้า (เริ่มต้น)',
            'max_upload_size' => 'ขนาดไฟล์สูงสุดที่อัพโหลดได้ (MB)',
            'allowed_file_types' => 'ประเภทไฟล์ที่อนุญาตให้อัพโหลด',
        ];

        return $descriptions[$key] ?? '';
    }

    private function isPublicSetting($key)
    {
        $publicSettings = [
            'site_name',
            'site_description',
            'site_keywords',
            'site_author',
            'site_version',
            'site_language',
            'site_timezone',
            'site_currency',
            'site_logo',
            'site_favicon',
        ];

        return in_array($key, $publicSettings);
    }
}