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
        Schema::create('core_user_roles', function (Blueprint $table) {
            $table->id(); // รหัสความสัมพันธ์ผู้ใช้-บทบาท (Primary Key)
            $table->foreignId('user_id')->constrained('core_users')->onDelete('cascade'); // รหัสผู้ใช้ (Foreign Key)
            $table->foreignId('role_id')->constrained('core_roles')->onDelete('cascade'); // รหัสบทบาท (Foreign Key)
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->unique(['user_id', 'role_id']); // ไม่ให้ผู้ใช้และบทบาทซ้ำกัน
            $table->index('user_id'); // Index สำหรับค้นหาตามผู้ใช้
            $table->index('role_id'); // Index สำหรับค้นหาตามบทบาท
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_user_roles');
    }
};
