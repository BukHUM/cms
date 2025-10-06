@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'ภาพรวมระบบและสถิติการใช้งาน')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card animate-fade-in-up animate-stagger-1">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-number">{{ $totalUsers ?? 0 }}</div>
            <div class="stats-label">ผู้ใช้ทั้งหมด</div>
            <div class="stats-change positive">
                <i class="fas fa-arrow-up"></i>
                +12% จากเดือนที่แล้ว
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card animate-fade-in-up animate-stagger-2">
            <div class="stats-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stats-number">{{ $newUsersToday ?? 0 }}</div>
            <div class="stats-label">ผู้ใช้ใหม่วันนี้</div>
            <div class="stats-change positive">
                <i class="fas fa-arrow-up"></i>
                +8% จากเมื่อวาน
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card animate-fade-in-up animate-stagger-3">
            <div class="stats-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stats-number">{{ $visitsToday ?? 0 }}</div>
            <div class="stats-label">การเข้าชมวันนี้</div>
            <div class="stats-change positive">
                <i class="fas fa-arrow-up"></i>
                +15% จากสัปดาห์ที่แล้ว
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card animate-fade-in-up animate-stagger-4">
            <div class="stats-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number">{{ $pendingJobs ?? 0 }}</div>
            <div class="stats-label">งานที่รอคิว</div>
            <div class="stats-change negative">
                <i class="fas fa-arrow-down"></i>
                -3% จากเดือนที่แล้ว
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Recent Activity -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="content-card animate-fade-in-left">
            <div class="card-header">
                <h4>
                    <i class="fas fa-history"></i>
                    กิจกรรมล่าสุด
                </h4>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus text-success"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">ผู้ใช้ใหม่ลงทะเบียน</div>
                            <div class="activity-description">John Doe ได้ลงทะเบียนเป็นสมาชิกใหม่</div>
                            <div class="activity-time">2 นาทีที่แล้ว</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-cog text-primary"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">อัปเดตการตั้งค่า</div>
                            <div class="activity-description">ระบบได้รับการอัปเดตการตั้งค่าความปลอดภัย</div>
                            <div class="activity-time">15 นาทีที่แล้ว</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-chart-bar text-warning"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">สร้างรายงานใหม่</div>
                            <div class="activity-description">รายงานการใช้งานประจำเดือนถูกสร้างขึ้น</div>
                            <div class="activity-time">1 ชั่วโมงที่แล้ว</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-shield-alt text-info"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">การสำรองข้อมูล</div>
                            <div class="activity-description">ระบบสำรองข้อมูลอัตโนมัติเสร็จสิ้น</div>
                            <div class="activity-time">2 ชั่วโมงที่แล้ว</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="content-card animate-fade-in-right">
            <div class="card-header">
                <h4>
                    <i class="fas fa-bolt"></i>
                    การดำเนินการด่วน
                </h4>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.users.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">จัดการผู้ใช้</div>
                            <div class="quick-action-description">เพิ่ม แก้ไข หรือลบผู้ใช้</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.settings.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">ตั้งค่าระบบ</div>
                            <div class="quick-action-description">ปรับแต่งการตั้งค่าต่างๆ</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">สร้างรายงาน</div>
                            <div class="quick-action-description">สร้างรายงานและสถิติ</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="#" class="quick-action-btn" onclick="SwalHelper.info('ฟีเจอร์นี้กำลังพัฒนา')">
                        <div class="quick-action-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="quick-action-content">
                            <div class="quick-action-title">สำรองข้อมูล</div>
                            <div class="quick-action-description">ดาวน์โหลดข้อมูลสำรอง</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-12">
        <div class="content-card animate-fade-in-up">
            <div class="card-header">
                <h4>
                    <i class="fas fa-chart-area"></i>
                    กราฟสถิติการใช้งาน
                </h4>
            </div>
            <div class="card-body">
                <canvas id="usageChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Usage Chart
    const ctx = document.getElementById('usageChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
            datasets: [{
                label: 'ผู้ใช้ที่เข้าสู่ระบบ',
                data: [12, 19, 3, 5, 2, 3, 8],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'เซสชันใหม่',
                data: [2, 3, 20, 5, 1, 4, 2],
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: 'Prompt',
                            size: 14
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            family: 'Prompt'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Prompt'
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 6,
                    hoverRadius: 8
                }
            }
        }
    });
});
</script>
@endpush