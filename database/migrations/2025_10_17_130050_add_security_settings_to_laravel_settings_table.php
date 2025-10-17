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
        // เพิ่มข้อมูลการตั้งค่าความปลอดภัยเริ่มต้น
        $securitySettings = [
            [
                'key' => 'security_session_lifetime',
                'value' => '120',
                'type' => 'integer',
                'description' => 'ระยะเวลา Session ในหน่วยนาที',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'security_max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'description' => 'จำนวนครั้งการเข้าสู่ระบบสูงสุดก่อนล็อค',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'security_password_min_length',
                'value' => '8',
                'type' => 'integer',
                'description' => 'ความยาวรหัสผ่านขั้นต่ำ',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'security_require_special_chars',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'ต้องมีอักขระพิเศษในรหัสผ่าน',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'security_two_factor_auth',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'เปิดใช้งาน Two-Factor Authentication',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'security_ip_whitelist',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'เปิดใช้งาน IP Whitelist',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // เพิ่มข้อมูลการตั้งค่าความปลอดภัยลงในตาราง
        foreach ($securitySettings as $setting) {
            DB::table('laravel_settings')->insertOrIgnore($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบข้อมูลการตั้งค่าความปลอดภัย
        DB::table('laravel_settings')->whereIn('key', [
            'security_session_lifetime',
            'security_max_login_attempts',
            'security_password_min_length',
            'security_require_special_chars',
            'security_two_factor_auth',
            'security_ip_whitelist'
        ])->delete();
    }
};