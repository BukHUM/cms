<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</title>
    
    @stack('head')
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Enhanced CSS for Settings Menu -->
    <style>
        /* Sidebar Navigation Active State */
        .admin-sidebar .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }
        .admin-sidebar .nav-link.active::before {
            content: '' !important;
            position: absolute !important;
            left: -16px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 4px !important;
            height: 60% !important;
            background: #3b82f6 !important;
            border-radius: 0 2px 2px 0 !important;
        }
        
        /* Ensure settings menu stays active when on settings page */
        body[data-page="settings"] .admin-sidebar .nav-link[href*="settings"] {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }
        body[data-page="settings"] .admin-sidebar .nav-link[href*="settings"]::before {
            content: '' !important;
            position: absolute !important;
            left: -16px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 4px !important;
            height: 60% !important;
            background: #3b82f6 !important;
            border-radius: 0 2px 2px 0 !important;
        }
        
        /* Override Bootstrap btn-outline-secondary hover effects */
        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-secondary:active,
        .btn-outline-secondary:focus-visible {
            background-color: transparent !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
            transform: none !important;
            box-shadow: none !important;
            outline: none !important;
        }
        
        /* Ultra-specific override for Bootstrap CDN styles */
        button.btn.btn-outline-secondary:hover,
        button.btn.btn-outline-secondary:focus,
        button.btn.btn-outline-secondary:active,
        a.btn.btn-outline-secondary:hover,
        a.btn.btn-outline-secondary:focus,
        a.btn.btn-outline-secondary:active {
            background-color: transparent !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Remove hover effects from all outline buttons to match table style */
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info:active,
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active,
        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning:active,
        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success:active,
        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger:active {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Remove hover effects from ALL buttons - ultra specific */
        button.btn:hover,
        button.btn:focus,
        button.btn:active,
        a.btn:hover,
        a.btn:focus,
        a.btn:active,
        .btn:hover,
        .btn:focus,
        .btn:active {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Specific overrides for different button types */
        .btn-info:hover,
        .btn-info:focus,
        .btn-info:active {
            background-color: #0dcaf0 !important;
            border-color: #0dcaf0 !important;
            color: #000 !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-warning:hover,
        .btn-warning:focus,
        .btn-warning:active {
            background-color: #ffca2c !important;
            border-color: #ffca2c !important;
            color: #000 !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-danger:active {
            background-color: #b02a37 !important;
            border-color: #b02a37 !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active {
            background-color: #198754 !important;
            border-color: #198754 !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Secondary button hover effects */
        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Outline button hover effects */
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning:active {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #000 !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger:active {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success:active {
            background-color: #198754 !important;
            border-color: #198754 !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info:active {
            background-color: #0dcaf0 !important;
            border-color: #0dcaf0 !important;
            color: #000 !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-secondary:active {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Ultra-specific overrides for user management buttons */
        .user-management .btn:hover,
        .user-management .btn:focus,
        .user-management .btn:active,
        #users .btn:hover,
        #users .btn:focus,
        #users .btn:active,
        .tab-pane#users .btn:hover,
        .tab-pane#users .btn:focus,
        .tab-pane#users .btn:active {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Force remove all hover effects with maximum specificity */
        .btn.btn-sm:hover,
        .btn.btn-sm:focus,
        .btn.btn-sm:active,
        .btn.dropdown-toggle:hover,
        .btn.dropdown-toggle:focus,
        .btn.dropdown-toggle:active {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* User Menu Dropdown - Simple Fix */
        .user-menu {
            position: relative;
            cursor: pointer;
        }
        
        .user-menu .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 9999;
            pointer-events: none;
        }
        
        .user-menu.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }
        
        .user-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #334155;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            cursor: pointer;
        }
        
        .user-menu .dropdown-item:hover {
            background: #f8fafc;
            color: #1e293b;
        }
        
        .user-menu .dropdown-divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 8px 0;
        }
        
        /* Remove conflicting CSS */
        
        /* Footer styling */
        .sidebar-footer {
            padding: 8px 16px !important;
        }
        
        .sidebar-info {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }
        
        .sidebar-info i {
            font-size: 12px !important;
        }
        
        .sidebar-info span {
            font-size: 11px !important;
            font-weight: 400 !important;
        }
        
    </style>
    
    @stack('styles')
</head>
<body class="admin-body">
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-title">Admin Panel</span>
                    <small class="brand-subtitle">Management System</small>
                </div>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">Dashboard</span>
                            <small class="nav-subtitle">ภาพรวมระบบ</small>
                        </div>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.user-management') }}" class="nav-link {{ request()->routeIs('admin.user-management*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">จัดการผู้ใช้</span>
                            <small class="nav-subtitle">User Management</small>
                        </div>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">ตั้งค่าระบบ</span>
                            <small class="nav-subtitle">System Settings</small>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-info">
                <i class="fas fa-info-circle"></i>
                <span>v.{{ config('app.version', '1.0.0') }}-{{ date('dmY') }}</span>
            </div>
        </div>
    </aside>
    
    <!-- Main Content Area -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Top Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p class="page-subtitle">@yield('page-subtitle', 'ภาพรวมระบบ')</p>
                </div>
            </div>
            
            <div class="header-right">
                <div class="header-actions">
                    <!-- User Menu -->
                    <div class="user-menu">
                        <div class="user-avatar-small">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ getCurrentAdminUserName() }}</span>
                            <small class="user-role">{{ getCurrentAdminUserRole() }}</small>
                        </div>
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                ข้อมูลส่วนตัว
                            </a>
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-edit"></i>
                                แก้ไขข้อมูล
                            </a>
                            <a href="{{ route('admin.profile.change-password') }}" class="dropdown-item">
                                <i class="fas fa-key"></i>
                                เปลี่ยนรหัสผ่าน
                            </a>
                            <hr class="dropdown-divider">
                            <a href="{{ route('logout') }}" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                ออกจากระบบ
                            </a>
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
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- SweetAlert2 Helper -->
    <script src="{{ asset('js/sweetalert-helper.js') }}"></script>
    
    <!-- Admin Panel JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const sidebar = document.getElementById('adminSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainWrapper = document.getElementById('mainWrapper');
            const userMenu = document.querySelector('.user-menu');
            
            // Sidebar Toggle Function
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                mainWrapper.classList.toggle('sidebar-active');
                mobileOverlay.classList.toggle('active');
                
                // Save state to localStorage (only for mobile/tablet)
                if (window.innerWidth < 1024) {
                    const isActive = sidebar.classList.contains('active');
                    localStorage.setItem('sidebarActive', isActive);
                }
            }
            
            // Close Sidebar Function
            function closeSidebar() {
                sidebar.classList.remove('active');
                mainWrapper.classList.remove('sidebar-active');
                mobileOverlay.classList.remove('active');
                
                // Save state to localStorage (only for mobile/tablet)
                if (window.innerWidth < 1024) {
                    localStorage.setItem('sidebarActive', false);
                }
            }
            
            // Event Listeners
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            mobileOverlay.addEventListener('click', closeSidebar);
            
            // User Menu Toggle
            if (userMenu) {
                userMenu.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userMenu.classList.toggle('active');
                });
                
                // Close user menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.remove('active');
                    }
                });
                
                // Handle dropdown items
                const dropdownItems = userMenu.querySelectorAll('.dropdown-item');
                dropdownItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (this.textContent.includes('ออกจากระบบ')) {
                            userMenu.classList.remove('active');
                        }
                    });
                });
            }
            
            // Don't restore sidebar state automatically
            // Sidebar will start hidden on mobile/tablet by default
            // Only restore if user explicitly opened it before
            const sidebarState = localStorage.getItem('sidebarActive');
            if (sidebarState === 'true' && window.innerWidth < 1024) {
                // Only restore if it was explicitly opened
                sidebar.classList.add('active');
                mainWrapper.classList.add('sidebar-active');
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    // Desktop: close mobile overlay and reset sidebar state
                    mobileOverlay.classList.remove('active');
                    sidebar.classList.remove('active');
                    mainWrapper.classList.remove('sidebar-active');
                    // Clear localStorage for desktop
                    localStorage.removeItem('sidebarActive');
                } else if (window.innerWidth > 768) {
                    // Tablet: close mobile overlay
                    mobileOverlay.classList.remove('active');
                } else {
                    // Mobile: close sidebar if open
                    if (sidebar.classList.contains('active')) {
                        closeSidebar();
                    }
                }
            });
            
            // SweetAlert2 Configuration
            Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-secondary',
                    denyButton: 'btn btn-warning',
                    input: 'form-control'
                },
                buttonsStyling: false,
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
                denyButtonText: 'ไม่',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            
            // Enhanced logout confirmation
            const logoutLinks = document.querySelectorAll('a[href="{{ route('logout') }}"]');
            
            logoutLinks.forEach((link, index) => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    try {
                        // Show confirmation dialog and wait for user response
                        const result = await Swal.fire({
                            title: 'ยืนยันการออกจากระบบ',
                            text: 'คุณต้องการออกจากระบบหรือไม่?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'ออกจากระบบ',
                            cancelButtonText: 'ยกเลิก',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            reverseButtons: true
                        });
                        
                        // Only proceed if user clicks "ออกจากระบบ"
                        if (result.isConfirmed) {
                            // Close the confirmation dialog first
                            Swal.close();
                            
                            // Submit logout form immediately
                            
                            // Create and submit form immediately
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('logout') }}';
                            
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            
                            form.appendChild(csrfToken);
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            // User clicked cancel or closed dialog
                            // Close the dialog
                            Swal.close();
                        }
                    } catch (error) {
                        // Handle any errors
                        console.error('SweetAlert error:', error);
                        // Close any open dialogs
                        Swal.close();
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
