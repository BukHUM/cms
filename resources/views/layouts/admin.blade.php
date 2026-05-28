<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</title>
    
    @stack('head')
    
    <!-- Tailwind CSS, Fonts, Font Awesome -->
    @vite(['resources/css/fonts.css', 'resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <!-- Modal Styles -->
    <style>
    #editModal, #passwordModal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 9999 !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
        display: none;
    }

    #editModal.show, #passwordModal.show {
        display: flex !important;
    }
    </style>
</head>
<body class="admin-body" data-page="@yield('page', 'dashboard')">
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-brand">
            <div class="sidebar-brand__inner">
                <div class="sidebar-brand__logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="sidebar-brand__text">
                    <h1 class="sidebar-brand__title">Admin Panel</h1>
                    <p class="sidebar-brand__subtitle">Control Center</p>
                </div>
            </div>
            <button class="sidebar-close lg:hidden" id="sidebarClose" aria-label="ปิดเมนู">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav flex-1 overflow-y-auto">
            <p class="nav-section-label">เมนูหลัก</p>
            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="nav-icon-wrap">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">Dashboard</span>
                            <span class="nav-subtitle">ภาพรวมระบบ</span>
                        </div>
                    </a>
                </li>
                
                <!-- User Management -->
                <li>
                    <a href="{{ route('admin.user-management') }}" class="admin-nav-link {{ request()->routeIs('admin.user-management*') ? 'active' : '' }}">
                        <div class="nav-icon-wrap">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">จัดการผู้ใช้</span>
                            <span class="nav-subtitle">Users & Roles</span>
                        </div>
                    </a>
                </li>
                
                <!-- Settings -->
                <li class="has-submenu">
                    <button type="button" class="admin-nav-link w-full {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" onclick="toggleSubmenu('settings')">
                        <div class="nav-icon-wrap">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">ตั้งค่าระบบ</span>
                            <span class="nav-subtitle">System Settings</span>
                        </div>
                        <div class="nav-arrow">
                            <i class="fas fa-chevron-down transition-transform duration-200" id="settings-arrow"></i>
                        </div>
                    </button>
                    
                    <!-- Settings Submenu -->
                    <ul class="submenu hidden" id="settings-submenu">
                        <li>
                            <a href="{{ route('admin.settings.index') }}#general" class="submenu-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                                <i class="fas fa-cog mr-2"></i>
                                <span>การตั้งค่าทั่วไป</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}#email" class="submenu-link">
                                <i class="fas fa-envelope mr-2"></i>
                                <span>การตั้งค่าอีเมล</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}#security" class="submenu-link">
                                <i class="fas fa-shield-alt mr-2"></i>
                                <span>การตั้งค่าความปลอดภัย</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}#backup" class="submenu-link">
                                <i class="fas fa-database mr-2"></i>
                                <span>การตั้งค่าสำรองข้อมูล</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}#performance" class="submenu-link">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                <span>การตั้งค่าประสิทธิภาพ</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}#system-info" class="submenu-link">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>ข้อมูลระบบ</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Reports -->
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <div class="nav-icon-wrap">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">รายงาน</span>
                            <span class="nav-subtitle">Reports & Analytics</span>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Admin Header -->
        <header class="admin-header">
            <div class="flex items-center justify-between w-full px-6">
                <!-- Header Left -->
                <div class="flex items-center gap-6">
                    <!-- Sidebar Toggle -->
                    <button class="sidebar-toggle lg:hidden w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-slate-700 transition-colors" id="sidebarToggle">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <div class="page-title">
                        <h1 class="text-xl font-semibold text-slate-800 leading-tight">@yield('title', 'Dashboard')</h1>
                        <p class="text-sm text-slate-600 leading-tight hidden lg:block">@yield('subtitle', 'ภาพรวมระบบ')</p>
                    </div>
                </div>
                
                <!-- Header Center - Search Box -->
                <div class="flex-1 flex justify-center max-w-md mx-8">
                    <div class="search-box hidden lg:block relative w-full">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm z-10"></i>
                        <input type="text" placeholder="ค้นหา..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-slate-700 text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                    </div>
                </div>
                
                <!-- Header Right -->
                <div class="flex items-center gap-3">
                    <!-- Notification Button -->
                    <button class="notification-btn relative w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <i class="fas fa-bell text-sm"></i>
                        <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">3</span>
                    </button>
                    
                    <!-- Frontend Link -->
                    <a href="{{ route('home') }}" class="frontend-link flex items-center gap-2 px-4 py-2.5 bg-slate-600 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors">
                        <i class="fas fa-external-link-alt text-xs"></i>
                        <span class="link-text hidden sm:inline">หน้าแรก</span>
                    </a>
                    
                    <!-- User Menu -->
                    <div class="user-menu relative" id="userMenu">
                        <div class="flex items-center gap-3 px-4 py-2.5 bg-slate-100 rounded-lg cursor-pointer hover:bg-slate-200 transition-colors">
                            <div class="w-8 h-8 bg-slate-200 rounded-md flex items-center justify-center text-slate-700 text-sm">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details hidden lg:block">
                                <p class="text-sm font-medium text-slate-800 leading-tight">{{ auth()->user()?->name ?? 'Admin' }}</p>
                                <p class="text-xs text-slate-600 leading-tight">{{ auth()->user()?->roles?->first()?->name ?? 'Administrator' }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-500"></i>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu absolute top-full right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg opacity-0 invisible transform -translate-y-2 transition-all duration-300 z-50">
                            <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="fas fa-user text-sm w-4"></i>
                                <span class="text-sm">โปรไฟล์</span>
                            </a>
                            <button onclick="openEditModal()" class="w-full flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-slate-50 transition-colors text-left">
                                <i class="fas fa-edit text-sm w-4"></i>
                                <span class="text-sm">แก้ไขโปรไฟล์</span>
                            </button>
                            <button onclick="openPasswordModal()" class="w-full flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-slate-50 transition-colors text-left">
                                <i class="fas fa-key text-sm w-4"></i>
                                <span class="text-sm">เปลี่ยนรหัสผ่าน</span>
                            </button>
                            <div class="border-t border-slate-200 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt text-sm w-4"></i>
                                    <span class="text-sm">ออกจากระบบ</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>
    
    <!-- Logout Form -->
    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
        @csrf
    </form>
    
    <!-- Admin Panel JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const sidebar = document.getElementById('adminSidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const userMenu = document.getElementById('userMenu');
            
            // Sidebar Toggle
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                mainWrapper.classList.toggle('sidebar-active');
                mobileOverlay.classList.toggle('active');
                document.body.classList.toggle('overflow-hidden');
            }
            
            function closeSidebar() {
                sidebar.classList.remove('active');
                mainWrapper.classList.remove('sidebar-active');
                mobileOverlay.classList.remove('active');
                document.body.classList.remove('overflow-hidden');
            }
            
            // Event Listeners
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            mobileOverlay.addEventListener('click', closeSidebar);
            
            // User Menu Toggle
                userMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                const dropdown = userMenu.querySelector('.dropdown-menu');
                dropdown.classList.toggle('opacity-0');
                dropdown.classList.toggle('invisible');
                dropdown.classList.toggle('-translate-y-2');
                dropdown.classList.toggle('translate-y-0');
                    userMenu.classList.toggle('active');
                });
                
            // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                    const dropdown = userMenu.querySelector('.dropdown-menu');
                    dropdown.classList.add('opacity-0', 'invisible', '-translate-y-2');
                    dropdown.classList.remove('translate-y-0');
                        userMenu.classList.remove('active');
                    }
                });
                
            // Auto-close sidebar on desktop when clicking outside
            if (window.innerWidth >= 1024) {
                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        closeSidebar();
                    }
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                        closeSidebar();
                }
            });
            
            // SweetAlert2 Configuration
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
            
            // Auto-show success/error messages
            @if(session('success'))
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
                Toast.fire({
                    icon: 'info',
                    title: '{{ session('info') }}'
                });
            @endif
        });
    </script>
    
    <!-- Submenu Functions -->
    <script>
    function toggleSubmenu(menuId) {
        const submenu = document.getElementById(menuId + '-submenu');
        const arrow = document.getElementById(menuId + '-arrow');
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }
    
    // Auto-open submenu if current page is in submenu
    document.addEventListener('DOMContentLoaded', function() {
        const activeSubmenuLink = document.querySelector('.submenu-link.active');
        if (activeSubmenuLink) {
            const submenu = activeSubmenuLink.closest('.submenu');
            const arrow = submenu.previousElementSibling.querySelector('.nav-arrow i');
            if (submenu) {
                submenu.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
            }
        }
        
        // Handle hash navigation for settings
        const hash = window.location.hash;
        if (hash && hash.startsWith('#') && window.location.pathname.includes('/admin/settings')) {
            // Auto-open settings submenu
            const settingsSubmenu = document.getElementById('settings-submenu');
            const settingsArrow = document.getElementById('settings-arrow');
            if (settingsSubmenu) {
                settingsSubmenu.classList.remove('hidden');
                if (settingsArrow) settingsArrow.classList.add('rotate-180');
            }
            
            // Update active submenu link
            const hashLink = document.querySelector(`a[href="${hash}"]`);
            if (hashLink) {
                // Remove active from all submenu links
                document.querySelectorAll('.submenu-link').forEach(link => {
                    link.classList.remove('active');
                });
                // Add active to current hash link
                hashLink.classList.add('active');
            }
        }
    });
    </script>
    
    

    @stack('scripts')
</body>
</html>