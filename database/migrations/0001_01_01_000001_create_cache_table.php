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
        // ตารางแคชหลัก - เก็บข้อมูลแคชของระบบ
        Schema::create('laravel_cache', function (Blueprint $table) {
            $table->string('key')->primary(); // คีย์ของข้อมูลแคช
            $table->mediumText('value'); // ค่าของข้อมูลแคช
            $table->integer('expiration'); // เวลาหมดอายุ (timestamp)
        });

        // ตารางแคชล็อค - เก็บข้อมูลล็อคสำหรับป้องกันการทำงานซ้ำ
        Schema::create('laravel_cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // คีย์ของล็อค
            $table->string('owner'); // เจ้าของล็อค
            $table->integer('expiration'); // เวลาหมดอายุของล็อค
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_cache');
        Schema::dropIfExists('laravel_cache_locks');
    }
};
