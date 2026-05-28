<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'เข้าสู่ระบบ') - {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</title>
    
    <!-- Tailwind CSS, Fonts, Font Awesome -->
    @vite(['resources/css/fonts.css', 'resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="login-page">
    @yield('content')
    
    <!-- SweetAlert2 Configuration -->
    <script>
        // Configure SweetAlert2 defaults with Tailwind classes
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
    </script>
    
    @stack('scripts')
</body>
</html>
