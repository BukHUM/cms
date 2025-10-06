<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // เพิ่มคอมเม้นท์ให้กับตารางและคอลัมน์ในฐานข้อมูล
        
        // ตาราง laravel_users
        DB::statement("ALTER TABLE `laravel_users` COMMENT = 'ตารางผู้ใช้ - เก็บข้อมูลผู้ใช้งานระบบ'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'ชื่อ-นามสกุล'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `email` varchar(255) NOT NULL COMMENT 'อีเมล (ไม่ซ้ำกัน)'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาที่ยืนยันอีเมล'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `password` varchar(255) NOT NULL COMMENT 'รหัสผ่าน (เข้ารหัสแล้ว)'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `role` varchar(255) NOT NULL DEFAULT 'user' COMMENT 'บทบาท (user, admin, etc.)'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `avatar` varchar(255) DEFAULT NULL COMMENT 'รูปโปรไฟล์'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `last_login_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาเข้าสู่ระบบครั้งล่าสุด'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `remember_token` varchar(100) DEFAULT NULL COMMENT 'Token สำหรับจำการล็อกอิน'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาอัปเดต'");
        DB::statement("ALTER TABLE `laravel_users` MODIFY COLUMN `status` enum('active','inactive','pending','suspended') NOT NULL DEFAULT 'active' COMMENT 'สถานะผู้ใช้ (ใช้งาน, ไม่ใช้งาน, รอการยืนยัน, ระงับการใช้งาน)'");

        // ตาราง laravel_password_reset_tokens
        DB::statement("ALTER TABLE `laravel_password_reset_tokens` COMMENT = 'ตารางรีเซ็ตรหัสผ่าน - เก็บ token สำหรับรีเซ็ตรหัสผ่าน'");
        DB::statement("ALTER TABLE `laravel_password_reset_tokens` MODIFY COLUMN `email` varchar(255) NOT NULL COMMENT 'อีเมลของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_password_reset_tokens` MODIFY COLUMN `token` varchar(255) NOT NULL COMMENT 'Token สำหรับรีเซ็ต'");
        DB::statement("ALTER TABLE `laravel_password_reset_tokens` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง token'");

        // ตาราง laravel_sessions
        DB::statement("ALTER TABLE `laravel_sessions` COMMENT = 'ตารางเซสชัน - เก็บข้อมูลเซสชันของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_sessions` MODIFY COLUMN `id` varchar(255) NOT NULL COMMENT 'ID ของเซสชัน'");
        DB::statement("ALTER TABLE `laravel_sessions` MODIFY COLUMN `user_id` bigint(20) unsigned DEFAULT NULL COMMENT 'ID ผู้ใช้ (ถ้าล็อกอินแล้ว)'");
        DB::statement("ALTER TABLE `laravel_sessions` MODIFY COLUMN `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP Address'");
        DB::statement("ALTER TABLE `laravel_sessions` MODIFY COLUMN `user_agent` text DEFAULT NULL COMMENT 'User Agent ของเบราว์เซอร์'");
        DB::statement("ALTER TABLE `laravel_sessions` MODIFY COLUMN `payload` longtext NOT NULL COMMENT 'ข้อมูลเซสชัน'");
        DB::statement("ALTER TABLE `laravel_sessions` MODIFY COLUMN `last_activity` int(11) NOT NULL COMMENT 'เวลากิจกรรมล่าสุด'");

        // ตาราง laravel_cache
        DB::statement("ALTER TABLE `laravel_cache` COMMENT = 'ตารางแคชหลัก - เก็บข้อมูลแคชของระบบ'");
        DB::statement("ALTER TABLE `laravel_cache` MODIFY COLUMN `key` varchar(255) NOT NULL COMMENT 'คีย์ของข้อมูลแคช'");
        DB::statement("ALTER TABLE `laravel_cache` MODIFY COLUMN `value` mediumtext NOT NULL COMMENT 'ค่าของข้อมูลแคช'");
        DB::statement("ALTER TABLE `laravel_cache` MODIFY COLUMN `expiration` int(11) NOT NULL COMMENT 'เวลาหมดอายุ (timestamp)'");

        // ตาราง laravel_cache_locks
        DB::statement("ALTER TABLE `laravel_cache_locks` COMMENT = 'ตารางแคชล็อค - เก็บข้อมูลล็อคสำหรับป้องกันการทำงานซ้ำ'");
        DB::statement("ALTER TABLE `laravel_cache_locks` MODIFY COLUMN `key` varchar(255) NOT NULL COMMENT 'คีย์ของล็อค'");
        DB::statement("ALTER TABLE `laravel_cache_locks` MODIFY COLUMN `owner` varchar(255) NOT NULL COMMENT 'เจ้าของล็อค'");
        DB::statement("ALTER TABLE `laravel_cache_locks` MODIFY COLUMN `expiration` int(11) NOT NULL COMMENT 'เวลาหมดอายุของล็อค'");

        // ตาราง laravel_jobs
        DB::statement("ALTER TABLE `laravel_jobs` COMMENT = 'ตารางงาน - เก็บงานที่ต้องประมวลผลในพื้นหลัง'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของงาน'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `queue` varchar(255) NOT NULL COMMENT 'ชื่อคิวที่งานอยู่ใน'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `payload` longtext NOT NULL COMMENT 'ข้อมูลงาน (JSON)'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `attempts` tinyint(3) unsigned NOT NULL COMMENT 'จำนวนครั้งที่พยายามประมวลผล'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `reserved_at` int(10) unsigned DEFAULT NULL COMMENT 'เวลาที่จองงานไว้'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `available_at` int(10) unsigned NOT NULL COMMENT 'เวลาที่พร้อมประมวลผล'");
        DB::statement("ALTER TABLE `laravel_jobs` MODIFY COLUMN `created_at` int(10) unsigned NOT NULL COMMENT 'เวลาสร้างงาน'");

        // ตาราง laravel_job_batches
        DB::statement("ALTER TABLE `laravel_job_batches` COMMENT = 'ตารางกลุ่มงาน - เก็บข้อมูลกลุ่มงานที่ประมวลผลพร้อมกัน'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `id` varchar(255) NOT NULL COMMENT 'ID ของกลุ่มงาน'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'ชื่อกลุ่มงาน'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `total_jobs` int(11) NOT NULL COMMENT 'จำนวนงานทั้งหมดในกลุ่ม'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `pending_jobs` int(11) NOT NULL COMMENT 'จำนวนงานที่รอประมวลผล'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `failed_jobs` int(11) NOT NULL COMMENT 'จำนวนงานที่ล้มเหลว'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `failed_job_ids` longtext NOT NULL COMMENT 'ID ของงานที่ล้มเหลว'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `options` mediumtext DEFAULT NULL COMMENT 'ตัวเลือกเพิ่มเติม'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `cancelled_at` int(11) DEFAULT NULL COMMENT 'เวลาที่ยกเลิกกลุ่มงาน'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `created_at` int(11) NOT NULL COMMENT 'เวลาสร้างกลุ่มงาน'");
        DB::statement("ALTER TABLE `laravel_job_batches` MODIFY COLUMN `finished_at` int(11) DEFAULT NULL COMMENT 'เวลาที่เสร็จสิ้นกลุ่มงาน'");

        // ตาราง laravel_failed_jobs
        DB::statement("ALTER TABLE `laravel_failed_jobs` COMMENT = 'ตารางงานที่ล้มเหลว - เก็บงานที่ประมวลผลไม่สำเร็จ'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของงานที่ล้มเหลว'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `uuid` varchar(255) NOT NULL COMMENT 'UUID ของงาน'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `connection` text NOT NULL COMMENT 'การเชื่อมต่อฐานข้อมูลที่ใช้'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `queue` text NOT NULL COMMENT 'ชื่อคิวที่งานอยู่ใน'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `payload` longtext NOT NULL COMMENT 'ข้อมูลงาน (JSON)'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `exception` longtext NOT NULL COMMENT 'ข้อผิดพลาดที่เกิดขึ้น'");
        DB::statement("ALTER TABLE `laravel_failed_jobs` MODIFY COLUMN `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'เวลาที่ล้มเหลว'");

        // ตาราง laravel_settings
        DB::statement("ALTER TABLE `laravel_settings` COMMENT = 'ตารางการตั้งค่าระบบ - เก็บการตั้งค่าต่าง ๆ ของระบบ'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของการตั้งค่า'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `key` varchar(255) NOT NULL COMMENT 'คีย์ของการตั้งค่า (ไม่ซ้ำกัน)'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `value` text DEFAULT NULL COMMENT 'ค่าของการตั้งค่า'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `type` varchar(255) NOT NULL DEFAULT 'string' COMMENT 'ประเภทข้อมูล (string, boolean, integer, json)'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `description` text DEFAULT NULL COMMENT 'คำอธิบายการตั้งค่า'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง'");
        DB::statement("ALTER TABLE `laravel_settings` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาอัปเดต'");

        // ตาราง laravel_audit_logs
        DB::statement("ALTER TABLE `laravel_audit_logs` COMMENT = 'ตาราง Audit Log - บันทึกการใช้งานระบบเพื่อตรวจสอบและติดตาม'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของ log'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `user_id` varchar(255) DEFAULT NULL COMMENT 'ID ของผู้ใช้ (อาจเป็น email หรือ ID)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `user_email` varchar(255) DEFAULT NULL COMMENT 'อีเมลของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `action` varchar(255) NOT NULL COMMENT 'การกระทำที่ทำ (login, logout, create, update, delete, view)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `resource_type` varchar(255) DEFAULT NULL COMMENT 'ประเภทของข้อมูลที่เกี่ยวข้อง (user, settings, etc.)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `resource_id` varchar(255) DEFAULT NULL COMMENT 'ID ของข้อมูลที่เกี่ยวข้อง'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `description` text DEFAULT NULL COMMENT 'คำอธิบายการกระทำ'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `ip_address` varchar(255) DEFAULT NULL COMMENT 'IP Address ของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `user_agent` varchar(255) DEFAULT NULL COMMENT 'User Agent ของเบราว์เซอร์'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `old_values` json DEFAULT NULL COMMENT 'ค่าเดิม (สำหรับการแก้ไข)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `new_values` json DEFAULT NULL COMMENT 'ค่าใหม่ (สำหรับการแก้ไข)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `status` varchar(255) NOT NULL DEFAULT 'success' COMMENT 'สถานะ (success, failed, error)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `error_message` text DEFAULT NULL COMMENT 'ข้อความข้อผิดพลาด (ถ้ามี)'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `session_id` varchar(255) DEFAULT NULL COMMENT 'Session ID ของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'เวลาที่เกิดการกระทำ'");
        DB::statement("ALTER TABLE `laravel_audit_logs` MODIFY COLUMN `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'เวลาที่อัปเดต'");

        // ตาราง laravel_roles
        DB::statement("ALTER TABLE `laravel_roles` COMMENT = 'ตารางบทบาท - เก็บข้อมูลบทบาทและสิทธิ์การเข้าถึง'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของบทบาท'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'ชื่อบทบาท (ไม่ซ้ำกัน)'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `slug` varchar(255) NOT NULL COMMENT 'slug สำหรับบทบาท'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `description` text DEFAULT NULL COMMENT 'คำอธิบายบทบาท'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `color` varchar(7) NOT NULL DEFAULT '#6c757d' COMMENT 'สีสำหรับแสดงผล'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะการใช้งาน'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'ลำดับการแสดงผล'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง'");
        DB::statement("ALTER TABLE `laravel_roles` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาอัปเดต'");

        // ตาราง laravel_permissions
        DB::statement("ALTER TABLE `laravel_permissions` COMMENT = 'ตารางสิทธิ์ - เก็บข้อมูลสิทธิ์การเข้าถึงระบบ'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของสิทธิ์'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'ชื่อสิทธิ์ (ไม่ซ้ำกัน)'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `slug` varchar(255) NOT NULL COMMENT 'slug สำหรับสิทธิ์'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `description` text DEFAULT NULL COMMENT 'คำอธิบายสิทธิ์'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `group` varchar(255) NOT NULL DEFAULT 'general' COMMENT 'กลุ่มสิทธิ์'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `action` varchar(255) DEFAULT NULL COMMENT 'การกระทำ (create, read, update, delete)'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `resource` varchar(255) DEFAULT NULL COMMENT 'ทรัพยากรที่เกี่ยวข้อง'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะการใช้งาน'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'ลำดับการแสดงผล'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง'");
        DB::statement("ALTER TABLE `laravel_permissions` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาอัปเดต'");

        // ตาราง laravel_role_permissions
        DB::statement("ALTER TABLE `laravel_role_permissions` COMMENT = 'ตารางความสัมพันธ์บทบาท-สิทธิ์ - เก็บการเชื่อมโยงระหว่างบทบาทและสิทธิ์'");
        DB::statement("ALTER TABLE `laravel_role_permissions` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของความสัมพันธ์'");
        DB::statement("ALTER TABLE `laravel_role_permissions` MODIFY COLUMN `role_id` bigint(20) unsigned NOT NULL COMMENT 'ID ของบทบาท'");
        DB::statement("ALTER TABLE `laravel_role_permissions` MODIFY COLUMN `permission_id` bigint(20) unsigned NOT NULL COMMENT 'ID ของสิทธิ์'");
        DB::statement("ALTER TABLE `laravel_role_permissions` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง'");
        DB::statement("ALTER TABLE `laravel_role_permissions` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาอัปเดต'");

        // ตาราง laravel_user_roles
        DB::statement("ALTER TABLE `laravel_user_roles` COMMENT = 'ตารางความสัมพันธ์ผู้ใช้-บทบาท - เก็บการเชื่อมโยงระหว่างผู้ใช้และบทบาท'");
        DB::statement("ALTER TABLE `laravel_user_roles` MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID หลักของความสัมพันธ์'");
        DB::statement("ALTER TABLE `laravel_user_roles` MODIFY COLUMN `user_id` bigint(20) unsigned NOT NULL COMMENT 'ID ของผู้ใช้'");
        DB::statement("ALTER TABLE `laravel_user_roles` MODIFY COLUMN `role_id` bigint(20) unsigned NOT NULL COMMENT 'ID ของบทบาท'");
        DB::statement("ALTER TABLE `laravel_user_roles` MODIFY COLUMN `created_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสร้าง'");
        DB::statement("ALTER TABLE `laravel_user_roles` MODIFY COLUMN `updated_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาอัปเดต'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบคอมเม้นท์ออกจากตารางทั้งหมด
        $tables = [
            'laravel_users',
            'laravel_password_reset_tokens', 
            'laravel_sessions',
            'laravel_cache',
            'laravel_cache_locks',
            'laravel_jobs',
            'laravel_job_batches',
            'laravel_failed_jobs',
            'laravel_settings',
            'laravel_audit_logs',
            'laravel_roles',
            'laravel_permissions',
            'laravel_role_permissions',
            'laravel_user_roles'
        ];

        foreach ($tables as $table) {
            DB::statement("ALTER TABLE `{$table}` COMMENT = ''");
        }
    }
};