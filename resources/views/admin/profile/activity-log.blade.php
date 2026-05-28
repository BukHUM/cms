@extends('layouts.admin')

@section('title', 'ประวัติการใช้งาน')
@section('subtitle', 'ดูประวัติการใช้งานและกิจกรรมของคุณ')

@section('content')
<div class="space-y-6">
    <!-- Activity Log Header -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-history mr-3 text-primary"></i>
                    ประวัติการใช้งาน
                </h3>
                <div class="flex items-center gap-3">
                    <!-- Filter -->
                    <select class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10">
                        <option value="all">ทั้งหมด</option>
                        <option value="login">เข้าสู่ระบบ</option>
                        <option value="logout">ออกจากระบบ</option>
                        <option value="profile_update">อัปเดตโปรไฟล์</option>
                        <option value="password_change">เปลี่ยนรหัสผ่าน</option>
                    </select>
                    <!-- Export -->
                    <button class="px-3 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors">
                        <i class="fas fa-download mr-1"></i>
                        ส่งออก
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Activity List -->
        <div class="p-6">
            <div class="space-y-4">
                <!-- Sample Activities -->
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-sign-in-alt text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-slate-800">เข้าสู่ระบบ</h4>
                            <span class="text-xs text-slate-500">2 ชั่วโมงที่แล้ว</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">เข้าสู่ระบบจาก IP: 192.168.1.100</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">สำเร็จ</span>
                            <span class="text-xs text-slate-500">Browser: Chrome 120.0</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-edit text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-slate-800">อัปเดตโปรไฟล์</h4>
                            <span class="text-xs text-slate-500">1 วันที่แล้ว</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">แก้ไขข้อมูลส่วนตัว</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">อัปเดต</span>
                            <span class="text-xs text-slate-500">IP: 192.168.1.100</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-key text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-slate-800">เปลี่ยนรหัสผ่าน</h4>
                            <span class="text-xs text-slate-500">3 วันที่แล้ว</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">อัปเดตรหัสผ่านใหม่</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">ความปลอดภัย</span>
                            <span class="text-xs text-slate-500">IP: 192.168.1.100</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-sign-out-alt text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-slate-800">ออกจากระบบ</h4>
                            <span class="text-xs text-slate-500">1 สัปดาห์ที่แล้ว</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">ออกจากระบบปกติ</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">ปกติ</span>
                            <span class="text-xs text-slate-500">IP: 192.168.1.100</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-slate-700">
                    แสดง <span class="font-medium">1</span> ถึง <span class="font-medium">10</span> จาก <span class="font-medium">47</span> รายการ
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        ก่อนหน้า
                    </button>
                    <button class="px-3 py-2 text-sm font-medium text-white bg-primary border border-primary rounded-lg hover:bg-primary-dark">
                        1
                    </button>
                    <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                        2
                    </button>
                    <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                        3
                    </button>
                    <button class="px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                        ถัดไป
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection