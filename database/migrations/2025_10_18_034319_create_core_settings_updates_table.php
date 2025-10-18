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
        Schema::create('core_settings_updates', function (Blueprint $table) {
            $table->id();
            $table->string('update_type')->comment('ประเภทการอัพเดต: core, package, config');
            $table->string('component_name')->comment('ชื่อ component ที่อัพเดต');
            $table->string('current_version')->nullable()->comment('เวอร์ชันปัจจุบัน');
            $table->string('target_version')->comment('เวอร์ชันเป้าหมาย');
            $table->text('description')->nullable()->comment('รายละเอียดการอัพเดต');
            $table->text('changelog')->nullable()->comment('รายการเปลี่ยนแปลง');
            $table->json('dependencies')->nullable()->comment('Dependencies ที่เกี่ยวข้อง');
            $table->json('backup_files')->nullable()->comment('ไฟล์ที่ต้อง backup');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('error_message')->nullable()->comment('ข้อความ error');
            $table->json('execution_log')->nullable()->comment('Log การทำงาน');
            $table->timestamp('scheduled_at')->nullable()->comment('เวลาที่กำหนดให้รัน');
            $table->timestamp('started_at')->nullable()->comment('เวลาเริ่มต้น');
            $table->timestamp('completed_at')->nullable()->comment('เวลาสำเร็จ');
            $table->unsignedBigInteger('created_by')->comment('ผู้สร้าง');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ผู้แก้ไขล่าสุด');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('core_users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('core_users')->onDelete('set null');
            
            // Indexes
            $table->index(['update_type', 'status']);
            $table->index(['component_name', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_settings_updates');
    }
};
