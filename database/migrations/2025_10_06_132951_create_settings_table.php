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
        // ตารางการตั้งค่าระบบ - เก็บการตั้งค่าต่าง ๆ ของระบบ
        Schema::create('laravel_settings', function (Blueprint $table) {
            $table->id(); // ID หลักของการตั้งค่า
            $table->string('key')->unique(); // คีย์ของการตั้งค่า (ไม่ซ้ำกัน)
            $table->text('value')->nullable(); // ค่าของการตั้งค่า
            $table->string('type')->default('string'); // ประเภทข้อมูล (string, boolean, integer, json)
            $table->text('description')->nullable(); // คำอธิบายการตั้งค่า
            $table->timestamps(); // เวลาสร้างและแก้ไข
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_settings');
    }
};
