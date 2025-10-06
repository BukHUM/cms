<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    
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
            background-color: transparent !important;
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
            background-color: transparent !important;
            transform: none !important;
            box-shadow: none !important;
            border-color: inherit !important;
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
            background-color: transparent !important;
            transform: none !important;
            box-shadow: none !important;
            border-color: inherit !important;
        }
        
        /* Force remove all hover effects with maximum specificity */
        .btn.btn-sm:hover,
        .btn.btn-sm:focus,
        .btn.btn-sm:active,
        .btn.dropdown-toggle:hover,
        .btn.dropdown-toggle:focus,
        .btn.dropdown-toggle:active {
            background-color: transparent !important;
            transform: none !important;
            box-shadow: none !important;
            border-color: inherit !important;
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
                
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="nav-text">
                            <span class="nav-title">รายงาน</span>
                            <small class="nav-subtitle">Reports & Analytics</small>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <span class="user-name">{{ getCurrentAdminUserName() }}</span>
                    <small class="user-role">{{ getCurrentAdminUserRole() }}</small>
                </div>
            </div>
            
            <a href="{{ route('logout') }}" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>ออกจากระบบ</span>
            </a>
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
                    <!-- Search -->
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="ค้นหา...">
                    </div>
                    
                    <!-- Notifications -->
                    <div class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    
                    <!-- Frontend App Link -->
                    <a href="{{ route('home') }}" class="frontend-link" title="เปิดหน้าแอพ" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span class="link-text">หน้าแอพ</span>
                    </a>
                    
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
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                โปรไฟล์
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                ตั้งค่า
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
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
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
                    e.stopPropagation();
                    userMenu.classList.toggle('active');
                });
                
                // Close user menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.remove('active');
                    }
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
            
            // Auto-show success/error messages with Toast
            @if(session('success'))
                // Show toast notification instead of SweetAlert
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
            
            @if(session('error'))
                // Show error toast
                const ErrorToast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                
                ErrorToast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif
            
            @if(session('warning'))
                // Show warning toast
                const WarningToast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                
                WarningToast.fire({
                    icon: 'warning',
                    title: '{{ session('warning') }}'
                });
            @endif
            
            @if(session('info'))
                // Show info toast
                const InfoToast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                
                InfoToast.fire({
                    icon: 'info',
                    title: '{{ session('info') }}'
                });
            @endif
            
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
