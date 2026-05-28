@extends('layouts.frontend')

@section('title', 'Debug Test')
@section('description', 'หน้าทดสอบ Laravel Debugbar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-5xl lg:text-6xl font-bold text-slate-800 mb-6">Laravel Debugbar Test</h1>
        <p class="text-xl text-slate-600 max-w-3xl mx-auto">
            หน้าทดสอบการทำงานของ Laravel Debugbar
        </p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Test Data Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
            <div class="bg-gradient-primary text-white p-6 rounded-t-2xl -m-8 mb-6">
                <h5 class="text-xl font-semibold">ข้อมูลทดสอบ</h5>
            </div>
            <div class="space-y-4">
                <h6 class="text-lg font-semibold text-slate-800 mb-4">ข้อมูลจากฐานข้อมูล:</h6>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">ผู้ใช้ทั้งหมด:</span>
                        <span class="text-slate-600">{{ $totalUsers ?? 'N/A' }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">วันที่ปัจจุบัน:</span>
                        <span class="text-slate-600">{{ now()->format('d/m/Y H:i:s') }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">Environment:</span>
                        <span class="text-slate-600">{{ app()->environment() }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2">
                        <span class="font-semibold text-slate-700">Debug Mode:</span>
                        <span class="text-slate-600">{{ config('app.debug') ? 'เปิด' : 'ปิด' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Debugbar Test Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
            <div class="bg-gradient-info text-white p-6 rounded-t-2xl -m-8 mb-6">
                <h5 class="text-xl font-semibold">การทดสอบ Debugbar</h5>
            </div>
            <div class="space-y-4">
                <p class="text-slate-600 mb-4">Debugbar จะแสดงข้อมูลต่อไปนี้:</p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        SQL Queries
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Route Information
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        View Data
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Session Data
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Performance Metrics
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Memory Usage
                    </li>
                </ul>
                
                <div class="mt-6">
                    <button class="w-full bg-gradient-primary text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3" 
                            onclick="testAjax()">
                        <i class="fas fa-play"></i>
                        ทดสอบ AJAX Request
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
        <div class="bg-gradient-success text-white p-6 rounded-t-2xl -m-8 mb-6">
            <h5 class="text-xl font-semibold">ข้อมูลเพิ่มเติม</h5>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h6 class="text-lg font-semibold text-slate-800 mb-4">Laravel Information</h6>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">Laravel Version:</span>
                        <span class="text-slate-600">{{ app()->version() }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">PHP Version:</span>
                        <span class="text-slate-600">{{ PHP_VERSION }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2">
                        <span class="font-semibold text-slate-700">Server:</span>
                        <span class="text-slate-600">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h6 class="text-lg font-semibold text-slate-800 mb-4">Database Information</h6>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">Connection:</span>
                        <span class="text-slate-600">{{ config('database.default') }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">Database:</span>
                        <span class="text-slate-600">{{ config('database.connections.mysql.database') }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2">
                        <span class="font-semibold text-slate-700">Host:</span>
                        <span class="text-slate-600">{{ config('database.connections.mysql.host') }}</span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h6 class="text-lg font-semibold text-slate-800 mb-4">Cache Information</h6>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">Cache Driver:</span>
                        <span class="text-slate-600">{{ config('cache.default') }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">Session Driver:</span>
                        <span class="text-slate-600">{{ config('session.driver') }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2">
                        <span class="font-semibold text-slate-700">Queue Driver:</span>
                        <span class="text-slate-600">{{ config('queue.default') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testAjax() {
    fetch('/api/test', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('AJAX Response:', data);
        alert('AJAX Request สำเร็จ! ดูข้อมูลใน Debugbar');
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        alert('AJAX Request ล้มเหลว! ดูข้อมูลใน Debugbar');
    });
}

// Test console logging
console.log('Debug Test Page Loaded');
console.log('Current URL:', window.location.href);
console.log('User Agent:', navigator.userAgent);
</script>
@endpush
