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
        Schema::create('laravel_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ชื่อสิทธิ์ (ไม่ซ้ำกัน)
            $table->string('slug')->unique(); // slug สำหรับสิทธิ์
            $table->text('description')->nullable(); // คำอธิบายสิทธิ์
            $table->string('group')->default('general'); // กลุ่มสิทธิ์
            $table->string('action')->nullable(); // การกระทำ (create, read, update, delete)
            $table->string('resource')->nullable(); // ทรัพยากรที่เกี่ยวข้อง
            $table->boolean('is_active')->default(true); // สถานะการใช้งาน
            $table->integer('sort_order')->default(0); // ลำดับการแสดงผล
            $table->timestamps();
            
            $table->index(['group', 'is_active', 'sort_order']);
            $table->index(['action', 'resource']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_permissions');
    }
};
