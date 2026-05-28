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
        if (!Schema::hasTable('core_cache')) {
            Schema::create('core_cache', function (Blueprint $table) {
                $table->string('key')->primary(); // คีย์สำหรับ Cache (Primary Key)
                $table->mediumText('value'); // ข้อมูลที่เก็บใน Cache
                $table->integer('expiration'); // เวลาหมดอายุของ Cache (Timestamp)
            });
        }

        if (!Schema::hasTable('core_cache_locks')) {
            Schema::create('core_cache_locks', function (Blueprint $table) {
                $table->string('key')->primary(); // คีย์สำหรับ Cache Lock (Primary Key)
                $table->string('owner'); // เจ้าของ Lock
                $table->integer('expiration'); // เวลาหมดอายุของ Lock (Timestamp)
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_cache');
        Schema::dropIfExists('core_cache_locks');
    }
};