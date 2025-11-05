<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AllSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting to seed all settings...');
        
        // ========================================
        // GENERAL SETTINGS
        // ========================================
        $this->command->info('Seeding General Settings...');
        $this->seedGeneralSettings();
        
        // ========================================
        // PERFORMANCE SETTINGS
        // ========================================
        $this->command->info('Seeding Performance Settings...');
        $this->seedPerformanceSettings();
        
        // ========================================
        // BACKUP SETTINGS
        // ========================================
        $this->command->info('Seeding Backup Settings...');
        $this->seedBackupSettings();
        
        $this->command->info('All settings seeded successfully!');
        $this->command->info('Total settings: ' . Setting::count());
    }
    
    private function seedGeneralSettings()
    {
        $generalSettings = [
            // ข้อมูลเว็บไซต์
            [
                'key' => 'site_name',
                'value' => 'CMS Backend System',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'ชื่อเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 1,
                'default_value' => 'CMS Backend System',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'site_description',
                'value' => 'ระบบจัดการเนื้อหาแบบครบวงจร',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'คำอธิบายเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 2,
                'default_value' => 'ระบบจัดการเนื้อหาแบบครบวงจร',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'site_keywords',
                'value' => 'CMS, Backend, Laravel, Management',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'คำสำคัญของเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 3,
                'default_value' => 'CMS, Backend, Laravel, Management',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'site_version',
                'value' => '1.0.0',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'เวอร์ชันของเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 4,
                'default_value' => '1.0.0',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'site_language',
                'value' => 'th',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'ภาษาหลักของเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 5,
                'default_value' => 'th',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'site_timezone',
                'value' => 'Asia/Bangkok',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'เขตเวลาของเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 6,
                'default_value' => 'Asia/Bangkok',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'โลโก้เว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 7,
                'default_value' => '',
                'validation_rules' => json_encode([]),
                'options' => null,
            ],
            [
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'ไอคอนเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 8,
                'default_value' => '',
                'validation_rules' => json_encode([]),
                'options' => null,
            ],
            
            // การตั้งค่าระบบ
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'โหมดบำรุงรักษา',
                'is_active' => false,
                'is_public' => false,
                'sort_order' => 10,
                'default_value' => 'false',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'ข้อความโหมดบำรุงรักษา',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 11,
                'default_value' => 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'debug_mode',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'เปิดใช้งาน debug mode',
                'is_active' => false,
                'is_public' => false,
                'sort_order' => 12,
                'default_value' => 'false',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'debug_bar',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'เปิดใช้งาน debug bar',
                'is_active' => false,
                'is_public' => false,
                'sort_order' => 13,
                'default_value' => 'false',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            
            // การตั้งค่าการแสดงผล
            [
                'key' => 'default_pagination',
                'value' => '20',
                'type' => 'integer',
                'category' => 'general',
                'group_name' => 'display',
                'description' => 'จำนวนรายการต่อหน้า (เริ่มต้น)',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 20,
                'default_value' => '20',
                'validation_rules' => json_encode(['required' => true, 'min' => 5, 'max' => 100]),
                'options' => null,
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10',
                'type' => 'integer',
                'category' => 'general',
                'group_name' => 'display',
                'description' => 'ขนาดไฟล์สูงสุดที่อัพโหลดได้ (MB)',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 21,
                'default_value' => '10',
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 100]),
                'options' => null,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'jpg,jpeg,png,gif,pdf,doc,docx',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'display',
                'description' => 'ประเภทไฟล์ที่อนุญาตให้อัพโหลด',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 22,
                'default_value' => 'jpg,jpeg,png,gif,pdf,doc,docx',
                'validation_rules' => json_encode(['required' => true]),
                'options' => null,
            ],
            [
                'key' => 'cache_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'เปิดหรือปิดการใช้งาน cache ทั้งระบบ',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 23,
                'default_value' => 'true',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
        ];
        
        foreach ($generalSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key'], 'category' => $setting['category']],
                $setting
            );
        }
        
        $this->command->info('General settings: ' . count($generalSettings) . ' items');
    }
    
    private function seedPerformanceSettings()
    {
        $performanceSettings = [
            // ========================================
            // CACHE SETTINGS (3 รายการ)
            // ========================================
            [
                'key' => 'cache_driver',
                'description' => 'เลือก driver สำหรับ cache (file, redis, memcached)',
                'value' => 'file',
                'type' => 'string',
                'category' => 'performance',
                'group_name' => 'cache',
                'default_value' => 'file',
                'is_active' => true,
                'sort_order' => 1,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['file' => 'File', 'redis' => 'Redis', 'memcached' => 'Memcached']),
            ],
            [
                'key' => 'cache_ttl',
                'description' => 'เวลาหมดอายุของ cache เป็นวินาที',
                'value' => '3600',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'cache',
                'default_value' => '3600',
                'is_active' => true,
                'sort_order' => 2,
                'validation_rules' => json_encode(['required' => true, 'min' => 60, 'max' => 86400]),
                'options' => null,
            ],
            [
                'key' => 'cache_prefix',
                'description' => 'คำนำหน้าสำหรับ cache key',
                'value' => 'core_cache',
                'type' => 'string',
                'category' => 'performance',
                'group_name' => 'cache',
                'default_value' => 'core_cache',
                'is_active' => true,
                'sort_order' => 3,
                'validation_rules' => json_encode(['required' => true, 'max_length' => 50]),
                'options' => null,
            ],

            // ========================================
            // DATABASE SETTINGS (3 รายการ)
            // ========================================
            [
                'key' => 'db_query_log',
                'description' => 'บันทึก query ทั้งหมดที่รันในฐานข้อมูล',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'database',
                'default_value' => 'false',
                'is_active' => true,
                'sort_order' => 10,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'db_connection_pool',
                'description' => 'จำนวน connection pool สำหรับฐานข้อมูล',
                'value' => '10',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'database',
                'default_value' => '10',
                'is_active' => true,
                'sort_order' => 11,
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 100]),
                'options' => null,
            ],
            [
                'key' => 'db_timeout',
                'description' => 'เวลารอการเชื่อมต่อฐานข้อมูล (วินาที)',
                'value' => '30',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'database',
                'default_value' => '30',
                'is_active' => true,
                'sort_order' => 12,
                'validation_rules' => json_encode(['required' => true, 'min' => 5, 'max' => 300]),
                'options' => null,
            ],

            // ========================================
            // MEMORY SETTINGS (3 รายการ)
            // ========================================
            [
                'key' => 'memory_limit',
                'description' => 'ขีดจำกัดหน่วยความจำสำหรับ PHP (MB)',
                'value' => '256',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'memory',
                'default_value' => '256',
                'is_active' => true,
                'sort_order' => 20,
                'validation_rules' => json_encode(['required' => true, 'min' => 64, 'max' => 2048]),
                'options' => null,
            ],
            [
                'key' => 'max_execution_time',
                'description' => 'เวลาสูงสุดในการประมวลผลสคริปต์ (วินาที)',
                'value' => '300',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'memory',
                'default_value' => '300',
                'is_active' => true,
                'sort_order' => 21,
                'validation_rules' => json_encode(['required' => true, 'min' => 30, 'max' => 1800]),
                'options' => null,
            ],
            [
                'key' => 'upload_max_filesize',
                'description' => 'ขนาดไฟล์สูงสุดที่สามารถอัปโหลดได้ (MB)',
                'value' => '10',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'memory',
                'default_value' => '10',
                'is_active' => true,
                'sort_order' => 22,
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 100]),
                'options' => null,
            ],

            // ========================================
            // SESSION SETTINGS (5 รายการ)
            // ========================================
            [
                'key' => 'session_driver',
                'description' => 'เลือก driver สำหรับ session',
                'value' => 'file',
                'type' => 'string',
                'category' => 'performance',
                'group_name' => 'session',
                'default_value' => 'file',
                'is_active' => true,
                'sort_order' => 30,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['file' => 'File', 'database' => 'Database', 'redis' => 'Redis']),
            ],
            [
                'key' => 'session_lifetime',
                'description' => 'เวลาหมดอายุของ session (นาที)',
                'value' => '120',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'session',
                'default_value' => '120',
                'is_active' => true,
                'sort_order' => 31,
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 1440]),
                'options' => null,
            ],
            [
                'key' => 'session_secure',
                'description' => 'ใช้ HTTPS สำหรับ session cookie',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'session',
                'default_value' => 'false',
                'is_active' => true,
                'sort_order' => 32,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'session_encrypt',
                'description' => 'เข้ารหัส session data',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'session',
                'default_value' => 'false',
                'is_active' => true,
                'sort_order' => 33,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'session_http_only',
                'description' => 'ป้องกัน JavaScript access',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'session',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 34,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],

            // ========================================
            // QUEUE SETTINGS (5 รายการ)
            // ========================================
            [
                'key' => 'queue_driver',
                'description' => 'เลือก driver สำหรับ queue',
                'value' => 'sync',
                'type' => 'string',
                'category' => 'performance',
                'group_name' => 'queue',
                'default_value' => 'sync',
                'is_active' => true,
                'sort_order' => 40,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['sync' => 'Sync', 'database' => 'Database', 'redis' => 'Redis']),
            ],
            [
                'key' => 'queue_retry_after',
                'description' => 'เวลารอคอยก่อน retry job ล้มเหลว (วินาที)',
                'value' => '90',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'queue',
                'default_value' => '90',
                'is_active' => true,
                'sort_order' => 41,
                'validation_rules' => json_encode(['required' => true, 'min' => 30, 'max' => 3600]),
                'options' => null,
            ],
            [
                'key' => 'queue_max_tries',
                'description' => 'จำนวนครั้งสูงสุดในการลองใหม่',
                'value' => '3',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'queue',
                'default_value' => '3',
                'is_active' => true,
                'sort_order' => 42,
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 10]),
                'options' => null,
            ],
            [
                'key' => 'queue_timeout',
                'description' => 'เวลา timeout สำหรับ queue jobs (วินาที)',
                'value' => '60',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'queue',
                'default_value' => '60',
                'is_active' => true,
                'sort_order' => 43,
                'validation_rules' => json_encode(['required' => true, 'min' => 30, 'max' => 3600]),
                'options' => null,
            ],
            [
                'key' => 'queue_memory',
                'description' => 'จำกัดหน่วยความจำสำหรับ queue (MB)',
                'value' => '128',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'queue',
                'default_value' => '128',
                'is_active' => true,
                'sort_order' => 44,
                'validation_rules' => json_encode(['required' => true, 'min' => 64, 'max' => 1024]),
                'options' => null,
            ],

            // ========================================
            // LOGGING SETTINGS (3 รายการ)
            // ========================================
            [
                'key' => 'log_level',
                'description' => 'ระดับการบันทึก log',
                'value' => 'debug',
                'type' => 'string',
                'category' => 'performance',
                'group_name' => 'logging',
                'default_value' => 'debug',
                'is_active' => true,
                'sort_order' => 50,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['debug' => 'Debug', 'info' => 'Info', 'warning' => 'Warning', 'error' => 'Error']),
            ],
            [
                'key' => 'log_max_files',
                'description' => 'จำนวนไฟล์ log สูงสุด',
                'value' => '5',
                'type' => 'integer',
                'category' => 'performance',
                'group_name' => 'logging',
                'default_value' => '5',
                'is_active' => true,
                'sort_order' => 51,
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 30]),
                'options' => null,
            ],
            [
                'key' => 'log_daily',
                'description' => 'สร้างไฟล์ log แยกตามวัน',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'logging',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 52,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],

            // ========================================
            // OPTIMIZATION SETTINGS (7 รายการ)
            // ========================================
            [
                'key' => 'opcache_enabled',
                'description' => 'เปิดใช้งาน OPcache สำหรับ PHP',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 60,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'gzip_compression',
                'description' => 'บีบอัดข้อมูลส่งให้ผู้ใช้',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 61,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'lazy_loading',
                'description' => 'โหลดข้อมูลแบบ lazy loading',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 62,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'minify_css',
                'description' => 'บีบอัดไฟล์ CSS',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 63,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'minify_js',
                'description' => 'บีบอัดไฟล์ JavaScript',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'true',
                'is_active' => true,
                'sort_order' => 64,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'image_optimization',
                'description' => 'ปรับปรุงรูปภาพอัตโนมัติ',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'false',
                'is_active' => true,
                'sort_order' => 65,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
            [
                'key' => 'cdn_enabled',
                'description' => 'ใช้ CDN สำหรับ static files',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'performance',
                'group_name' => 'optimization',
                'default_value' => 'false',
                'is_active' => true,
                'sort_order' => 66,
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['true' => 'เปิด', 'false' => 'ปิด']),
            ],
        ];
        
        foreach ($performanceSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key'], 'category' => $setting['category']],
                $setting
            );
        }
        
        $this->command->info('Performance settings: ' . count($performanceSettings) . ' items');
    }
    
    private function seedBackupSettings()
    {
        $backupSettings = [
            // Database Settings
            [
                'key' => 'backup_database_enabled',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'backup',
                'group_name' => 'database',
                'description' => 'เปิดใช้งานการสำรองฐานข้อมูล',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 1,
                'default_value' => '1',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['label' => 'เปิดใช้งานการสำรองฐานข้อมูล']),
            ],

            // Schedule Settings
            [
                'key' => 'backup_auto_enabled',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'backup',
                'group_name' => 'schedule',
                'description' => 'เปิดใช้งานการสำรองอัตโนมัติ',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 3,
                'default_value' => '0',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['label' => 'เปิดใช้งานการสำรองอัตโนมัติ']),
            ],
            [
                'key' => 'backup_schedule_frequency',
                'value' => 'disabled',
                'type' => 'string',
                'category' => 'backup',
                'group_name' => 'schedule',
                'description' => 'ช่วงเวลาสำรองอัตโนมัติ',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 4,
                'default_value' => 'disabled',
                'validation_rules' => json_encode(['required' => true, 'in' => ['daily', 'weekly', 'monthly', 'disabled']]),
                'options' => json_encode([
                    'label' => 'ช่วงเวลาสำรองอัตโนมัติ',
                    'choices' => [
                        'daily' => 'ทุกวัน เวลา 02:00',
                        'weekly' => 'ทุกสัปดาห์ วันอาทิตย์',
                        'monthly' => 'ทุกเดือน วันที่ 1',
                        'disabled' => 'ปิดใช้งาน'
                    ]
                ]),
            ],

            // Storage Settings
            [
                'key' => 'backup_max_files',
                'value' => '10',
                'type' => 'integer',
                'category' => 'backup',
                'group_name' => 'storage',
                'description' => 'จำนวนไฟล์สำรองสูงสุด',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 5,
                'default_value' => '10',
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 100]),
                'options' => json_encode(['label' => 'จำนวนไฟล์สำรองสูงสุด', 'unit' => 'ไฟล์']),
            ],
            [
                'key' => 'backup_max_size_mb',
                'value' => '100',
                'type' => 'integer',
                'category' => 'backup',
                'group_name' => 'storage',
                'description' => 'ขนาดไฟล์สำรองสูงสุด (MB)',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 6,
                'default_value' => '100',
                'validation_rules' => json_encode(['required' => true, 'min' => 1, 'max' => 10000]),
                'options' => json_encode(['label' => 'ขนาดไฟล์สำรองสูงสุด', 'unit' => 'MB']),
            ],

            // File Settings
            [
                'key' => 'backup_include_files',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'backup',
                'group_name' => 'files',
                'description' => 'รวมไฟล์ระบบในการสำรองข้อมูล',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 8,
                'default_value' => '0',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['label' => 'รวมไฟล์ระบบ']),
            ],
            [
                'key' => 'backup_include_storage',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'backup',
                'group_name' => 'files',
                'description' => 'รวมโฟลเดอร์ storage',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 9,
                'default_value' => '1',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['label' => 'รวมโฟลเดอร์ storage']),
            ],
            [
                'key' => 'backup_include_config',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'backup',
                'group_name' => 'files',
                'description' => 'รวมไฟล์ config',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 10,
                'default_value' => '1',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['label' => 'รวมไฟล์ config']),
            ],

            // Notification Settings
            [
                'key' => 'backup_notification_enabled',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'backup',
                'group_name' => 'notification',
                'description' => 'เปิดใช้งานการแจ้งเตือน',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 11,
                'default_value' => '1',
                'validation_rules' => json_encode(['required' => true]),
                'options' => json_encode(['label' => 'เปิดใช้งานการแจ้งเตือน']),
            ],
            [
                'key' => 'backup_notification_email',
                'value' => '',
                'type' => 'email',
                'category' => 'backup',
                'group_name' => 'notification',
                'description' => 'อีเมลสำหรับรับการแจ้งเตือน',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 12,
                'default_value' => '',
                'validation_rules' => json_encode(['email' => true]),
                'options' => json_encode(['label' => 'อีเมลแจ้งเตือน']),
            ],
        ];
        
        foreach ($backupSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key'], 'category' => $setting['category']],
                $setting
            );
        }
        
        $this->command->info('Backup settings: ' . count($backupSettings) . ' items');
    }
}
