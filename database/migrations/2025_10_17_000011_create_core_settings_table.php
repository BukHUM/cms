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
        Schema::create('core_settings', function (Blueprint $table) {
            $table->id(); // รหัสการตั้งค่า (Primary Key)
            $table->string('key')->unique(); // คีย์การตั้งค่า (เช่น 'site_name', 'email_from', 'max_login_attempts')
            $table->text('value')->nullable(); // ค่าการตั้งค่า
            $table->string('type')->default('string'); // ประเภทข้อมูล (string, boolean, integer, json, etc.)
            $table->string('group')->default('general'); // กลุ่มการตั้งค่า (general, email, security, etc.)
            $table->text('description')->nullable(); // คำอธิบายการตั้งค่า
            $table->boolean('is_public')->default(false); // สามารถเข้าถึงได้จากภายนอกหรือไม่
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->index('key'); // Index สำหรับค้นหาตามคีย์
            $table->index('group'); // Index สำหรับค้นหาตามกลุ่ม
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_settings');
    }
};
