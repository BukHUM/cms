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
        Schema::create('core_role_permissions', function (Blueprint $table) {
            $table->id(); // รหัสความสัมพันธ์บทบาท-สิทธิ์ (Primary Key)
            $table->foreignId('role_id')->constrained('core_roles')->onDelete('cascade'); // รหัสบทบาท (Foreign Key)
            $table->foreignId('permission_id')->constrained('core_permissions')->onDelete('cascade'); // รหัสสิทธิ์ (Foreign Key)
            $table->timestamps(); // วันที่สร้างและแก้ไข
            
            $table->unique(['role_id', 'permission_id']); // ไม่ให้บทบาทและสิทธิ์ซ้ำกัน
            $table->index('role_id'); // Index สำหรับค้นหาตามบทบาท
            $table->index('permission_id'); // Index สำหรับค้นหาตามสิทธิ์
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_role_permissions');
    }
};
