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
            $table->id();
            $table->string('key')->unique()->comment('รหัสการตั้งค่า (เช่น site_name, email_from)');
            $table->text('value')->nullable()->comment('ค่าการตั้งค่า');
            $table->enum('type', ['string', 'boolean', 'integer', 'float', 'email', 'url', 'json', 'array'])->default('string')->comment('ประเภทข้อมูล');
            $table->enum('category', ['general', 'performance', 'backup', 'email', 'security', 'system'])->default('general')->comment('หมวดหมู่การตั้งค่า');
            $table->string('group_name')->default('default')->comment('ชื่อกลุ่มย่อย');
            $table->text('description')->nullable()->comment('คำอธิบายการตั้งค่า');
            $table->boolean('is_active')->default(true)->comment('สถานะการใช้งาน');
            $table->boolean('is_public')->default(false)->comment('สามารถเข้าถึงได้จากภายนอก');
            $table->integer('sort_order')->default(0)->comment('ลำดับการแสดงผล');
            $table->json('validation_rules')->nullable()->comment('กฎการตรวจสอบ');
            $table->text('default_value')->nullable()->comment('ค่าเริ่มต้น');
            $table->json('options')->nullable()->comment('ตัวเลือกเพิ่มเติม');
            $table->unsignedBigInteger('created_by')->nullable()->comment('ผู้สร้าง');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ผู้แก้ไขล่าสุด');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['group_name']);
            $table->index(['sort_order']);
            $table->index(['created_by']);
            $table->index(['updated_by']);

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('core_users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('core_users')->onDelete('set null');
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