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
        // ตารางงาน - เก็บงานที่ต้องประมวลผลในพื้นหลัง
        Schema::create('laravel_jobs', function (Blueprint $table) {
            $table->id(); // ID หลักของงาน
            $table->string('queue')->index(); // ชื่อคิวที่งานอยู่ใน
            $table->longText('payload'); // ข้อมูลงาน (JSON)
            $table->unsignedTinyInteger('attempts'); // จำนวนครั้งที่พยายามประมวลผล
            $table->unsignedInteger('reserved_at')->nullable(); // เวลาที่จองงานไว้
            $table->unsignedInteger('available_at'); // เวลาที่พร้อมประมวลผล
            $table->unsignedInteger('created_at'); // เวลาสร้างงาน
        });

        // ตารางกลุ่มงาน - เก็บข้อมูลกลุ่มงานที่ประมวลผลพร้อมกัน
        Schema::create('laravel_job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // ID ของกลุ่มงาน
            $table->string('name'); // ชื่อกลุ่มงาน
            $table->integer('total_jobs'); // จำนวนงานทั้งหมดในกลุ่ม
            $table->integer('pending_jobs'); // จำนวนงานที่รอประมวลผล
            $table->integer('failed_jobs'); // จำนวนงานที่ล้มเหลว
            $table->longText('failed_job_ids'); // ID ของงานที่ล้มเหลว
            $table->mediumText('options')->nullable(); // ตัวเลือกเพิ่มเติม
            $table->integer('cancelled_at')->nullable(); // เวลาที่ยกเลิกกลุ่มงาน
            $table->integer('created_at'); // เวลาสร้างกลุ่มงาน
            $table->integer('finished_at')->nullable(); // เวลาที่เสร็จสิ้นกลุ่มงาน
        });

        // ตารางงานที่ล้มเหลว - เก็บงานที่ประมวลผลไม่สำเร็จ
        Schema::create('laravel_failed_jobs', function (Blueprint $table) {
            $table->id(); // ID หลักของงานที่ล้มเหลว
            $table->string('uuid')->unique(); // UUID ของงาน
            $table->text('connection'); // การเชื่อมต่อฐานข้อมูลที่ใช้
            $table->text('queue'); // ชื่อคิวที่งานอยู่ใน
            $table->longText('payload'); // ข้อมูลงาน (JSON)
            $table->longText('exception'); // ข้อผิดพลาดที่เกิดขึ้น
            $table->timestamp('failed_at')->useCurrent(); // เวลาที่ล้มเหลว
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_jobs');
        Schema::dropIfExists('laravel_job_batches');
        Schema::dropIfExists('laravel_failed_jobs');
    }
};
