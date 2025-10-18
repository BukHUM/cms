<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Performance;
use App\Models\User;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user for created_by and updated_by
        $adminUser = User::where('email', 'admin@example.com')->first();
        $userId = $adminUser ? $adminUser->id : 1;

        $performanceSettings = [
            // Cache Settings
            [
                'name' => 'Cache Driver',
                'key' => 'performance.cache.driver',
                'value' => 'file',
                'type' => 'string',
                'description' => 'Cache driver ที่ใช้ในการเก็บข้อมูล cache',
                'is_active' => true,
                'category' => 'cache',
                'sort_order' => 1,
                'validation_rules' => [
                    'required' => true,
                    'options' => ['file', 'redis', 'memcached', 'database']
                ],
                'default_value' => 'file',
                'options' => [
                    'file' => 'File Cache',
                    'redis' => 'Redis Cache',
                    'memcached' => 'Memcached Cache',
                    'database' => 'Database Cache'
                ],
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Cache TTL',
                'key' => 'performance.cache.ttl',
                'value' => '3600',
                'type' => 'integer',
                'description' => 'เวลาหมดอายุของ cache (วินาที)',
                'is_active' => true,
                'category' => 'cache',
                'sort_order' => 2,
                'validation_rules' => [
                    'required' => true,
                    'min' => 60,
                    'max' => 86400
                ],
                'default_value' => '3600',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Enable Cache',
                'key' => 'performance.cache.enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานระบบ cache',
                'is_active' => true,
                'category' => 'cache',
                'sort_order' => 3,
                'validation_rules' => [],
                'default_value' => '1',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // Database Settings
            [
                'name' => 'Query Logging',
                'key' => 'performance.database.query_logging',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานการบันทึก query logs',
                'is_active' => true,
                'category' => 'database',
                'sort_order' => 1,
                'validation_rules' => [],
                'default_value' => '0',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Connection Pool Size',
                'key' => 'performance.database.pool_size',
                'value' => '10',
                'type' => 'integer',
                'description' => 'จำนวน connection pool สำหรับฐานข้อมูล',
                'is_active' => true,
                'category' => 'database',
                'sort_order' => 2,
                'validation_rules' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 100
                ],
                'default_value' => '10',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Slow Query Threshold',
                'key' => 'performance.database.slow_query_threshold',
                'value' => '2.0',
                'type' => 'float',
                'description' => 'เวลาขั้นต่ำที่ถือว่าเป็น slow query (วินาที)',
                'is_active' => true,
                'category' => 'database',
                'sort_order' => 3,
                'validation_rules' => [
                    'required' => true,
                    'min' => 0.1,
                    'max' => 10.0
                ],
                'default_value' => '2.0',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // Memory Settings
            [
                'name' => 'Memory Limit',
                'key' => 'performance.memory.limit',
                'value' => '256M',
                'type' => 'string',
                'description' => 'ขีดจำกัดหน่วยความจำสำหรับ PHP',
                'is_active' => true,
                'category' => 'memory',
                'sort_order' => 1,
                'validation_rules' => [
                    'required' => true,
                    'pattern' => '/^\d+[KMGT]?$/'
                ],
                'default_value' => '256M',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Enable Memory Monitoring',
                'key' => 'performance.memory.monitoring',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานการตรวจสอบการใช้หน่วยความจำ',
                'is_active' => true,
                'category' => 'memory',
                'sort_order' => 2,
                'validation_rules' => [],
                'default_value' => '1',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // Session Settings
            [
                'name' => 'Session Driver',
                'key' => 'performance.session.driver',
                'value' => 'file',
                'type' => 'string',
                'description' => 'Session driver ที่ใช้ในการเก็บข้อมูล session',
                'is_active' => true,
                'category' => 'session',
                'sort_order' => 1,
                'validation_rules' => [
                    'required' => true,
                    'options' => ['file', 'redis', 'database', 'memcached']
                ],
                'default_value' => 'file',
                'options' => [
                    'file' => 'File Session',
                    'redis' => 'Redis Session',
                    'database' => 'Database Session',
                    'memcached' => 'Memcached Session'
                ],
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Session Lifetime',
                'key' => 'performance.session.lifetime',
                'value' => '120',
                'type' => 'integer',
                'description' => 'เวลาหมดอายุของ session (นาที)',
                'is_active' => true,
                'category' => 'session',
                'sort_order' => 2,
                'validation_rules' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 1440
                ],
                'default_value' => '120',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // Queue Settings
            [
                'name' => 'Queue Driver',
                'key' => 'performance.queue.driver',
                'value' => 'sync',
                'type' => 'string',
                'description' => 'Queue driver ที่ใช้ในการประมวลผลงาน',
                'is_active' => true,
                'category' => 'queue',
                'sort_order' => 1,
                'validation_rules' => [
                    'required' => true,
                    'options' => ['sync', 'database', 'redis', 'sqs']
                ],
                'default_value' => 'sync',
                'options' => [
                    'sync' => 'Synchronous',
                    'database' => 'Database Queue',
                    'redis' => 'Redis Queue',
                    'sqs' => 'Amazon SQS'
                ],
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Queue Workers',
                'key' => 'performance.queue.workers',
                'value' => '4',
                'type' => 'integer',
                'description' => 'จำนวน worker processes สำหรับ queue',
                'is_active' => true,
                'category' => 'queue',
                'sort_order' => 2,
                'validation_rules' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 32
                ],
                'default_value' => '4',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // Logging Settings
            [
                'name' => 'Log Level',
                'key' => 'performance.logging.level',
                'value' => 'info',
                'type' => 'string',
                'description' => 'ระดับการบันทึก log',
                'is_active' => true,
                'category' => 'logging',
                'sort_order' => 1,
                'validation_rules' => [
                    'required' => true,
                    'options' => ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency']
                ],
                'default_value' => 'info',
                'options' => [
                    'debug' => 'Debug',
                    'info' => 'Info',
                    'notice' => 'Notice',
                    'warning' => 'Warning',
                    'error' => 'Error',
                    'critical' => 'Critical',
                    'alert' => 'Alert',
                    'emergency' => 'Emergency'
                ],
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Log Retention Days',
                'key' => 'performance.logging.retention_days',
                'value' => '30',
                'type' => 'integer',
                'description' => 'จำนวนวันที่เก็บ log files',
                'is_active' => true,
                'category' => 'logging',
                'sort_order' => 2,
                'validation_rules' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 365
                ],
                'default_value' => '30',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // Optimization Settings
            [
                'name' => 'Enable Gzip Compression',
                'key' => 'performance.optimization.gzip',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานการบีบอัด Gzip',
                'is_active' => true,
                'category' => 'optimization',
                'sort_order' => 1,
                'validation_rules' => [],
                'default_value' => '1',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Enable Minification',
                'key' => 'performance.optimization.minification',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'เปิดใช้งานการย่อขนาด CSS และ JS',
                'is_active' => true,
                'category' => 'optimization',
                'sort_order' => 2,
                'validation_rules' => [],
                'default_value' => '1',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'name' => 'Image Optimization Quality',
                'key' => 'performance.optimization.image_quality',
                'value' => '85',
                'type' => 'integer',
                'description' => 'คุณภาพการบีบอัดรูปภาพ (1-100)',
                'is_active' => true,
                'category' => 'optimization',
                'sort_order' => 3,
                'validation_rules' => [
                    'required' => true,
                    'min' => 1,
                    'max' => 100
                ],
                'default_value' => '85',
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
        ];

        foreach ($performanceSettings as $setting) {
            Performance::create($setting);
        }
    }
};