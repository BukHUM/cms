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
        Schema::create('core_login_attempts', function (Blueprint $table) {
            $table->id(); // รหัสการพยายามเข้าสู่ระบบ (Primary Key)
            $table->string('email'); // อีเมลที่ใช้พยายามเข้าสู่ระบบ
            $table->ipAddress('ip_address'); // IP Address ที่ใช้เข้าสู่ระบบ
            $table->string('user_agent')->nullable(); // ข้อมูล Browser ที่ใช้เข้าสู่ระบบ
            $table->boolean('success')->default(false); // สถานะการเข้าสู่ระบบสำเร็จหรือไม่
            $table->text('failure_reason')->nullable(); // เหตุผลที่เข้าสู่ระบบล้มเหลว
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->index(['email', 'ip_address']); // Index สำหรับค้นหาตามอีเมลและ IP
            $table->index('created_at'); // Index สำหรับค้นหาตามวันที่
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_login_attempts');
    }
};
