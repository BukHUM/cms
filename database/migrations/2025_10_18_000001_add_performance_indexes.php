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
        // เพิ่ม indexes เพื่อเพิ่มความเร็วในการค้นหา
        
        // 1. Users table indexes
        if (!Schema::hasColumn('laravel_users', 'email_index')) {
            DB::statement('ALTER TABLE `laravel_users` ADD INDEX `idx_email` (`email`)');
        }
        
        if (!Schema::hasColumn('laravel_users', 'status_index')) {
            DB::statement('ALTER TABLE `laravel_users` ADD INDEX `idx_status` (`status`)');
        }
        
        if (!Schema::hasColumn('laravel_users', 'last_login_index')) {
            DB::statement('ALTER TABLE `laravel_users` ADD INDEX `idx_last_login` (`last_login_at`)');
        }
        
        // 2. Audit logs table indexes
        if (!Schema::hasColumn('laravel_audit_logs', 'user_id_index')) {
            DB::statement('ALTER TABLE `laravel_audit_logs` ADD INDEX `idx_user_id` (`user_id`)');
        }
        
        if (!Schema::hasColumn('laravel_audit_logs', 'created_at_index')) {
            DB::statement('ALTER TABLE `laravel_audit_logs` ADD INDEX `idx_created_at` (`created_at`)');
        }
        
        if (!Schema::hasColumn('laravel_audit_logs', 'action_index')) {
            DB::statement('ALTER TABLE `laravel_audit_logs` ADD INDEX `idx_action` (`action`)');
        }
        
        if (!Schema::hasColumn('laravel_audit_logs', 'ip_address_index')) {
            DB::statement('ALTER TABLE `laravel_audit_logs` ADD INDEX `idx_ip_address` (`ip_address`)');
        }
        
        // 3. Login attempts table indexes
        if (!Schema::hasColumn('laravel_login_attempts', 'email_attempted_index')) {
            DB::statement('ALTER TABLE `laravel_login_attempts` ADD INDEX `idx_email_attempted` (`email`, `attempted_at`)');
        }
        
        if (!Schema::hasColumn('laravel_login_attempts', 'ip_attempted_index')) {
            DB::statement('ALTER TABLE `laravel_login_attempts` ADD INDEX `idx_ip_attempted` (`ip_address`, `attempted_at`)');
        }
        
        if (!Schema::hasColumn('laravel_login_attempts', 'success_index')) {
            DB::statement('ALTER TABLE `laravel_login_attempts` ADD INDEX `idx_success` (`success`)');
        }
        
        // 4. Settings table indexes
        if (!Schema::hasColumn('laravel_settings', 'key_index')) {
            DB::statement('ALTER TABLE `laravel_settings` ADD INDEX `idx_key` (`key`)');
        }
        
        if (!Schema::hasColumn('laravel_settings', 'updated_at_index')) {
            DB::statement('ALTER TABLE `laravel_settings` ADD INDEX `idx_updated_at` (`updated_at`)');
        }
        
        // 5. Roles and permissions indexes
        if (!Schema::hasColumn('laravel_roles', 'slug_index')) {
            DB::statement('ALTER TABLE `laravel_roles` ADD INDEX `idx_slug` (`slug`)');
        }
        
        if (!Schema::hasColumn('laravel_permissions', 'slug_index')) {
            DB::statement('ALTER TABLE `laravel_permissions` ADD INDEX `idx_slug` (`slug`)');
        }
        
        // 6. User roles pivot table indexes
        if (!Schema::hasColumn('laravel_user_roles', 'user_role_index')) {
            DB::statement('ALTER TABLE `laravel_user_roles` ADD INDEX `idx_user_role` (`user_id`, `role_id`)');
        }
        
        // 7. Role permissions pivot table indexes
        if (!Schema::hasColumn('laravel_role_permissions', 'role_permission_index')) {
            DB::statement('ALTER TABLE `laravel_role_permissions` ADD INDEX `idx_role_permission` (`role_id`, `permission_id`)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ indexes (ระวัง: อาจทำให้ query ช้าลง)
        DB::statement('ALTER TABLE `laravel_users` DROP INDEX `idx_email`');
        DB::statement('ALTER TABLE `laravel_users` DROP INDEX `idx_status`');
        DB::statement('ALTER TABLE `laravel_users` DROP INDEX `idx_last_login`');
        
        DB::statement('ALTER TABLE `laravel_audit_logs` DROP INDEX `idx_user_id`');
        DB::statement('ALTER TABLE `laravel_audit_logs` DROP INDEX `idx_created_at`');
        DB::statement('ALTER TABLE `laravel_audit_logs` DROP INDEX `idx_action`');
        DB::statement('ALTER TABLE `laravel_audit_logs` DROP INDEX `idx_ip_address`');
        
        DB::statement('ALTER TABLE `laravel_login_attempts` DROP INDEX `idx_email_attempted`');
        DB::statement('ALTER TABLE `laravel_login_attempts` DROP INDEX `idx_ip_attempted`');
        DB::statement('ALTER TABLE `laravel_login_attempts` DROP INDEX `idx_success`');
        
        DB::statement('ALTER TABLE `laravel_settings` DROP INDEX `idx_key`');
        DB::statement('ALTER TABLE `laravel_settings` DROP INDEX `idx_updated_at`');
        
        DB::statement('ALTER TABLE `laravel_roles` DROP INDEX `idx_slug`');
        DB::statement('ALTER TABLE `laravel_permissions` DROP INDEX `idx_slug`');
        
        DB::statement('ALTER TABLE `laravel_user_roles` DROP INDEX `idx_user_role`');
        DB::statement('ALTER TABLE `laravel_role_permissions` DROP INDEX `idx_role_permission`');
    }
};
