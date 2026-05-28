@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'ภาพรวมระบบและสถิติการใช้งาน')

@section('content')
<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-slate-800 mb-1">1,234</p>
                <p class="text-sm text-slate-600">ผู้ใช้ทั้งหมด</p>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i>
                +12.5%
            </span>
            <span class="text-slate-500 ml-2">จากเดือนที่แล้ว</span>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-success rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-slate-800 mb-1">89</p>
                <p class="text-sm text-slate-600">ผู้ใช้ออนไลน์</p>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i>
                +5.2%
            </span>
            <span class="text-slate-500 ml-2">จากเมื่อวาน</span>
        </div>
    </div>

    <!-- System Performance -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-info rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-tachometer-alt text-xl"></i>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-slate-800 mb-1">98.5%</p>
                <p class="text-sm text-slate-600">ประสิทธิภาพระบบ</p>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i>
                +2.1%
            </span>
            <span class="text-slate-500 ml-2">จากสัปดาห์ที่แล้ว</span>
        </div>
    </div>

    <!-- Storage Usage -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-warning rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-hdd text-xl"></i>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-slate-800 mb-1">67%</p>
                <p class="text-sm text-slate-600">พื้นที่เก็บข้อมูล</p>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-orange-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i>
                +8.3%
            </span>
            <span class="text-slate-500 ml-2">จากเดือนที่แล้ว</span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="bg-gradient-primary text-white px-6 py-4">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-history mr-3"></i>
                    กิจกรรมล่าสุด
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-plus text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">ผู้ใช้ใหม่ลงทะเบียน</p>
                            <p class="text-sm text-slate-600">John Doe ได้ลงทะเบียนในระบบ</p>
                            <p class="text-xs text-slate-500 mt-1">2 นาทีที่แล้ว</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cog text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">อัพเดทการตั้งค่า</p>
                            <p class="text-sm text-slate-600">Admin ได้แก้ไขการตั้งค่าระบบ</p>
                            <p class="text-xs text-slate-500 mt-1">15 นาทีที่แล้ว</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">การเข้าสู่ระบบ</p>
                            <p class="text-sm text-slate-600">ผู้ใช้เข้าสู่ระบบจาก IP ใหม่</p>
                            <p class="text-xs text-slate-500 mt-1">1 ชั่วโมงที่แล้ว</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 text-blue-600 hover:text-blue-700 transition-colors">
                        <span>ดูทั้งหมด</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="bg-gradient-info text-white px-6 py-4">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-bolt mr-3"></i>
                    การดำเนินการด่วน
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.user-management') }}" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all duration-300 border-2 border-transparent hover:border-blue-200 hover:shadow-md">
                        <div class="w-11 h-11 bg-gradient-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">จัดการผู้ใช้</h4>
                            <p class="text-sm text-slate-600">เพิ่ม แก้ไข หรือลบผู้ใช้</p>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400"></i>
                    </a>
                    
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all duration-300 border-2 border-transparent hover:border-blue-200 hover:shadow-md">
                        <div class="w-11 h-11 bg-gradient-info rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-cog text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">ตั้งค่าระบบ</h4>
                            <p class="text-sm text-slate-600">จัดการการตั้งค่าต่างๆ</p>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400"></i>
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all duration-300 border-2 border-transparent hover:border-blue-200 hover:shadow-md">
                        <div class="w-11 h-11 bg-gradient-success rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-chart-bar text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">รายงาน</h4>
                            <p class="text-sm text-slate-600">ดูสถิติและรายงาน</p>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400"></i>
                    </a>
                    
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all duration-300 border-2 border-transparent hover:border-blue-200 hover:shadow-md">
                        <div class="w-11 h-11 bg-gradient-warning rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-user text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">โปรไฟล์</h4>
                            <p class="text-sm text-slate-600">จัดการข้อมูลส่วนตัว</p>
                        </div>
                        <i class="fas fa-arrow-right text-slate-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-8">
        <!-- System Status -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="bg-gradient-success text-white px-6 py-4">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-server mr-3"></i>
                    สถานะระบบ
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="font-medium text-green-800">ฐานข้อมูล</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">ปกติ</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="font-medium text-green-800">เซิร์ฟเวอร์</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">ปกติ</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="font-medium text-yellow-800">พื้นที่เก็บข้อมูล</span>
                        </div>
                        <span class="text-sm font-semibold text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">67%</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="font-medium text-green-800">เครือข่าย</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">ปกติ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="bg-gradient-warning text-white px-6 py-4">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-user-friends mr-3"></i>
                    ผู้ใช้ล่าสุด
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                        <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center text-white font-semibold">
                            JD
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">John Doe</p>
                            <p class="text-sm text-slate-600">john@example.com</p>
                        </div>
                        <span class="text-xs text-slate-500">2 นาที</span>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                        <div class="w-10 h-10 bg-gradient-success rounded-lg flex items-center justify-center text-white font-semibold">
                            JS
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Jane Smith</p>
                            <p class="text-sm text-slate-600">jane@example.com</p>
                        </div>
                        <span class="text-xs text-slate-500">5 นาที</span>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                        <div class="w-10 h-10 bg-gradient-info rounded-lg flex items-center justify-center text-white font-semibold">
                            MJ
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Mike Johnson</p>
                            <p class="text-sm text-slate-600">mike@example.com</p>
                        </div>
                        <span class="text-xs text-slate-500">1 ชั่วโมง</span>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.user-management') }}" class="inline-flex items-center gap-2 px-4 py-2 text-blue-600 hover:text-blue-700 transition-colors">
                        <span>ดูทั้งหมด</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection