@extends('layouts.admin')

@section('title', 'แก้ไขข้อมูลส่วนตัว')
@section('page-title', 'แก้ไขข้อมูลส่วนตัว')
@section('page-subtitle', 'อัปเดตข้อมูลส่วนตัวของคุณ')

@push('styles')
@vite(['resources/css/profile.css'])
<style>
/* Mobile Responsive Improvements */
@media (max-width: 767.98px) {
    .profile-edit-container {
        padding: 0 10px;
    }
    
    .profile-card {
        margin-bottom: 1rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .card-header {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-control {
        padding: 0.75rem;
        font-size: 1rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
    }
    
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
    
    .btn {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }
    
    .btn-outline-warning {
        border-color: #f59e0b;
        color: #f59e0b;
    }
    
    .btn-outline-warning:hover {
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    
    .form-actions {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    
    .row.g-2 > * {
        padding: 0.25rem;
    }
    
    /* Improve spacing for mobile */
    .mb-3 {
        margin-bottom: 1rem !important;
    }
    
    /* Better touch targets */
    .btn {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Responsive text */
    .card-header h5 {
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    
    .page-title h1 {
        font-size: 1.5rem;
    }
    
    .page-subtitle {
        font-size: 0.9rem;
    }
}

/* Tablet adjustments */
@media (min-width: 768px) and (max-width: 991.98px) {
    .profile-edit-container {
        padding: 0 15px;
    }
    
    .btn {
        padding: 0.6rem 1rem;
    }
}

/* Ensure proper spacing on all devices */
.form-actions .row.g-2 {
    margin: 0 -0.25rem;
}

.form-actions .row.g-2 > * {
    padding: 0 0.25rem;
}
</style>
@endpush

@section('content')
<div class="profile-edit-container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card profile-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>แก้ไขข้อมูลส่วนตัว
                        </h5>
                        <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-2"></i>ชื่อ-นามสกุล <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           placeholder="กรุณากรอกชื่อ-นามสกุล"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>อีเมล <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           placeholder="กรุณากรอกอีเมล"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <!-- Phone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>เบอร์โทรศัพท์
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           placeholder="กรุณากรอกเบอร์โทรศัพท์">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Address -->
                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        <i class="fas fa-map-marker-alt me-2"></i>ที่อยู่
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="3" 
                                              placeholder="กรุณากรอกที่อยู่">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <!-- Bio -->
                                <div class="mb-3">
                                    <label for="bio" class="form-label">
                                        <i class="fas fa-info-circle me-2"></i>ข้อมูลส่วนตัว
                                    </label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                              id="bio" 
                                              name="bio" 
                                              rows="4" 
                                              placeholder="กรุณากรอกข้อมูลส่วนตัว">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <!-- Mobile Layout -->
                            <div class="d-block d-md-none">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                            <i class="fas fa-save me-1"></i>บันทึกข้อมูล
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-warning w-100" onclick="resetForm()">
                                            <i class="fas fa-undo me-1"></i>รีเซ็ต
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-times me-1"></i>ยกเลิก
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Desktop Layout -->
                            <div class="d-none d-md-block">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>ยกเลิก
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-warning me-2" onclick="resetForm()">
                                            <i class="fas fa-undo me-1"></i>รีเซ็ต
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-1"></i>บันทึกข้อมูล
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Show success message if redirected from update
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
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        // Show loading state for both mobile and desktop buttons
        const submitButtons = document.querySelectorAll('#submitBtn');
        submitButtons.forEach(btn => {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังบันทึก...';
            btn.disabled = true;
        });
        
        // Prevent double submission
        form.removeEventListener('submit', arguments.callee);
    });
    
    // Form validation
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });
    
    function validateField(field) {
        const value = field.value.trim();
        const isValid = value.length > 0;
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }
    }
});

function resetForm() {
    Swal.fire({
        title: 'ยืนยันการรีเซ็ต',
        text: 'คุณต้องการรีเซ็ตฟอร์มหรือไม่? ข้อมูลที่กรอกจะหายไป',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'รีเซ็ต',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('profileForm').reset();
            
            // Remove validation classes
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });
            
            Swal.fire({
                title: 'รีเซ็ตเรียบร้อย',
                text: 'ฟอร์มถูกรีเซ็ตแล้ว',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}
</script>
@endpush
