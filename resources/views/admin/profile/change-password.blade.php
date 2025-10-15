@extends('layouts.admin')

@section('title', 'เปลี่ยนรหัสผ่าน')
@section('page-title', 'เปลี่ยนรหัสผ่าน')
@section('page-subtitle', 'อัปเดตรหัสผ่านของคุณเพื่อความปลอดภัย')

@push('styles')
@vite(['resources/css/profile.css'])
@endpush

@section('content')
<div class="change-password-container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card profile-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน
                        </h5>
                        <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update-password') }}" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-2"></i>รหัสผ่านปัจจุบัน <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       placeholder="กรุณากรอกรหัสผ่านปัจจุบัน"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key me-2"></i>รหัสผ่านใหม่ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password" 
                                       placeholder="กรุณากรอกรหัสผ่านใหม่"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye" id="new_password_icon"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            <!-- Password Strength Indicator -->
                            <div class="password-strength mt-2">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <div class="strength-text" id="strengthText">กรุณากรอกรหัสผ่าน</div>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">
                                <i class="fas fa-check-circle me-2"></i>ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       placeholder="กรุณายืนยันรหัสผ่านใหม่"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                </button>
                            </div>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            <!-- Password Match Indicator -->
                            <div class="password-match mt-2 d-none" id="passwordMatch">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                <span class="text-success">รหัสผ่านตรงกัน</span>
                            </div>
                            <div class="password-mismatch mt-2 d-none" id="passwordMismatch">
                                <i class="fas fa-times-circle text-danger me-1"></i>
                                <span class="text-danger">รหัสผ่านไม่ตรงกัน</span>
                            </div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="password-requirements mb-4">
                            <h6 class="mb-2">
                                <i class="fas fa-list me-2"></i>ข้อกำหนดรหัสผ่าน:
                            </h6>
                            <ul class="list-unstyled">
                                <li id="req-length">
                                    <i class="fas fa-circle text-muted me-2"></i>
                                    อย่างน้อย 8 ตัวอักษร
                                </li>
                                <li id="req-uppercase">
                                    <i class="fas fa-circle text-muted me-2"></i>
                                    มีตัวอักษรพิมพ์ใหญ่
                                </li>
                                <li id="req-lowercase">
                                    <i class="fas fa-circle text-muted me-2"></i>
                                    มีตัวอักษรพิมพ์เล็ก
                                </li>
                                <li id="req-number">
                                    <i class="fas fa-circle text-muted me-2"></i>
                                    มีตัวเลข
                                </li>
                                <li id="req-special">
                                    <i class="fas fa-circle text-muted me-2"></i>
                                    มีอักขระพิเศษ (!@#$%^&*)
                                </li>
                            </ul>
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
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-save me-1"></i>เปลี่ยนรหัสผ่าน
                                    </button>
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
    const form = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submitBtn');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('new_password_confirmation');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const passwordMatch = document.getElementById('passwordMatch');
    const passwordMismatch = document.getElementById('passwordMismatch');

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
    
    // Show security notice on page load
    Swal.fire({
        icon: 'warning',
        title: 'คำเตือนความปลอดภัย',
        text: 'การเปลี่ยนรหัสผ่านจะทำให้คุณต้องเข้าสู่ระบบใหม่ และจะถูกบันทึกในระบบ Audit Log',
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let strengthTextValue = '';
        let strengthColor = '';

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        switch (strength) {
            case 0:
            case 1:
                strengthTextValue = 'อ่อนมาก';
                strengthColor = '#dc3545';
                break;
            case 2:
                strengthTextValue = 'อ่อน';
                strengthColor = '#fd7e14';
                break;
            case 3:
                strengthTextValue = 'ปานกลาง';
                strengthColor = '#ffc107';
                break;
            case 4:
                strengthTextValue = 'แข็ง';
                strengthColor = '#20c997';
                break;
            case 5:
                strengthTextValue = 'แข็งมาก';
                strengthColor = '#198754';
                break;
        }

        strengthFill.style.width = (strength * 20) + '%';
        strengthFill.style.backgroundColor = strengthColor;
        strengthText.textContent = strengthTextValue;
        strengthText.style.color = strengthColor;
    }

    // Password match checker
    function checkPasswordMatch() {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword.length > 0) {
            if (newPassword === confirmPassword) {
                passwordMatch.classList.remove('d-none');
                passwordMismatch.classList.add('d-none');
                return true;
            } else {
                passwordMatch.classList.add('d-none');
                passwordMismatch.classList.remove('d-none');
                return false;
            }
        } else {
            passwordMatch.classList.add('d-none');
            passwordMismatch.classList.add('d-none');
            return false;
        }
    }

    // Form validation
    function validateForm() {
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const isPasswordMatch = checkPasswordMatch();

        if (currentPassword && newPassword && confirmPassword && isPasswordMatch) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Event listeners
    newPasswordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', function() {
        checkPasswordMatch();
        validateForm();
    });

    document.getElementById('current_password').addEventListener('input', validateForm);

    // Form submission
    form.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังเปลี่ยนรหัสผ่าน...';
        submitBtn.disabled = true;
    });

    // Toggle password visibility
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // Reset form
    window.resetForm = function() {
        form.reset();
        strengthFill.style.width = '0%';
        strengthText.textContent = 'กรุณากรอกรหัสผ่าน';
        strengthText.style.color = '';
        passwordMatch.classList.add('d-none');
        passwordMismatch.classList.add('d-none');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>เปลี่ยนรหัสผ่าน';
    };
});
</script>
@endpush