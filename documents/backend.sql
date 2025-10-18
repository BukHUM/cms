-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2025 at 09:38 AM
-- Server version: 11.4.8-MariaDB
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `core_audit_logs`
--

CREATE TABLE `core_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_cache`
--

CREATE TABLE `core_cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_cache_locks`
--

CREATE TABLE `core_cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_failed_jobs`
--

CREATE TABLE `core_failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_jobs`
--

CREATE TABLE `core_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_job_batches`
--

CREATE TABLE `core_job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_login_attempts`
--

CREATE TABLE `core_login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `failure_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_migrations`
--

CREATE TABLE `core_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_password_reset_tokens`
--

CREATE TABLE `core_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_performance_settings`
--

CREATE TABLE `core_performance_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` enum('string','integer','float','boolean','array','json') NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `category` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `validation_rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`validation_rules`)),
  `default_value` text DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_permissions`
--

CREATE TABLE `core_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_roles`
--

CREATE TABLE `core_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_role_permissions`
--

CREATE TABLE `core_role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_sessions`
--

CREATE TABLE `core_sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_settings`
--

CREATE TABLE `core_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL COMMENT 'รหัสการตั้งค่า (เช่น site_name, email_from)',
  `value` text DEFAULT NULL COMMENT 'ค่าการตั้งค่า',
  `type` enum('string','boolean','integer','float','email','url','json','array') NOT NULL DEFAULT 'string' COMMENT 'ประเภทข้อมูล',
  `category` enum('general','performance','backup','email','security','system') NOT NULL DEFAULT 'general' COMMENT 'หมวดหมู่การตั้งค่า',
  `group_name` varchar(255) NOT NULL DEFAULT 'default' COMMENT 'ชื่อกลุ่มย่อย',
  `description` text DEFAULT NULL COMMENT 'คำอธิบายการตั้งค่า',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะการใช้งาน',
  `is_public` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สามารถเข้าถึงได้จากภายนอก',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'ลำดับการแสดงผล',
  `validation_rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'กฎการตรวจสอบ' CHECK (json_valid(`validation_rules`)),
  `default_value` text DEFAULT NULL COMMENT 'ค่าเริ่มต้น',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'ตัวเลือกเพิ่มเติม' CHECK (json_valid(`options`)),
  `created_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ผู้สร้าง',
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ผู้แก้ไขล่าสุด',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_settings_backup`
--

CREATE TABLE `core_settings_backup` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL COMMENT 'รหัสการตั้งค่า',
  `value` text DEFAULT NULL COMMENT 'ค่าการตั้งค่า',
  `type` varchar(255) NOT NULL DEFAULT 'string' COMMENT 'ประเภทข้อมูล (string, boolean, integer, json)',
  `description` text DEFAULT NULL COMMENT 'คำอธิบายการตั้งค่า',
  `group` varchar(255) NOT NULL DEFAULT 'general' COMMENT 'กลุ่มการตั้งค่า',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'สถานะการใช้งาน',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'ลำดับการแสดงผล',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_settings_general`
--

CREATE TABLE `core_settings_general` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_settings_updates`
--

CREATE TABLE `core_settings_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `update_type` varchar(255) NOT NULL COMMENT 'ประเภทการอัพเดต: core, package, config',
  `component_name` varchar(255) NOT NULL COMMENT 'ชื่อ component ที่อัพเดต',
  `current_version` varchar(255) DEFAULT NULL COMMENT 'เวอร์ชันปัจจุบัน',
  `target_version` varchar(255) NOT NULL COMMENT 'เวอร์ชันเป้าหมาย',
  `description` text DEFAULT NULL COMMENT 'รายละเอียดการอัพเดต',
  `changelog` text DEFAULT NULL COMMENT 'รายการเปลี่ยนแปลง',
  `dependencies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Dependencies ที่เกี่ยวข้อง' CHECK (json_valid(`dependencies`)),
  `backup_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'ไฟล์ที่ต้อง backup' CHECK (json_valid(`backup_files`)),
  `status` enum('pending','in_progress','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `error_message` text DEFAULT NULL COMMENT 'ข้อความ error',
  `execution_log` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Log การทำงาน' CHECK (json_valid(`execution_log`)),
  `scheduled_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาที่กำหนดให้รัน',
  `started_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาเริ่มต้น',
  `completed_at` timestamp NULL DEFAULT NULL COMMENT 'เวลาสำเร็จ',
  `created_by` bigint(20) UNSIGNED NOT NULL COMMENT 'ผู้สร้าง',
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ผู้แก้ไขล่าสุด',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_users`
--

CREATE TABLE `core_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_user_roles`
--

CREATE TABLE `core_user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `core_audit_logs`
--
ALTER TABLE `core_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `core_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `core_audit_logs_user_type_user_id_index` (`user_type`,`user_id`),
  ADD KEY `core_audit_logs_created_at_index` (`created_at`);

--
-- Indexes for table `core_cache`
--
ALTER TABLE `core_cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `core_cache_locks`
--
ALTER TABLE `core_cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `core_failed_jobs`
--
ALTER TABLE `core_failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `core_jobs`
--
ALTER TABLE `core_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `core_job_batches`
--
ALTER TABLE `core_job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_login_attempts`
--
ALTER TABLE `core_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `core_login_attempts_email_ip_address_index` (`email`,`ip_address`),
  ADD KEY `core_login_attempts_created_at_index` (`created_at`);

