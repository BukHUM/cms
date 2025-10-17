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
        if (!Schema::hasTable('core_users')) {
            Schema::create('core_users', function (Blueprint $table) {
                $table->id(); // รหัสผู้ใช้ (Primary Key)
                $table->string('name'); // ชื่อผู้ใช้
                $table->string('email')->unique(); // อีเมล (ไม่ซ้ำกัน)
                $table->timestamp('email_verified_at')->nullable(); // วันที่ยืนยันอีเมล
                $table->string('password'); // รหัสผ่าน (เข้ารหัสแล้ว)
                $table->rememberToken(); // Token สำหรับจำการเข้าสู่ระบบ
                $table->timestamps(); // วันที่สร้างและแก้ไข
            });
        }

        if (!Schema::hasTable('core_password_reset_tokens')) {
            Schema::create('core_password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary(); // อีเมล (Primary Key)
                $table->string('token'); // Token สำหรับรีเซ็ตรหัสผ่าน
                $table->timestamp('created_at')->nullable(); // วันที่สร้าง Token
            });
        }

        if (!Schema::hasTable('core_sessions')) {
            Schema::create('core_sessions', function (Blueprint $table) {
                $table->string('id')->primary(); // รหัส Session (Primary Key)
                $table->foreignId('user_id')->nullable()->index(); // รหัสผู้ใช้ (Foreign Key)
                $table->string('ip_address', 45)->nullable(); // IP Address ของผู้ใช้
                $table->text('user_agent')->nullable(); // ข้อมูล Browser ของผู้ใช้
                $table->longText('payload'); // ข้อมูล Session
                $table->integer('last_activity')->index(); // เวลาที่ใช้งานล่าสุด (Timestamp)
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_users');
        Schema::dropIfExists('core_password_reset_tokens');
        Schema::dropIfExists('core_sessions');
    }
};