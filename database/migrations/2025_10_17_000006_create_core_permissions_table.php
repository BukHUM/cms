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
        Schema::create('core_permissions', function (Blueprint $table) {
            $table->id(); // รหัสสิทธิ์ (Primary Key)
            $table->string('name')->unique(); // ชื่อสิทธิ์ (เช่น 'users.create', 'settings.edit')
            $table->string('display_name'); // ชื่อที่แสดงให้ผู้ใช้เห็น
            $table->text('description')->nullable(); // คำอธิบายสิทธิ์
            $table->string('group')->nullable(); // กลุ่มสิทธิ์ (เช่น 'users', 'settings', 'roles')
            $table->boolean('is_active')->default(true); // สถานะการใช้งานสิทธิ์
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->index('name'); // Index สำหรับค้นหาตามชื่อสิทธิ์
            $table->index('group'); // Index สำหรับค้นหาตามกลุ่มสิทธิ์
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_permissions');
    }
};
