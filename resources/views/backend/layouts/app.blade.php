<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CMS Backend')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-prompt">
    <div class="min-h-screen flex">
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
        
        <!-- Desktop Sidebar -->
        <div id="desktop-sidebar" class="hidden lg:block w-64 bg-white shadow-lg">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-cogs mr-2"></i>
                    CMS Backend
                </h2>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">เมนูหลัก</h3>
                </div>
                
                <a href="{{ route('backend.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.dashboard') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">Dashboard</span>
                </a>
                
                <div class="px-6 py-2 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ผู้ใช้งานระบบ</h3>
                </div>
                
                <a href="{{ route('backend.users.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.users.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>
                    <span class="truncate">ผู้ใช้งาน</span>
                </a>
                
                <a href="{{ route('backend.roles.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.roles.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-user-tag mr-3 w-5 text-center"></i>
                    <span class="truncate">บทบาท</span>
                </a>
                
                <a href="{{ route('backend.permissions.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.permissions.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-key mr-3 w-5 text-center"></i>
                    <span class="truncate">สิทธิ์การเข้าถึง</span>
                </a>
                
                <div class="px-6 py-2 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ตั้งค่าระบบ</h3>
                </div>
                
                <a href="{{ route('backend.settings.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-cog mr-3 w-5 text-center"></i>
                    <span class="truncate">ทั่วไป</span>
                </a>
                
                <a href="{{ route('backend.settings-email.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-email.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-envelope mr-3 w-5 text-center"></i>
                    <span class="truncate">อีเมล์</span>
                </a>
                
                <a href="{{ route('backend.settings-security.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-security.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-shield-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">ความปลอดภัย</span>
                </a>
                
                <a href="{{ route('backend.settings.auditlog.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings.auditlog.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-history mr-3 w-5 text-center"></i>
                    <span class="truncate">Audit Log</span>
                </a>
                
                <a href="{{ route('backend.settings-performance.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-performance.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                    <span class="truncate">Performance</span>
                </a>
                
                <a href="{{ route('backend.settings-update.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-update.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-sync-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">อัพเดตระบบ</span>
                </a>
                
                <a href="{{ route('backend.settings-systeminfo.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-systeminfo.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-info-circle mr-3 w-5 text-center"></i>
                    <span class="truncate">ข้อมูลระบบ</span>
                </a>
            </nav>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-cogs mr-2"></i>
                        CMS Backend
                    </h2>
                    <button id="close-mobile-sidebar" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">เมนูหลัก</h3>
                </div>
                
                <a href="{{ route('backend.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.dashboard') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">Dashboard</span>
                </a>
                
                <div class="px-6 py-2 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ผู้ใช้งานระบบ</h3>
                </div>
                
                <a href="{{ route('backend.users.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.users.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-users mr-3 w-5 text-center"></i>
                    <span class="truncate">ผู้ใช้งาน</span>
                </a>
                
                <a href="{{ route('backend.roles.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.roles.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-user-tag mr-3 w-5 text-center"></i>
                    <span class="truncate">บทบาท</span>
                </a>
                
                <a href="{{ route('backend.permissions.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.permissions.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-key mr-3 w-5 text-center"></i>
                    <span class="truncate">สิทธิ์การเข้าถึง</span>
                </a>
                
                <div class="px-6 py-2 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ตั้งค่าระบบ</h3>
                </div>
                
                <a href="{{ route('backend.settings.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-cog mr-3 w-5 text-center"></i>
                    <span class="truncate">ทั่วไป</span>
                </a>
                
                <a href="{{ route('backend.settings-email.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-email.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-envelope mr-3 w-5 text-center"></i>
                    <span class="truncate">อีเมล์</span>
                </a>
                
                <a href="{{ route('backend.settings-security.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-security.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-shield-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">ความปลอดภัย</span>
                </a>
                
                <a href="{{ route('backend.settings.auditlog.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings.auditlog.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-history mr-3 w-5 text-center"></i>
                    <span class="truncate">Audit Log</span>
                </a>
                
                <a href="{{ route('backend.settings-performance.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-performance.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                    <span class="truncate">Performance</span>
                </a>
                
                <a href="{{ route('backend.settings-update.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-update.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-sync-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">อัพเดตระบบ</span>
                </a>
                
                <a href="{{ route('backend.settings-systeminfo.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 {{ request()->routeIs('backend.settings-systeminfo.*') ? 'bg-blue-50 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-info-circle mr-3 w-5 text-center"></i>
                    <span class="truncate">ข้อมูลระบบ</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-4 sm:px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button id="mobile-menu-button" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                                <p class="text-sm text-gray-600 mt-1 hidden sm:block">@yield('page-description', 'ภาพรวมระบบ')</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                <button class="flex items-center text-sm text-gray-600 hover:text-gray-900 p-2">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-1 hidden sm:inline">3</span>
                                </button>
                            </div>
                            
                            <!-- User Profile -->
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</p>
                                </div>
                                <div class="relative group">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-500 rounded-full flex items-center justify-center cursor-pointer">
                                        <i class="fas fa-user text-white text-sm sm:text-base"></i>
                                    </div>
                                    
                                    <!-- Dropdown Menu -->
                                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                            <p class="font-medium">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                            <p class="text-gray-500">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                                        </div>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user mr-2"></i>โปรไฟล์
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog mr-2"></i>ตั้งค่า
                                        </a>
                                        <div class="border-t"></div>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>ออกจากระบบ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <!-- Mobile Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeSidebarButton = document.getElementById('close-mobile-sidebar');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');

            function openSidebar() {
                if (mobileSidebar) {
                    mobileSidebar.classList.remove('-translate-x-full');
                }
                if (mobileOverlay) {
                    mobileOverlay.classList.remove('hidden');
                }
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (mobileSidebar) {
                    mobileSidebar.classList.add('-translate-x-full');
                }
                if (mobileOverlay) {
                    mobileOverlay.classList.add('hidden');
                }
                document.body.style.overflow = '';
            }

            // Open sidebar
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openSidebar);
            }

            // Close sidebar
            if (closeSidebarButton) {
                closeSidebarButton.addEventListener('click', closeSidebar);
            }
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
    
    <!-- SweetAlert2 is already included via Vite -->
    
    @stack('scripts')
</body>
</html>
