<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'หน้าแรก') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'ระบบจัดการข้อมูลที่ทันสมัย')">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    <style>
        /* Frontend specific styles - Typography handled by app.css */
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 400;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0 20px;
        }
        .footer a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #3498db;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-rocket me-2"></i>
                {{ config('app.name') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i>เกี่ยวกับเรา
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">
                            <i class="fas fa-cogs me-1"></i>บริการ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fas fa-envelope me-1"></i>ติดต่อ
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user-circle me-2"></i>โปรไฟล์</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>แดชบอร์ด</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>เข้าสู่ระบบ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>สมัครสมาชิก
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-rocket me-2"></i>{{ config('app.name') }}</h5>
                    <p class="text-muted">ระบบจัดการข้อมูลที่ทันสมัยและใช้งานง่าย</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>ลิงก์ด่วน</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">หน้าแรก</a></li>
                        <li><a href="{{ route('about') }}">เกี่ยวกับเรา</a></li>
                        <li><a href="{{ route('services') }}">บริการ</a></li>
                        <li><a href="{{ route('contact') }}">ติดต่อ</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>บัญชีผู้ใช้</h6>
                    <ul class="list-unstyled">
                        @auth
                            <li><a href="{{ route('profile') }}">โปรไฟล์</a></li>
                            <li><a href="{{ route('dashboard') }}">แดชบอร์ด</a></li>
                        @else
                            <li><a href="{{ route('login') }}">เข้าสู่ระบบ</a></li>
                            <li><a href="{{ route('register') }}">สมัครสมาชิก</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6>ติดต่อเรา</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>info@example.com</li>
                        <li><i class="fas fa-phone me-2"></i>+66 2-123-4567</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>กรุงเทพมหานคร, ประเทศไทย</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. สงวนลิขสิทธิ์.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">นโยบายความเป็นส่วนตัว</a>
                    <a href="#">ข้อกำหนดการใช้งาน</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- SweetAlert2 Helper -->
    <script src="{{ asset('js/sweetalert-helper.js') }}"></script>
    
    <!-- SweetAlert2 Configuration -->
    <script>
        // Configure SweetAlert2 defaults
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
        
        // Auto-show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: '{{ session('error') }}',
                confirmButtonText: 'ตกลง'
            });
        @endif
        
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'คำเตือน!',
                text: '{{ session('warning') }}',
                confirmButtonText: 'ตกลง'
            });
        @endif
        
        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'ข้อมูล!',
                text: '{{ session('info') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>
