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
        Schema::create('core_roles', function (Blueprint $table) {
            $table->id(); // รหัสบทบาท (Primary Key)
            $table->string('name')->unique(); // ชื่อบทบาท (เช่น 'admin', 'editor', 'user')
            $table->string('display_name'); // ชื่อที่แสดงให้ผู้ใช้เห็น
            $table->text('description')->nullable(); // คำอธิบายบทบาท
            $table->boolean('is_active')->default(true); // สถานะการใช้งานบทบาท
            $table->boolean('is_system')->default(false); // บทบาทระบบ (ไม่สามารถลบได้)
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->index('name'); // Index สำหรับค้นหาตามชื่อบทบาท
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_roles');
    }
};
