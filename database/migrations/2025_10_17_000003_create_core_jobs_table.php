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
        if (!Schema::hasTable('core_jobs')) {
            Schema::create('core_jobs', function (Blueprint $table) {
                $table->id(); // รหัส Job (Primary Key)
                $table->string('queue')->index(); // ชื่อ Queue ที่ Job อยู่ในคิว
                $table->longText('payload'); // ข้อมูล Job (JSON)
                $table->unsignedTinyInteger('attempts'); // จำนวนครั้งที่พยายามรัน
                $table->unsignedInteger('reserved_at')->nullable(); // เวลาที่ Job ถูกจอง (Timestamp)
                $table->unsignedInteger('available_at'); // เวลาที่ Job พร้อมรัน (Timestamp)
                $table->unsignedInteger('created_at'); // เวลาที่สร้าง Job (Timestamp)
            });
        }

        if (!Schema::hasTable('core_job_batches')) {
            Schema::create('core_job_batches', function (Blueprint $table) {
                $table->string('id')->primary(); // รหัส Batch (Primary Key)
                $table->string('name'); // ชื่อ Batch
                $table->integer('total_jobs'); // จำนวน Job ทั้งหมดใน Batch
                $table->integer('pending_jobs'); // จำนวน Job ที่รอการประมวลผล
                $table->integer('failed_jobs'); // จำนวน Job ที่ล้มเหลว
                $table->longText('failed_job_ids'); // รหัส Job ที่ล้มเหลว (JSON)
                $table->mediumText('options')->nullable(); // ตัวเลือกเพิ่มเติม (JSON)
                $table->integer('cancelled_at')->nullable(); // เวลาที่ยกเลิก Batch (Timestamp)
                $table->integer('created_at'); // เวลาที่สร้าง Batch (Timestamp)
                $table->integer('finished_at')->nullable(); // เวลาที่ Batch เสร็จสิ้น (Timestamp)
            });
        }

        if (!Schema::hasTable('core_failed_jobs')) {
            Schema::create('core_failed_jobs', function (Blueprint $table) {
                $table->id(); // รหัส Failed Job (Primary Key)
                $table->string('uuid')->unique(); // UUID ของ Job ที่ล้มเหลว
                $table->text('connection'); // ชื่อ Connection ที่ใช้
                $table->text('queue'); // ชื่อ Queue ที่ Job ล้มเหลว
                $table->longText('payload'); // ข้อมูล Job ที่ล้มเหลว (JSON)
                $table->longText('exception'); // ข้อมูล Exception ที่เกิดขึ้น
                $table->timestamp('failed_at')->useCurrent(); // เวลาที่ Job ล้มเหลว
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_jobs');
        Schema::dropIfExists('core_job_batches');
        Schema::dropIfExists('core_failed_jobs');
    }
};