@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'ภาพรวมระบบและสถิติการใช้งาน')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0">
            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard
        </h2>
        <p class="text-muted">ภาพรวมของระบบจัดการ</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ผู้ใช้ทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalUsers ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            ผู้ใช้ใหม่วันนี้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $newUsersToday ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            การเข้าชมวันนี้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $visitsToday ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-eye fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            งานที่รอคิว
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $pendingJobs ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- User Activity Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">กราฟกิจกรรมผู้ใช้</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item" href="#">รายสัปดาห์</a>
                        <a class="dropdown-item" href="#">รายเดือน</a>
                        <a class="dropdown-item" href="#">รายปี</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="userActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">สถานะระบบ</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>ฐานข้อมูล</span>
                        <span class="badge bg-success">ปกติ</span>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>เซิร์ฟเวอร์</span>
                        <span class="badge bg-success">ปกติ</span>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 95%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>พื้นที่เก็บข้อมูล</span>
                        <span class="badge bg-warning">75%</span>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 75%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>RAM</span>
                        <span class="badge bg-info">60%</span>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">กิจกรรมล่าสุด</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">ผู้ใช้ใหม่สมัครสมาชิก</h6>
                            <p class="timeline-text">john.doe@example.com สมัครสมาชิกใหม่</p>
                            <small class="text-muted">2 นาทีที่แล้ว</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">อัปเดตข้อมูลสำเร็จ</h6>
                            <p class="timeline-text">ระบบอัปเดตข้อมูลผู้ใช้เรียบร้อย</p>
                            <small class="text-muted">5 นาทีที่แล้ว</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">การสำรองข้อมูล</h6>
                            <p class="timeline-text">เริ่มกระบวนการสำรองข้อมูลอัตโนมัติ</p>
                            <small class="text-muted">10 นาทีที่แล้ว</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">ผู้ใช้ที่ออนไลน์</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="https://placehold.co/40" class="rounded-circle me-3" alt="User">
                            <div>
                                <h6 class="mb-0">John Doe</h6>
                                <small class="text-muted">Admin</small>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">ออนไลน์</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="https://placehold.co/40" class="rounded-circle me-3" alt="User">
                            <div>
                                <h6 class="mb-0">Jane Smith</h6>
                                <small class="text-muted">User</small>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">ออนไลน์</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="https://placehold.co/40" class="rounded-circle me-3" alt="User">
                            <div>
                                <h6 class="mb-0">Bob Johnson</h6>
                                <small class="text-muted">User</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary rounded-pill">ออฟไลน์</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #e9ecef;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 5px;
}
</style>
@endpush

@push('scripts')
<script>
// User Activity Chart
const ctx = document.getElementById('userActivityChart').getContext('2d');
const userActivityChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
        datasets: [{
            label: 'ผู้ใช้ที่เข้าชม',
            data: [12, 19, 3, 5, 2, 3, 8],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'ผู้ใช้ใหม่',
            data: [2, 3, 20, 5, 1, 4, 1],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'สถิติการใช้งานรายสัปดาห์'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
