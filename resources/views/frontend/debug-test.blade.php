@extends('layouts.frontend')

@section('title', 'Debug Test')
@section('description', 'หน้าทดสอบ Laravel Debugbar')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 mb-4">Laravel Debugbar Test</h1>
            <p class="lead">หน้าทดสอบการทำงานของ Laravel Debugbar</p>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">ข้อมูลทดสอบ</h5>
                        </div>
                        <div class="card-body">
                            <h6>ข้อมูลจากฐานข้อมูล:</h6>
                            <ul class="list-unstyled">
                                <li><strong>ผู้ใช้ทั้งหมด:</strong> {{ $totalUsers ?? 'N/A' }}</li>
                                <li><strong>วันที่ปัจจุบัน:</strong> {{ now()->format('d/m/Y H:i:s') }}</li>
                                <li><strong>Environment:</strong> {{ app()->environment() }}</li>
                                <li><strong>Debug Mode:</strong> {{ config('app.debug') ? 'เปิด' : 'ปิด' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">การทดสอบ Debugbar</h5>
                        </div>
                        <div class="card-body">
                            <p>Debugbar จะแสดงข้อมูลต่อไปนี้:</p>
                            <ul>
                                <li>SQL Queries</li>
                                <li>Route Information</li>
                                <li>View Data</li>
                                <li>Session Data</li>
                                <li>Performance Metrics</li>
                                <li>Memory Usage</li>
                            </ul>
                            
                            <div class="mt-3">
                                <button class="btn btn-primary" onclick="testAjax()">
                                    ทดสอบ AJAX Request
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">ข้อมูลเพิ่มเติม</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Laravel Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Laravel Version:</strong> {{ app()->version() }}</li>
                                        <li><strong>PHP Version:</strong> {{ PHP_VERSION }}</li>
                                        <li><strong>Server:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6>Database Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Connection:</strong> {{ config('database.default') }}</li>
                                        <li><strong>Database:</strong> {{ config('database.connections.mysql.database') }}</li>
                                        <li><strong>Host:</strong> {{ config('database.connections.mysql.host') }}</li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6>Cache Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Cache Driver:</strong> {{ config('cache.default') }}</li>
                                        <li><strong>Session Driver:</strong> {{ config('session.driver') }}</li>
                                        <li><strong>Queue Driver:</strong> {{ config('queue.default') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        SwalHelper.success('AJAX Request สำเร็จ! ดูข้อมูลใน Debugbar');
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        SwalHelper.error('AJAX Request ล้มเหลว! ดูข้อมูลใน Debugbar');
    });
}

// Test console logging
console.log('Debug Test Page Loaded');
console.log('Current URL:', window.location.href);
console.log('User Agent:', navigator.userAgent);
</script>
@endpush
