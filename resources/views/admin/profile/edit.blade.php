@extends('layouts.admin')

@section('title', 'แก้ไขข้อมูลส่วนตัว')
@section('page-title', 'แก้ไขข้อมูลส่วนตัว')
@section('page-subtitle', 'อัปเดตข้อมูลส่วนตัวของคุณ')

@push('styles')
@vite(['resources/css/profile.css'])
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
                    </form>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="card profile-card mt-3">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>หมายเหตุ:</strong> การเปลี่ยนแปลงข้อมูลส่วนตัวจะถูกบันทึกในระบบ Audit Log เพื่อความปลอดภัย
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
    const form = document.getElementById('profileForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังบันทึก...';
        submitBtn.disabled = true;
        
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
