@extends('layouts.login')

@section('title', 'เข้าสู่ระบบ Admin')
@section('description', 'หน้าเข้าสู่ระบบสำหรับ Admin Panel')

@section('content')
<!-- Full Screen Login with Tailwind -->
<div class="login-container">
    <div class="login-background"></div>
    <div class="login-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center min-h-screen">
                <div class="w-full max-w-md">
                    <!-- Login Card -->
                    <div class="backdrop-glass rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
                        <div class="p-8 sm:p-10">
                            <!-- Header -->
                            <div class="text-center mb-8">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-primary rounded-full shadow-lg mb-4">
                                    <i class="fas fa-shield-alt text-white text-3xl"></i>
                                </div>
                                <h2 class="text-2xl font-semibold text-slate-800 mb-2">เข้าสู่ระบบ Admin</h2>
                                <p class="text-slate-600">กรุณาเข้าสู่ระบบเพื่อเข้าถึง Admin Panel</p>
                            </div>

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                                @csrf
                                
                                <!-- Email Field -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                        <i class="fas fa-envelope mr-2 text-primary"></i>อีเมล
                                    </label>
                                    <input type="email" 
                                           class="login-input w-full px-4 py-3 rounded-lg bg-white @error('email') login-input--error @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="กรุณากรอกอีเมลของคุณ"
                                           required 
                                           autofocus>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                        <i class="fas fa-lock mr-2 text-primary"></i>รหัสผ่าน
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               class="login-input w-full px-4 py-3 pr-12 rounded-lg bg-white @error('password') login-input--error @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="กรุณากรอกรหัสผ่านของคุณ"
                                               required>
                                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" 
                                                type="button" 
                                                id="togglePassword">
                                            <i class="fas fa-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary focus:ring-2" 
                                           id="remember" 
                                           name="remember" 
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="ml-2 text-sm text-slate-700" for="remember">
                                        จดจำการเข้าสู่ระบบ
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        class="w-full bg-gradient-primary text-white font-medium py-3 px-4 rounded-lg hover:shadow-lg hover:-translate-y-0.5 transition-smooth" 
                                        id="loginBtn">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    เข้าสู่ระบบ
                                </button>

                                <!-- Security Notice -->
                                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                                        <p class="text-sm text-blue-700">
                                            ระบบจะล็อคบัญชีชั่วคราวหากเข้าสู่ระบบผิดเกิน 5 ครั้ง
                                        </p>
                                    </div>
                                </div>
                            </form>

                            <!-- Additional Links -->
                            <div class="text-center mt-8">
                                <div class="h-px bg-slate-200 mb-6"></div>
                                <div class="space-y-2">
                                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-primary transition-colors">
                                        <i class="fas fa-home mr-2"></i>กลับหน้าแรก
                                    </a>
                                    <span class="mx-2 text-slate-300">|</span>
                                    <a href="{{ route('contact') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-primary transition-colors">
                                        <i class="fas fa-question-circle mr-2"></i>ต้องการความช่วยเหลือ?
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="text-center mt-6">
                        <div class="backdrop-glass rounded-lg border border-white/20 p-4">
                            <p class="text-sm text-slate-600">
                                <i class="fas fa-shield-alt mr-2 text-primary"></i>
                                ระบบนี้ได้รับการปกป้องด้วยเทคโนโลยีความปลอดภัยระดับสูง
                            </p>
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
    const loginBtn = document.getElementById('loginBtn');
    
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
        
        // Show loading state
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังเข้าสู่ระบบ...';
        loginBtn.disabled = true;
        loginBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Prevent double submission
        form.removeEventListener('submit', arguments.callee);
    });

    // Check for rate limiting errors
    @if(session('error'))
        @if(str_contains(session('error'), 'ล็อค') || str_contains(session('error'), 'บล็อก'))
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#667eea'
            });
        @endif
    @endif
});
</script>
@endpush
