<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@example.com',
            'mail_from_name' => 'CMS Backend System',
            'mail_reply_to' => 'support@example.com',
            'mail_sendmail_path' => '/usr/sbin/sendmail -bs',
            'mail_markdown_theme' => 'default',
            'mail_markdown_paths' => 'resources/views/vendor/mail',
            'enable_mail_queue' => 'true',
            'mail_queue_connection' => 'database',
            'mail_queue_name' => 'default',
            'mail_queue_delay' => '0',
            'mail_queue_retry_after' => '90',
            'mail_queue_max_tries' => '3',
            'enable_mail_logging' => 'true',
            'mail_test_recipient' => 'test@example.com',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'category' => 'email',
                    'group_name' => 'smtp',
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
            'mail_driver' => 'string',
            'mail_host' => 'string',
            'mail_port' => 'integer',
            'mail_username' => 'string',
            'mail_password' => 'string',
            'mail_encryption' => 'string',
            'mail_from_address' => 'email',
            'mail_from_name' => 'string',
            'mail_reply_to' => 'email',
            'mail_sendmail_path' => 'string',
            'mail_markdown_theme' => 'string',
            'mail_markdown_paths' => 'string',
            'enable_mail_queue' => 'boolean',
            'mail_queue_connection' => 'string',
            'mail_queue_name' => 'string',
            'mail_queue_delay' => 'integer',
            'mail_queue_retry_after' => 'integer',
            'mail_queue_max_tries' => 'integer',
            'enable_mail_logging' => 'boolean',
            'mail_test_recipient' => 'email',
        ];

        return $types[$key] ?? 'string';
    }

    private function getSettingDescription($key)
    {
        $descriptions = [
            'mail_driver' => 'ประเภทการส่งอีเมล์ (smtp, sendmail, mailgun, ses)',
            'mail_host' => 'เซิร์ฟเวอร์ SMTP',
            'mail_port' => 'พอร์ต SMTP',
            'mail_username' => 'ชื่อผู้ใช้ SMTP',
            'mail_password' => 'รหัสผ่าน SMTP',
            'mail_encryption' => 'การเข้ารหัส (tls, ssl)',
            'mail_from_address' => 'อีเมล์ผู้ส่ง',
            'mail_from_name' => 'ชื่อผู้ส่ง',
            'mail_reply_to' => 'อีเมล์สำหรับตอบกลับ',
            'mail_sendmail_path' => 'เส้นทาง Sendmail',
            'mail_markdown_theme' => 'ธีม Markdown สำหรับอีเมล์',
            'mail_markdown_paths' => 'เส้นทางไฟล์ Markdown',
            'enable_mail_queue' => 'เปิดใช้งาน Mail Queue',
            'mail_queue_connection' => 'การเชื่อมต่อ Queue',
            'mail_queue_name' => 'ชื่อ Queue',
            'mail_queue_delay' => 'ความล่าช้า Queue (วินาที)',
            'mail_queue_retry_after' => 'เวลารอคอยก่อนลองใหม่ (วินาที)',
            'mail_queue_max_tries' => 'จำนวนครั้งสูงสุดที่ลองส่ง',
            'enable_mail_logging' => 'เปิดใช้งานการบันทึกอีเมล์',
            'mail_test_recipient' => 'อีเมล์สำหรับทดสอบการส่ง',
        ];

        return $descriptions[$key] ?? '';
    }
}