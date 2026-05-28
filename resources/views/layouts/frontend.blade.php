<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'หน้าแรก') - {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</title>
    <meta name="description" content="@yield('description', 'ระบบจัดการข้อมูลที่ทันสมัย')">
    
    <!-- Tailwind CSS, Fonts, Font Awesome -->
    @vite(['resources/css/fonts.css', 'resources/css/frontend.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="frontend-body">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-xl font-bold text-primary hover:text-primary-dark transition-colors">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white shadow-sm">
                            <i class="fas fa-code"></i>
                        </div>
                        <span>{{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home mr-2"></i>
                        หน้าแรก
                    </a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle mr-2"></i>
                        เกี่ยวกับเรา
                    </a>
                    <a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}">
                        <i class="fas fa-cogs mr-2"></i>
                        บริการ
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="fas fa-envelope mr-2"></i>
                        ติดต่อ
                    </a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary">
                            <i class="fas fa-tachometer-alt"></i>
                            แดชบอร์ด
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-outline">
                                <i class="fas fa-sign-out-alt"></i>
                                ออกจากระบบ
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline">
                            <i class="fas fa-sign-in-alt"></i>
                            เข้าสู่ระบบ
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary">
                            <i class="fas fa-user-plus"></i>
                            สมัครสมาชิก
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-slate-600 hover:text-slate-800 focus:outline-none focus:text-slate-800">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-slate-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    หน้าแรก
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle mr-3"></i>
                    เกี่ยวกับเรา
                </a>
                <a href="{{ route('services') }}" class="mobile-nav-link {{ request()->routeIs('services') ? 'active' : '' }}">
                    <i class="fas fa-cogs mr-3"></i>
                    บริการ
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <i class="fas fa-envelope mr-3"></i>
                    ติดต่อ
                </a>
                
                <div class="border-t border-slate-200 pt-3 mt-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            แดชบอร์ด
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mobile-nav-link w-full text-left">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                ออกจากระบบ
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mobile-nav-link">
                            <i class="fas fa-sign-in-alt mr-3"></i>
                            เข้าสู่ระบบ
                        </a>
                        <a href="{{ route('register') }}" class="mobile-nav-link">
                            <i class="fas fa-user-plus mr-3"></i>
                            สมัครสมาชิก
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white shadow-sm">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</h3>
                    </div>
                    <p class="text-slate-300 mb-6 max-w-md">
                        ระบบจัดการข้อมูลที่ทันสมัย พร้อมฟีเจอร์ครบครันสำหรับการจัดการธุรกิจของคุณ
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-slate-300 hover:bg-primary hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-slate-300 hover:bg-primary hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-slate-300 hover:bg-primary hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-slate-300 hover:bg-primary hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">ลิงก์ด่วน</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-slate-300 hover:text-white transition-colors">หน้าแรก</a></li>
                        <li><a href="{{ route('about') }}" class="text-slate-300 hover:text-white transition-colors">เกี่ยวกับเรา</a></li>
                        <li><a href="{{ route('services') }}" class="text-slate-300 hover:text-white transition-colors">บริการ</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-300 hover:text-white transition-colors">ติดต่อ</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">การสนับสนุน</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors">ศูนย์ช่วยเหลือ</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors">เอกสาร</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors">API</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors">สถานะระบบ</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="border-t border-slate-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-400 text-sm">
                    © {{ date('Y') }} {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}. สงวนลิขสิทธิ์
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">นโยบายความเป็นส่วนตัว</a>
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">ข้อกำหนดการใช้งาน</a>
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">คุกกี้</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Frontend JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
            
            // Auto-show success/error messages
            @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
            
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#667eea'
                });
            @endif
            
            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'คำเตือน!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
            
            @if(session('info'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                Toast.fire({
                    icon: 'info',
                    title: '{{ session('info') }}'
                });
            @endif
        });
    </script>
    
    @stack('scripts')
</body>
</html>