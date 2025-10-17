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
        Schema::create('core_audit_logs', function (Blueprint $table) {
            $table->id(); // รหัส Audit Log (Primary Key)
            $table->string('user_type')->nullable(); // ประเภทผู้ใช้ (Model class name)
            $table->unsignedBigInteger('user_id')->nullable(); // รหัสผู้ใช้ที่ทำการเปลี่ยนแปลง
            $table->string('event'); // ประเภทเหตุการณ์ (created, updated, deleted, etc.)
            $table->string('auditable_type'); // ประเภทข้อมูลที่ถูกเปลี่ยนแปลง (Model class name)
            $table->unsignedBigInteger('auditable_id'); // รหัสข้อมูลที่ถูกเปลี่ยนแปลง
            $table->json('old_values')->nullable(); // ค่าเดิมก่อนการเปลี่ยนแปลง (JSON)
            $table->json('new_values')->nullable(); // ค่าใหม่หลังการเปลี่ยนแปลง (JSON)
            $table->text('url')->nullable(); // URL ที่เกิดการเปลี่ยนแปลง
            $table->ipAddress('ip_address')->nullable(); // IP Address ของผู้ใช้
            $table->string('user_agent')->nullable(); // ข้อมูล Browser ของผู้ใช้
            $table->string('tags')->nullable(); // Tags เพิ่มเติมสำหรับการจัดกลุ่ม
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->index(['auditable_type', 'auditable_id']); // Index สำหรับค้นหาตามข้อมูลที่เปลี่ยนแปลง
            $table->index(['user_type', 'user_id']); // Index สำหรับค้นหาตามผู้ใช้
            $table->index('created_at'); // Index สำหรับค้นหาตามวันที่
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_audit_logs');
    }
};
