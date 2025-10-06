<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'เข้าสู่ระบบ') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    @stack('styles')
</head>
<body class="login-page">
    @yield('content')
    
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
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
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
