<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('site_name', 'CMS Backend'))</title>
    
    @if(setting('site_favicon'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-prompt transition-colors duration-200">
    <div class="min-h-screen flex">
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
        
        <!-- Desktop Sidebar -->
        <div id="desktop-sidebar" class="hidden lg:block w-64 bg-white dark:bg-gray-800 shadow-sm border-r border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-cogs mr-2"></i>
                    {{ setting('site_name', 'CMS Backend') }}
                </h2>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">เมนูหลัก</h3>
                </div>
                
                <a href="{{ route('backend.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.dashboard') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">Dashboard</span>
                </a>
                
                <!-- ผู้ใช้งานระบบ Menu -->
                <div class="px-6 py-2 mt-4">
                    <button id="user-menu-toggle" class="flex items-center justify-between w-full text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200 rounded-md px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span>ผู้ใช้งานระบบ</span>
                        <i id="user-menu-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                </div>
                
                <div id="user-menu-items" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
                    <a href="{{ route('backend.users.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.users.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>
                        <span class="truncate">ผู้ใช้งาน</span>
                    </a>
                    
                    <a href="{{ route('backend.roles.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.roles.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-user-tag mr-3 w-5 text-center"></i>
                        <span class="truncate">บทบาท</span>
                    </a>
                    
                    <a href="{{ route('backend.permissions.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.permissions.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-key mr-3 w-5 text-center"></i>
                        <span class="truncate">สิทธิ์การเข้าถึง</span>
                    </a>
                </div>
                
                <!-- ตั้งค่าระบบ Menu -->
                <div class="px-6 py-2 mt-4">
                    <button id="settings-menu-toggle" class="flex items-center justify-between w-full text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200 rounded-md px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span>ตั้งค่าระบบ</span>
                        <i id="settings-menu-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                </div>
                
                <div id="settings-menu-items" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
                    <a href="{{ route('backend.settings-general.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-general.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-cog mr-3 w-5 text-center"></i>
                        <span class="truncate">ทั่วไป</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-email.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-email.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-envelope mr-3 w-5 text-center"></i>
                        <span class="truncate">อีเมล์</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-security.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-security.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-shield-alt mr-3 w-5 text-center"></i>
                        <span class="truncate">ความปลอดภัย</span>
                    </a>
                    
                    <a href="{{ route('backend.settings.auditlog.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings.auditlog.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-history mr-3 w-5 text-center"></i>
                        <span class="truncate">Audit Log</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-performance.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-performance.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                        <span class="truncate">Performance</span>
                    </a>
                    
                    
                    <a href="{{ route('backend.settings-systeminfo.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-systeminfo.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-info-circle mr-3 w-5 text-center"></i>
                        <span class="truncate">ข้อมูลระบบ</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-backup.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-backup.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-database mr-3 w-5 text-center"></i>
                        <span class="truncate">สำรองข้อมูล</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-sm border-r border-gray-200 dark:border-gray-700 transform -translate-x-full transition-all duration-300 ease-in-out lg:hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-cogs mr-2"></i>
                        {{ setting('site_name', 'CMS Backend') }}
                    </h2>
                    <button id="close-mobile-sidebar" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">เมนูหลัก</h3>
                </div>
                
                <a href="{{ route('backend.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.dashboard') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                    <span class="truncate">Dashboard</span>
                </a>
                
                <!-- ผู้ใช้งานระบบ Menu -->
                <div class="px-6 py-2 mt-4">
                    <button id="user-menu-toggle-mobile" class="flex items-center justify-between w-full text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200 rounded-md px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span>ผู้ใช้งานระบบ</span>
                        <i id="user-menu-icon-mobile" class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                </div>
                
                <div id="user-menu-items-mobile" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
                    <a href="{{ route('backend.users.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.users.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>
                        <span class="truncate">ผู้ใช้งาน</span>
                    </a>
                    
                    <a href="{{ route('backend.roles.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.roles.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-user-tag mr-3 w-5 text-center"></i>
                        <span class="truncate">บทบาท</span>
                    </a>
                    
                    <a href="{{ route('backend.permissions.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.permissions.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-key mr-3 w-5 text-center"></i>
                        <span class="truncate">สิทธิ์การเข้าถึง</span>
                    </a>
                </div>
                
                <!-- ตั้งค่าระบบ Menu -->
                <div class="px-6 py-2 mt-4">
                    <button id="settings-menu-toggle-mobile" class="flex items-center justify-between w-full text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200 rounded-md px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span>ตั้งค่าระบบ</span>
                        <i id="settings-menu-icon-mobile" class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                </div>
                
                <div id="settings-menu-items-mobile" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
                    <a href="{{ route('backend.settings-general.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-general.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-cog mr-3 w-5 text-center"></i>
                        <span class="truncate">ทั่วไป</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-email.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-email.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-envelope mr-3 w-5 text-center"></i>
                        <span class="truncate">อีเมล์</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-security.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-security.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-shield-alt mr-3 w-5 text-center"></i>
                        <span class="truncate">ความปลอดภัย</span>
                    </a>
                    
                    <a href="{{ route('backend.settings.auditlog.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings.auditlog.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-history mr-3 w-5 text-center"></i>
                        <span class="truncate">Audit Log</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-performance.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-performance.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                        <span class="truncate">Performance</span>
                    </a>
                    
                    
                    <a href="{{ route('backend.settings-systeminfo.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-systeminfo.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-info-circle mr-3 w-5 text-center"></i>
                        <span class="truncate">ข้อมูลระบบ</span>
                    </a>
                    
                    <a href="{{ route('backend.settings-backup.index') }}" class="flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('backend.settings-backup.*') ? 'bg-blue-50 dark:bg-blue-900 border-r-4 border-blue-500' : '' }}">
                        <i class="fas fa-database mr-3 w-5 text-center"></i>
                        <span class="truncate">สำรองข้อมูล</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-button" class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">@yield('page-description', 'ภาพรวมระบบ')</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <div class="relative">
                            <button id="dark-mode-toggle" class="flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i id="dark-mode-icon" class="fas fa-moon text-lg"></i>
                            </button>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <!-- User Profile -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden lg:block">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</p>
                            </div>
                            <div class="relative group">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                
                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 mt-2 w-48 bg-gray-50 dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border dark:border-gray-700">
                                    <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b dark:border-gray-700">
                                        <p class="font-medium">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                        <p class="text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                                    </div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-user mr-2"></i>โปรไฟล์
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-cog mr-2"></i>ตั้งค่า
                                    </a>
                                    <div class="border-t dark:border-gray-700"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-sign-out-alt mr-2"></i>ออกจากระบบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 bg-gray-50 dark:bg-gray-900 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-full shadow-lg transition-all duration-300 ease-in-out opacity-0 invisible z-50 transform translate-y-24 hover:translate-y-0 hover:shadow-xl dark:hover:shadow-2xl sm:bottom-4 sm:right-4 sm:p-2">
        <i class="fas fa-chevron-up text-lg"></i>
    </button>

    <!-- Mobile Menu & Dark Mode JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const elements = {
                mobileMenuButton: document.getElementById('mobile-menu-button'),
                closeSidebarButton: document.getElementById('close-mobile-sidebar'),
                mobileSidebar: document.getElementById('mobile-sidebar'),
                mobileOverlay: document.getElementById('mobile-overlay'),
                darkModeToggle: document.getElementById('dark-mode-toggle'),
                darkModeIcon: document.getElementById('dark-mode-icon'),
                backToTopButton: document.getElementById('back-to-top')
            };

            // Mobile Menu functionality
            function openSidebar() {
                elements.mobileSidebar?.classList.remove('-translate-x-full');
                elements.mobileOverlay?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                elements.mobileSidebar?.classList.add('-translate-x-full');
                elements.mobileOverlay?.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Event listeners for mobile menu
            elements.mobileMenuButton?.addEventListener('click', openSidebar);
            elements.closeSidebarButton?.addEventListener('click', closeSidebar);
            elements.mobileOverlay?.addEventListener('click', closeSidebar);

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSidebar();
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) closeSidebar();
            });

            // Dark Mode functionality
            const htmlElement = document.documentElement;
            const savedTheme = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDarkMode = savedTheme === 'dark' || (savedTheme === null && prefersDark);

            // Apply initial theme
            if (isDarkMode) {
                htmlElement.classList.add('dark');
                elements.darkModeIcon.className = 'fas fa-sun text-lg';
            } else {
                htmlElement.classList.remove('dark');
                elements.darkModeIcon.className = 'fas fa-moon text-lg';
            }

            // Toggle dark mode
            elements.darkModeToggle?.addEventListener('click', function() {
                const isCurrentlyDark = htmlElement.classList.contains('dark');
                
                if (isCurrentlyDark) {
                    htmlElement.classList.remove('dark');
                    elements.darkModeIcon.className = 'fas fa-moon text-lg';
                    localStorage.setItem('darkMode', 'light');
                } else {
                    htmlElement.classList.add('dark');
                    elements.darkModeIcon.className = 'fas fa-sun text-lg';
                    localStorage.setItem('darkMode', 'dark');
                }
            });

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (localStorage.getItem('darkMode') === null) {
                    if (e.matches) {
                        htmlElement.classList.add('dark');
                        elements.darkModeIcon.className = 'fas fa-sun text-lg';
                    } else {
                        htmlElement.classList.remove('dark');
                        elements.darkModeIcon.className = 'fas fa-moon text-lg';
                    }
                }
            });

            // Collapsible Menu functionality
            function initializeCollapsibleMenu(menuToggleId, menuItemsId, menuIconId, storageKey) {
                const menuToggle = document.getElementById(menuToggleId);
                const menuItems = document.getElementById(menuItemsId);
                const menuIcon = document.getElementById(menuIconId);
                
                if (!menuToggle || !menuItems || !menuIcon) return;
                
                const savedState = localStorage.getItem(storageKey);
                const isOpen = savedState === 'open';
                
                // Set initial state
                if (isOpen) {
                    menuItems.style.maxHeight = menuItems.scrollHeight + 'px';
                    menuIcon.classList.add('rotate-180');
                } else {
                    menuItems.style.maxHeight = '0px';
                    menuIcon.classList.remove('rotate-180');
                }
                
                // Toggle functionality
                menuToggle.addEventListener('click', function() {
                    const isCurrentlyOpen = menuItems.style.maxHeight !== '0px';
                    
                    if (isCurrentlyOpen) {
                        menuItems.style.maxHeight = '0px';
                        menuIcon.classList.remove('rotate-180');
                        localStorage.setItem(storageKey, 'closed');
                    } else {
                        menuItems.style.maxHeight = menuItems.scrollHeight + 'px';
                        menuIcon.classList.add('rotate-180');
                        localStorage.setItem(storageKey, 'open');
                    }
                });
            }
            
            // Initialize collapsible menus
            initializeCollapsibleMenu('user-menu-toggle', 'user-menu-items', 'user-menu-icon', 'sidebar_user_menu_state');
            initializeCollapsibleMenu('settings-menu-toggle', 'settings-menu-items', 'settings-menu-icon', 'sidebar_settings_menu_state');
            initializeCollapsibleMenu('user-menu-toggle-mobile', 'user-menu-items-mobile', 'user-menu-icon-mobile', 'sidebar_user_menu_state_mobile');
            initializeCollapsibleMenu('settings-menu-toggle-mobile', 'settings-menu-items-mobile', 'settings-menu-icon-mobile', 'sidebar_settings_menu_state_mobile');

            // Back to Top Button functionality
            if (elements.backToTopButton) {
                function toggleBackToTopButton() {
                    if (window.pageYOffset > 300) {
                        elements.backToTopButton.classList.add('opacity-100', 'visible', 'translate-y-0');
                        elements.backToTopButton.classList.remove('opacity-0', 'invisible', 'translate-y-24');
                    } else {
                        elements.backToTopButton.classList.add('opacity-0', 'invisible', 'translate-y-24');
                        elements.backToTopButton.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    }
                }
                
                function scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
                
                window.addEventListener('scroll', toggleBackToTopButton);
                elements.backToTopButton.addEventListener('click', scrollToTop);
                toggleBackToTopButton(); // Initial check
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
