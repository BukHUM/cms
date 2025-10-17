<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('core_users', function (Blueprint $table) {
            if (!Schema::hasColumn('core_users', 'first_name')) {
                $table->string('first_name')->nullable(); // ชื่อจริง
            }
            if (!Schema::hasColumn('core_users', 'last_name')) {
                $table->string('last_name')->nullable(); // นามสกุล
            }
            if (!Schema::hasColumn('core_users', 'phone')) {
                $table->string('phone')->nullable(); // เบอร์โทรศัพท์
            }
            if (!Schema::hasColumn('core_users', 'avatar')) {
                $table->string('avatar')->nullable(); // รูปโปรไฟล์ (URL หรือ path)
            }
            if (!Schema::hasColumn('core_users', 'is_active')) {
                $table->boolean('is_active')->default(true); // สถานะการใช้งาน (true/false)
            }
            if (!Schema::hasColumn('core_users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable(); // เวลาเข้าสู่ระบบล่าสุด
            }
            if (!Schema::hasColumn('core_users', 'last_login_ip')) {
                $table->ipAddress('last_login_ip')->nullable(); // IP Address ที่เข้าสู่ระบบล่าสุด
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('core_users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name', 
                'phone',
                'avatar',
                'is_active',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
};