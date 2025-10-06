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
        Schema::create('laravel_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); // ID ของผู้ใช้ (อาจเป็น email หรือ ID)
            $table->string('user_email')->nullable(); // อีเมลของผู้ใช้
            $table->string('action'); // การกระทำที่ทำ (login, logout, create, update, delete, view)
            $table->string('resource_type')->nullable(); // ประเภทของข้อมูลที่เกี่ยวข้อง (user, settings, etc.)
            $table->string('resource_id')->nullable(); // ID ของข้อมูลที่เกี่ยวข้อง
            $table->text('description')->nullable(); // คำอธิบายการกระทำ
            $table->string('ip_address')->nullable(); // IP Address
            $table->string('user_agent')->nullable(); // User Agent
            $table->json('old_values')->nullable(); // ค่าเดิม (สำหรับการแก้ไข)
            $table->json('new_values')->nullable(); // ค่าใหม่ (สำหรับการแก้ไข)
            $table->string('status')->default('success'); // สถานะ (success, failed, error)
            $table->text('error_message')->nullable(); // ข้อความข้อผิดพลาด (ถ้ามี)
            $table->string('session_id')->nullable(); // Session ID
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['resource_type', 'resource_id']);
            $table->index(['ip_address', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_audit_logs');
    }
};
