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
        Schema::create('laravel_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ชื่อบทบาท (ไม่ซ้ำกัน)
            $table->string('slug')->unique(); // slug สำหรับบทบาท
            $table->text('description')->nullable(); // คำอธิบายบทบาท
            $table->string('color', 7)->default('#6c757d'); // สีสำหรับแสดงผล
            $table->boolean('is_active')->default(true); // สถานะการใช้งาน
            $table->integer('sort_order')->default(0); // ลำดับการแสดงผล
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_roles');
    }
};
