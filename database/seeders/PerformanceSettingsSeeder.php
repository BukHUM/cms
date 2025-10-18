<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class PerformanceSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            'enable_query_cache' => 'true',
            'query_cache_duration' => '3600',
            'enable_page_cache' => 'true',
            'page_cache_duration' => '1800',
            'enable_asset_cache' => 'true',
            'asset_cache_duration' => '86400',
            'enable_compression' => 'true',
            'compression_level' => '6',
            'enable_minification' => 'true',
            'minify_css' => 'true',
            'minify_js' => 'true',
            'minify_html' => 'true',
            'enable_cdn' => 'false',
            'cdn_url' => '',
            'enable_lazy_loading' => 'true',
            'lazy_loading_threshold' => '100',
            'enable_image_optimization' => 'true',
            'image_quality' => '85',
            'max_image_width' => '1920',
            'max_image_height' => '1080',
            'enable_database_optimization' => 'true',
            'database_cleanup_days' => '30',
            'enable_log_rotation' => 'true',
            'log_rotation_days' => '7',
            'max_log_size' => '10',
            'enable_monitoring' => 'true',
            'monitoring_interval' => '300',
            'enable_alerts' => 'true',
            'alert_threshold_cpu' => '80',
            'alert_threshold_memory' => '85',
            'alert_threshold_disk' => '90',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'category' => 'performance',
                    'group_name' => 'optimization',
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
            'enable_query_cache' => 'boolean',
            'query_cache_duration' => 'integer',
            'enable_page_cache' => 'boolean',
            'page_cache_duration' => 'integer',
            'enable_asset_cache' => 'boolean',
            'asset_cache_duration' => 'integer',
            'enable_compression' => 'boolean',
            'compression_level' => 'integer',
            'enable_minification' => 'boolean',
            'minify_css' => 'boolean',
            'minify_js' => 'boolean',
            'minify_html' => 'boolean',
            'enable_cdn' => 'boolean',
            'cdn_url' => 'url',
            'enable_lazy_loading' => 'boolean',
            'lazy_loading_threshold' => 'integer',
            'enable_image_optimization' => 'boolean',
            'image_quality' => 'integer',
            'max_image_width' => 'integer',
            'max_image_height' => 'integer',
            'enable_database_optimization' => 'boolean',
            'database_cleanup_days' => 'integer',
            'enable_log_rotation' => 'boolean',
            'log_rotation_days' => 'integer',
            'max_log_size' => 'integer',
            'enable_monitoring' => 'boolean',
            'monitoring_interval' => 'integer',
            'enable_alerts' => 'boolean',
            'alert_threshold_cpu' => 'integer',
            'alert_threshold_memory' => 'integer',
            'alert_threshold_disk' => 'integer',
        ];

        return $types[$key] ?? 'string';
    }

    private function getSettingDescription($key)
    {
        $descriptions = [
            'enable_query_cache' => 'เปิดใช้งาน Query Cache',
            'query_cache_duration' => 'ระยะเวลา Query Cache (วินาที)',
            'enable_page_cache' => 'เปิดใช้งาน Page Cache',
            'page_cache_duration' => 'ระยะเวลา Page Cache (วินาที)',
            'enable_asset_cache' => 'เปิดใช้งาน Asset Cache',
            'asset_cache_duration' => 'ระยะเวลา Asset Cache (วินาที)',
            'enable_compression' => 'เปิดใช้งานการบีบอัด',
            'compression_level' => 'ระดับการบีบอัด (1-9)',
            'enable_minification' => 'เปิดใช้งานการย่อไฟล์',
            'minify_css' => 'ย่อไฟล์ CSS',
            'minify_js' => 'ย่อไฟล์ JavaScript',
            'minify_html' => 'ย่อไฟล์ HTML',
            'enable_cdn' => 'เปิดใช้งาน CDN',
            'cdn_url' => 'URL ของ CDN',
            'enable_lazy_loading' => 'เปิดใช้งาน Lazy Loading',
            'lazy_loading_threshold' => 'ระยะห่างสำหรับ Lazy Loading (px)',
            'enable_image_optimization' => 'เปิดใช้งานการปรับปรุงรูปภาพ',
            'image_quality' => 'คุณภาพรูปภาพ (1-100)',
            'max_image_width' => 'ความกว้างสูงสุดของรูปภาพ (px)',
            'max_image_height' => 'ความสูงสูงสุดของรูปภาพ (px)',
            'enable_database_optimization' => 'เปิดใช้งานการปรับปรุงฐานข้อมูล',
            'database_cleanup_days' => 'จำนวนวันในการทำความสะอาดฐานข้อมูล',
            'enable_log_rotation' => 'เปิดใช้งานการหมุนเวียน Log',
            'log_rotation_days' => 'จำนวนวันในการเก็บ Log',
            'max_log_size' => 'ขนาด Log สูงสุด (MB)',
            'enable_monitoring' => 'เปิดใช้งานการตรวจสอบระบบ',
            'monitoring_interval' => 'ช่วงเวลาการตรวจสอบ (วินาที)',
            'enable_alerts' => 'เปิดใช้งานการแจ้งเตือน',
            'alert_threshold_cpu' => 'เกณฑ์การแจ้งเตือน CPU (%)',
            'alert_threshold_memory' => 'เกณฑ์การแจ้งเตือน Memory (%)',
            'alert_threshold_disk' => 'เกณฑ์การแจ้งเตือน Disk (%)',
        ];

        return $descriptions[$key] ?? '';
    }
}