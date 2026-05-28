@extends('layouts.admin')

@section('title', 'รายงาน')
@section('subtitle', 'รายงานและสถิติการใช้งานระบบ')

@section('content')
<div class="space-y-6">
    <!-- Report Filters -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                <i class="fas fa-filter mr-3 text-primary"></i>
                ตัวกรองรายงาน
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">วันที่เริ่มต้น</label>
                    <input type="date" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">วันที่สิ้นสุด</label>
                    <input type="date" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ประเภทรายงาน</label>
                    <select class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                        <option value="all">ทั้งหมด</option>
                        <option value="users">ผู้ใช้</option>
                        <option value="activity">กิจกรรม</option>
                        <option value="system">ระบบ</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="w-full bg-gradient-primary text-white px-4 py-3 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-search mr-2"></i>
                        สร้างรายงาน
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Activity Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-primary"></i>
                    กิจกรรมผู้ใช้
                </h3>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-slate-50 rounded-xl">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-600">กราฟกิจกรรมผู้ใช้</p>
                        <p class="text-sm text-slate-500">ข้อมูลจะแสดงที่นี่</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Performance Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-primary"></i>
                    ประสิทธิภาพระบบ
                </h3>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-slate-50 rounded-xl">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-600">กราฟประสิทธิภาพระบบ</p>
                        <p class="text-sm text-slate-500">ข้อมูลจะแสดงที่นี่</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                <i class="fas fa-download mr-3 text-primary"></i>
                ส่งออกรายงาน
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="flex items-center justify-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors border-2 border-transparent hover:border-primary/20">
                    <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                    <div class="text-left">
                        <h4 class="font-semibold text-slate-800">PDF</h4>
                        <p class="text-sm text-slate-600">ส่งออกเป็น PDF</p>
                    </div>
                </button>
                
                <button class="flex items-center justify-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors border-2 border-transparent hover:border-primary/20">
                    <i class="fas fa-file-excel text-green-600 text-xl"></i>
                    <div class="text-left">
                        <h4 class="font-semibold text-slate-800">Excel</h4>
                        <p class="text-sm text-slate-600">ส่งออกเป็น Excel</p>
                    </div>
                </button>
                
                <button class="flex items-center justify-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors border-2 border-transparent hover:border-primary/20">
                    <i class="fas fa-file-csv text-blue-600 text-xl"></i>
                    <div class="text-left">
                        <h4 class="font-semibold text-slate-800">CSV</h4>
                        <p class="text-sm text-slate-600">ส่งออกเป็น CSV</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection