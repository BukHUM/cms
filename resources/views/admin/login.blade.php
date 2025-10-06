@extends('layouts.login')

@section('title', 'เข้าสู่ระบบ Admin')
@section('description', 'หน้าเข้าสู่ระบบสำหรับ Admin Panel')

@section('content')
<!-- Full Screen Login -->
<div class="login-container">
    <div class="login-background"></div>
    <div class="login-content">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="admin-icon mb-3">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h2 class="h3 mb-2">เข้าสู่ระบบ Admin</h2>
                        <p class="text-muted">กรุณาเข้าสู่ระบบเพื่อเข้าถึง Admin Panel</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>อีเมล
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="กรุณากรอกอีเมลของคุณ"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>รหัสผ่าน
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="กรุณากรอกรหัสผ่านของคุณ"
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        id="togglePassword">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="remember" 
                                   name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                จดจำการเข้าสู่ระบบ
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                เข้าสู่ระบบ
                            </button>
                        </div>

                    </form>

                    <!-- Additional Links -->
                    <div class="text-center">
                        <hr class="my-4">
                        <p class="mb-2">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="fas fa-home me-2"></i>กลับหน้าแรก
                            </a>
                        </p>
                        <p class="mb-0">
                            <a href="{{ route('contact') }}" class="text-decoration-none">
                                <i class="fas fa-question-circle me-2"></i>ต้องการความช่วยเหลือ?
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="text-center mt-4">
                <div class="card bg-light border-0">
                    <div class="card-body py-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-2"></i>
                            ระบบนี้ได้รับการปกป้องด้วยเทคโนโลยีความปลอดภัยระดับสูง
                        </small>
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
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', function() {
        if (password.type === 'password') {
            password.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });


    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            e.preventDefault();
            SwalHelper.error('กรุณากรอกข้อมูลให้ครบถ้วน');
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังเข้าสู่ระบบ...';
        submitBtn.disabled = true;
    });
});
</script>
@endpush

