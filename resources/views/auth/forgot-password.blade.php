<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน - CMS Backend</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center font-prompt" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="w-full max-w-md mx-4">
        <!-- Forgot Password Card -->
        <div class="rounded-2xl shadow-2xl p-8 border border-white/20" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">ลืมรหัสผ่าน</h1>
                <p class="text-gray-600">กรุณาใส่อีเมล์ของคุณเพื่อรีเซ็ตรหัสผ่าน</p>
            </div>

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>อีเมล์
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent focus:outline-none transition-all duration-200 @error('email') border-red-500 @enderror"
                        placeholder="กรุณาใส่อีเมล์ของคุณ"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    ส่งลิงก์รีเซ็ตรหัสผ่าน
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">เกิดข้อผิดพลาด</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Messages -->
            @if (session('info'))
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white/80">
            <p class="text-sm">
                <i class="fas fa-shield-alt mr-1"></i>
                ระบบได้รับการปกป้องด้วยมาตรฐานความปลอดภัยสูงสุด
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.bg-red-50, .bg-green-50, .bg-blue-50');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });

            // Form validation
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');

            if (form) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Email validation
                    if (!emailInput.value.trim()) {
                        isValid = false;
                        emailInput.classList.add('border-red-500');
                    } else {
                        emailInput.classList.remove('border-red-500');
                    }

                    if (!isValid) {
                        e.preventDefault();
                        alert('กรุณากรอกอีเมล์');
                    }
                });
            }
        });
    </script>
</body>
</html>
