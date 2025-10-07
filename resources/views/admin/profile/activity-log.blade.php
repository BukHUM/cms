@extends('layouts.admin')

@section('title', 'ประวัติการใช้งาน')
@section('page-title', 'ประวัติการใช้งาน')
@section('page-subtitle', 'ดูประวัติการใช้งานและกิจกรรมของคุณ')

@push('styles')
@vite(['resources/css/profile.css'])
@endpush

@section('content')
<div class="activity-log-container">
    <div class="row">
        <div class="col-12">
            <div class="card profile-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>ประวัติการใช้งาน
                        </h5>
                        <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($activities->count() > 0)
                        <div class="activity-timeline">
                            @foreach($activities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker">
                                        @switch($activity->action)
                                            @case('login')
                                                <i class="fas fa-sign-in-alt text-success"></i>
                                                @break
                                            @case('logout')
                                                <i class="fas fa-sign-out-alt text-warning"></i>
                                                @break
                                            @case('profile_update')
                                                <i class="fas fa-user-edit text-primary"></i>
                                                @break
                                            @case('password_change')
                                                <i class="fas fa-key text-danger"></i>
                                                @break
                                            @case('avatar_update')
                                                <i class="fas fa-camera text-info"></i>
                                                @break
                                            @default
                                                <i class="fas fa-circle text-secondary"></i>
                                        @endswitch
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <h6 class="timeline-title">{{ $activity->description }}</h6>
                                            <span class="timeline-time">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="timeline-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-globe me-1"></i>
                                                        {{ $activity->ip_address }}
                                                    </small>
                                                </div>
                                            </div>
                                            @if($activity->user_agent)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-desktop me-1"></i>
                                                        {{ Str::limit($activity->user_agent, 80) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $activities->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">ยังไม่มีประวัติการใช้งาน</h5>
                            <p class="text-muted">กิจกรรมของคุณจะแสดงที่นี่</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Activity Summary -->
            <div class="card profile-card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>สรุปกิจกรรม
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $activitySummary = [
                            'login' => $activities->where('action', 'login')->count(),
                            'logout' => $activities->where('action', 'logout')->count(),
                            'profile_update' => $activities->where('action', 'profile_update')->count(),
                            'password_change' => $activities->where('action', 'password_change')->count(),
                            'avatar_update' => $activities->where('action', 'avatar_update')->count(),
                        ];
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-2 col-6 mb-3">
                            <div class="activity-summary-item text-center">
                                <div class="summary-icon bg-success">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="summary-number">{{ $activitySummary['login'] }}</div>
                                <div class="summary-label">เข้าสู่ระบบ</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="activity-summary-item text-center">
                                <div class="summary-icon bg-warning">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="summary-number">{{ $activitySummary['logout'] }}</div>
                                <div class="summary-label">ออกจากระบบ</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="activity-summary-item text-center">
                                <div class="summary-icon bg-primary">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="summary-number">{{ $activitySummary['profile_update'] }}</div>
                                <div class="summary-label">แก้ไขข้อมูล</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="activity-summary-item text-center">
                                <div class="summary-icon bg-danger">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="summary-number">{{ $activitySummary['password_change'] }}</div>
                                <div class="summary-label">เปลี่ยนรหัสผ่าน</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="activity-summary-item text-center">
                                <div class="summary-icon bg-info">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div class="summary-number">{{ $activitySummary['avatar_update'] }}</div>
                                <div class="summary-label">เปลี่ยนรูป</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="activity-summary-item text-center">
                                <div class="summary-icon bg-secondary">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="summary-number">{{ $activities->total() }}</div>
                                <div class="summary-label">ทั้งหมด</div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Activity log functionality
    console.log('Activity log page loaded');
});
</script>
@endpush