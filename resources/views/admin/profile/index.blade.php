@extends('layouts.admin')

@section('title', 'ข้อมูลส่วนตัว')
@section('page-title', 'ข้อมูลส่วนตัว')
@section('page-subtitle', 'จัดการข้อมูลส่วนตัวและบัญชีของคุณ')

@push('styles')
@vite(['resources/css/profile.css'])
@endpush

@section('content')
<div class="profile-container">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card profile-card">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="profile-avatar mb-3">
                        <img src="{{ $user->getAvatarUrl() }}" 
                             alt="{{ $user->name }}" 
                             class="avatar-image"
                             id="avatarImage">
                        <div class="avatar-overlay" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <h4 class="profile-name">{{ $user->name }}</h4>
                    <p class="profile-email">{{ $user->email }}</p>
                    <p class="profile-role">
                        <span class="badge bg-primary">{{ $user->getRoleDisplayName() }}</span>
                    </p>
                    
                    <!-- Status -->
                    <div class="profile-status mb-3">
                        <span class="badge {{ $user->getStatusBadgeClass() }}">
                            {{ $user->getStatusDisplayName() }}
                        </span>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="profile-actions">
                        <button class="btn btn-primary btn-sm" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-camera me-1"></i>เปลี่ยนรูป
                        </button>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>แก้ไขข้อมูล
                        </a>
                        <a href="{{ route('admin.profile.change-password') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-key me-1"></i>เปลี่ยนรหัสผ่าน
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card profile-card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>สถิติการใช้งาน
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number">{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="stat-label">สมาชิกเมื่อ</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-number">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y') : 'ไม่เคย' }}</div>
                                <div class="stat-label">เข้าสู่ระบบล่าสุด</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="card profile-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว
                        </h5>
                        <div class="header-actions">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>แก้ไข
                            </a>
                            <a href="{{ route('admin.profile.change-password') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-key me-1"></i>เปลี่ยนรหัสผ่าน
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="info-label">
                                    <i class="fas fa-user me-2"></i>ชื่อ-นามสกุล
                                </label>
                                <div class="info-value">{{ $user->name ?: 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="info-label">
                                    <i class="fas fa-envelope me-2"></i>อีเมล
                                </label>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="info-label">
                                    <i class="fas fa-phone me-2"></i>เบอร์โทรศัพท์
                                </label>
                                <div class="info-value">{{ $user->phone ?: 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="info-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>ที่อยู่
                                </label>
                                <div class="info-value">{{ $user->address ?: 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="info-label">
                                    <i class="fas fa-info-circle me-2"></i>ข้อมูลส่วนตัว
                                </label>
                                <div class="info-value">{{ $user->bio ?: 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="info-label">
                                    <i class="fas fa-calendar me-2"></i>อัปเดตล่าสุด
                                </label>
                                <div class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Log -->
            <div class="card profile-card mt-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-history me-2"></i>ประวัติการใช้งานล่าสุด
                        </h6>
                        <a href="{{ route('admin.profile.activity-log') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-list me-1"></i>ดูทั้งหมด
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $recentActivities = \App\Models\AuditLog::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentActivities->count() > 0)
                        <div class="activity-list">
                            @foreach($recentActivities as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon">
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
                                            @default
                                                <i class="fas fa-circle text-secondary"></i>
                                        @endswitch
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-description">{{ $activity->description }}</div>
                                        <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p>ยังไม่มีประวัติการใช้งาน</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file input for avatar upload -->
<input type="file" id="avatarInput" accept="image/*" class="d-none">

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success/error messages if redirected
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: '{{ session('error') }}',
            confirmButtonText: 'ตกลง',
            toast: true,
            position: 'top-end'
        });
    @endif
    
    // Avatar upload handling
    const avatarInput = document.getElementById('avatarInput');
    const avatarImage = document.getElementById('avatarImage');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ขนาดไฟล์ต้องไม่เกิน 2MB',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไฟล์ต้องเป็นรูปภาพ (JPEG, PNG, JPG, GIF)',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'กำลังอัปโหลด...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Upload file
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('{{ route("admin.profile.update-avatar") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    avatarImage.src = data.avatar_url;
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: data.message,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message,
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์',
                    confirmButtonText: 'ตกลง'
                });
                console.error('Error:', error);
            });
        }
    });
});
</script>
@endpush
