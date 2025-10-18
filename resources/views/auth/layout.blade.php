<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <title>@yield('title', 'CMS Backend')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth.js'])
    
    <!-- Critical CSS for auth pages -->
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .auth-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .auth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .auth-focus:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        #togglePassword {
            pointer-events: auto !important;
            user-select: none;
        }
        #togglePassword:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen flex items-center justify-center font-prompt auth-bg">
    <div class="w-full max-w-md mx-4">
        <!-- Auth Card -->
        <div class="rounded-2xl shadow-2xl p-8 border border-white/20 auth-card auth-transition">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 @yield('logo-color', 'bg-blue-600') rounded-full flex items-center justify-center mx-auto mb-4 auth-transition">
                    <i class="@yield('logo-icon', 'fas fa-cogs') text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">@yield('page-title', 'CMS Backend')</h1>
                <p class="text-gray-600">@yield('page-description', 'เข้าสู่ระบบเพื่อจัดการระบบ')</p>
            </div>

            <!-- Main Content -->
            @yield('content')

            <!-- Messages -->
            @include('auth.partials.messages')

            <!-- Footer Links -->
            @yield('footer-links')
        </div>

        <!-- Security Footer -->
        <div class="text-center mt-8 text-white/80">
            <p class="text-sm">
                <i class="fas fa-shield-alt mr-1"></i>
                ระบบได้รับการปกป้องด้วยมาตรฐานความปลอดภัยสูงสุด
            </p>
        </div>
    </div>

    <!-- Auth JavaScript -->
    <script>
        // Global function for password toggle - Available immediately
        window.togglePassword = function() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput && passwordIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                }
            }
        };

        // Password toggle event listener
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'togglePassword') {
                e.preventDefault();
                e.stopPropagation();
                window.togglePassword();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const elements = {
                form: document.querySelector('form'),
                emailInput: document.getElementById('email'),
                passwordInput: document.getElementById('password'),
                togglePassword: document.getElementById('togglePassword'),
                passwordIcon: document.getElementById('passwordIcon'),
                alerts: document.querySelectorAll('.bg-red-50, .bg-green-50, .bg-blue-50')
            };

            

            // Auto-hide alerts
            elements.alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });

            // Form validation
            if (elements.form) {
                elements.form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Email validation
                    if (elements.emailInput && !elements.emailInput.value.trim()) {
                        isValid = false;
                        elements.emailInput.classList.add('border-red-500');
                    } else if (elements.emailInput) {
                        elements.emailInput.classList.remove('border-red-500');
                    }

                    // Password validation (if exists)
                    if (elements.passwordInput && !elements.passwordInput.value.trim()) {
                        isValid = false;
                        elements.passwordInput.classList.add('border-red-500');
                    } else if (elements.passwordInput) {
                        elements.passwordInput.classList.remove('border-red-500');
                    }

                    if (!isValid) {
                        e.preventDefault();
                        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    }
                });
            }

            // Rate limiting indicator
            const rateLimitIndicator = document.getElementById('rate-limit-indicator');
            if (rateLimitIndicator) {
                const remaining = parseInt(rateLimitIndicator.dataset.remaining);
                const resetTime = parseInt(rateLimitIndicator.dataset.reset);
                
                if (remaining <= 2) {
                    rateLimitIndicator.classList.remove('hidden');
                    rateLimitIndicator.textContent = `เหลือ ${remaining} ครั้ง (รีเซ็ตใน ${Math.ceil((resetTime - Date.now()) / 1000)} วินาที)`;
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
