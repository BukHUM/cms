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
        // ตารางผู้ใช้ - เก็บข้อมูลผู้ใช้งานระบบ
        Schema::create('laravel_users', function (Blueprint $table) {
            $table->id(); // ID หลักของผู้ใช้
            $table->string('name'); // ชื่อ-นามสกุล
            $table->string('email')->unique(); // อีเมล (ไม่ซ้ำกัน)
            $table->timestamp('email_verified_at')->nullable(); // เวลาที่ยืนยันอีเมล
            $table->string('password'); // รหัสผ่าน (เข้ารหัสแล้ว)
            $table->string('role')->default('user'); // บทบาท (user, admin, etc.)
            $table->string('avatar')->nullable(); // รูปโปรไฟล์
            $table->timestamp('last_login_at')->nullable(); // เวลาเข้าสู่ระบบครั้งล่าสุด
            $table->rememberToken(); // Token สำหรับจำการล็อกอิน
            $table->timestamps(); // เวลาสร้างและแก้ไข
        });

        // ตารางรีเซ็ตรหัสผ่าน - เก็บ token สำหรับรีเซ็ตรหัสผ่าน
        Schema::create('laravel_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // อีเมลของผู้ใช้
            $table->string('token'); // Token สำหรับรีเซ็ต
            $table->timestamp('created_at')->nullable(); // เวลาสร้าง token
        });

        // ตารางเซสชัน - เก็บข้อมูลเซสชันของผู้ใช้
        Schema::create('laravel_sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID ของเซสชัน
            $table->foreignId('user_id')->nullable()->index(); // ID ผู้ใช้ (ถ้าล็อกอินแล้ว)
            $table->string('ip_address', 45)->nullable(); // IP Address
            $table->text('user_agent')->nullable(); // User Agent ของเบราว์เซอร์
            $table->longText('payload'); // ข้อมูลเซสชัน
            $table->integer('last_activity')->index(); // เวลากิจกรรมล่าสุด
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_users');
        Schema::dropIfExists('laravel_password_reset_tokens');
        Schema::dropIfExists('laravel_sessions');
    }
};
