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
        // ลบการตั้งค่าเก่าที่ไม่ต้องการ
        DB::table('core_settings')
            ->where('category', 'general')
            ->whereIn('key', ['enable_comments', 'enable_registration', 'site_author', 'site_currency'])
            ->delete();

        // เพิ่มการตั้งค่าใหม่
        $newSettings = [
            [
                'key' => 'debug_mode',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'เปิดใช้งาน debug mode',
                'is_active' => false,
                'is_public' => false,
                'sort_order' => 100,
                'created_at' => now(),
                'updated_at' => now(),
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
                'sort_order' => 101,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($newSettings as $setting) {
            DB::table('core_settings')->updateOrInsert(
                ['key' => $setting['key'], 'category' => $setting['category']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบการตั้งค่าใหม่
        DB::table('core_settings')
            ->where('category', 'general')
            ->whereIn('key', ['debug_mode', 'debug_bar'])
            ->delete();

        // เพิ่มการตั้งค่าเก่ากลับ
        $oldSettings = [
            [
                'key' => 'enable_comments',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'เปิดใช้งานระบบความคิดเห็น',
                'is_active' => false,
                'is_public' => false,
                'sort_order' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_registration',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'general',
                'group_name' => 'system',
                'description' => 'เปิดใช้งานการสมัครสมาชิก',
                'is_active' => true,
                'is_public' => false,
                'sort_order' => 101,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_author',
                'value' => 'Admin',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'ผู้เขียนเว็บไซต์',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 102,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_currency',
                'value' => 'THB',
                'type' => 'string',
                'category' => 'general',
                'group_name' => 'site',
                'description' => 'สกุลเงินหลัก',
                'is_active' => true,
                'is_public' => true,
                'sort_order' => 103,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($oldSettings as $setting) {
            DB::table('core_settings')->updateOrInsert(
                ['key' => $setting['key'], 'category' => $setting['category']],
                $setting
            );
        }
    }
};