--
-- Indexes for table `core_migrations`
--
ALTER TABLE `core_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_password_reset_tokens`
--
ALTER TABLE `core_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `core_performance_settings`
--
ALTER TABLE `core_performance_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_performance_settings_key_unique` (`key`),
  ADD KEY `core_performance_settings_category_is_active_index` (`category`,`is_active`),
  ADD KEY `core_performance_settings_type_index` (`type`),
  ADD KEY `core_performance_settings_sort_order_index` (`sort_order`),
  ADD KEY `core_performance_settings_created_by_index` (`created_by`),
  ADD KEY `core_performance_settings_updated_by_index` (`updated_by`);

--
-- Indexes for table `core_permissions`
--
ALTER TABLE `core_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_permissions_name_unique` (`name`),
  ADD KEY `core_permissions_name_index` (`name`),
  ADD KEY `core_permissions_group_index` (`group`);

--
-- Indexes for table `core_roles`
--
ALTER TABLE `core_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_roles_name_unique` (`name`),
  ADD KEY `core_roles_name_index` (`name`);

--
-- Indexes for table `core_role_permissions`
--
ALTER TABLE `core_role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `core_role_permissions_role_id_index` (`role_id`),
  ADD KEY `core_role_permissions_permission_id_index` (`permission_id`);

--
-- Indexes for table `core_sessions`
--
ALTER TABLE `core_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `core_settings`
--
ALTER TABLE `core_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_settings_key_unique` (`key`),
  ADD KEY `core_settings_category_is_active_index` (`category`,`is_active`),
  ADD KEY `core_settings_group_name_index` (`group_name`),
  ADD KEY `core_settings_sort_order_index` (`sort_order`),
  ADD KEY `core_settings_created_by_index` (`created_by`),
  ADD KEY `core_settings_updated_by_index` (`updated_by`);

--
-- Indexes for table `core_settings_backup`
--
ALTER TABLE `core_settings_backup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_settings_backup_key_unique` (`key`),
  ADD KEY `core_settings_backup_group_is_active_index` (`group`,`is_active`),
  ADD KEY `core_settings_backup_sort_order_index` (`sort_order`);

--
-- Indexes for table `core_settings_general`
--
ALTER TABLE `core_settings_general`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_settings_key_unique` (`key`),
  ADD KEY `core_settings_key_index` (`key`),
  ADD KEY `core_settings_group_index` (`group`);

--
-- Indexes for table `core_settings_updates`
--
ALTER TABLE `core_settings_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `core_settings_updates_created_by_foreign` (`created_by`),
  ADD KEY `core_settings_updates_updated_by_foreign` (`updated_by`),
  ADD KEY `core_settings_updates_update_type_status_index` (`update_type`,`status`),
  ADD KEY `core_settings_updates_component_name_status_index` (`component_name`,`status`),
  ADD KEY `core_settings_updates_scheduled_at_index` (`scheduled_at`);

--
-- Indexes for table `core_users`
--
ALTER TABLE `core_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `core_user_roles`
--
ALTER TABLE `core_user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `core_user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `core_user_roles_user_id_index` (`user_id`),
  ADD KEY `core_user_roles_role_id_index` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `core_audit_logs`
--
ALTER TABLE `core_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_failed_jobs`
--
ALTER TABLE `core_failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_jobs`
--
ALTER TABLE `core_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_login_attempts`
--
ALTER TABLE `core_login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_migrations`
--
ALTER TABLE `core_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_performance_settings`
--
ALTER TABLE `core_performance_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_permissions`
--
ALTER TABLE `core_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_roles`
--
ALTER TABLE `core_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_role_permissions`
--
ALTER TABLE `core_role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_settings`
--
ALTER TABLE `core_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_settings_backup`
--
ALTER TABLE `core_settings_backup`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_settings_general`
--
ALTER TABLE `core_settings_general`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_settings_updates`
--
ALTER TABLE `core_settings_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_users`
--
ALTER TABLE `core_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_user_roles`
--
ALTER TABLE `core_user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `core_performance_settings`
--
ALTER TABLE `core_performance_settings`
  ADD CONSTRAINT `core_performance_settings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `core_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `core_performance_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `core_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `core_role_permissions`
--
ALTER TABLE `core_role_permissions`
  ADD CONSTRAINT `core_role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `core_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `core_role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `core_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `core_settings`
--
ALTER TABLE `core_settings`
  ADD CONSTRAINT `core_settings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `core_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `core_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `core_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `core_settings_updates`
--
ALTER TABLE `core_settings_updates`
  ADD CONSTRAINT `core_settings_updates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `core_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `core_settings_updates_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `core_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `core_user_roles`
--
ALTER TABLE `core_user_roles`
  ADD CONSTRAINT `core_user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `core_roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `core_user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `core_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
