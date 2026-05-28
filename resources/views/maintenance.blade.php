<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบกำลังบำรุงรักษา - {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'prompt': ['Prompt', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                        'primary-dark': '#1d4ed8',
                    },
                    animation: {
                        'pulse-slow': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'float': 'float 20s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translate(0, 0)' },
                            '33%': { transform: 'translate(30px, -30px)' },
                            '66%': { transform: 'translate(-20px, 20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary via-primary-dark to-blue-600 flex items-center justify-center p-5 relative overflow-hidden">
    <!-- Background Animation -->
    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/30 via-pink-500/30 to-blue-500/30 animate-float"></div>
    
    <div class="relative z-10 w-full max-w-2xl mx-auto">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-8 md:p-12 text-center shadow-2xl border border-white/20">
            <!-- Maintenance Icon -->
            <div class="w-32 h-32 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse-slow">
                <i class="fas fa-tools text-white text-5xl"></i>
            </div>
            
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent mb-4">
                ระบบกำลังบำรุงรักษา
            </h1>
            <p class="text-xl text-slate-600 mb-8">เรากำลังปรับปรุงระบบเพื่อให้บริการที่ดีขึ้น</p>
            
            <!-- Description -->
            <p class="text-slate-600 leading-relaxed mb-8">
                ขออภัยในความไม่สะดวก ระบบของเรากำลังอยู่ในระหว่างการบำรุงรักษา 
                เพื่อปรับปรุงประสิทธิภาพและเพิ่มฟีเจอร์ใหม่ๆ ให้กับคุณ
            </p>
            
            <!-- Progress Section -->
            <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">ความคืบหน้า</h3>
                <div class="w-full bg-slate-200 rounded-full h-2 mb-4">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full animate-pulse" style="width: 75%"></div>
                </div>
                <p class="text-sm text-slate-600">กำลังดำเนินการ... กรุณารอสักครู่</p>
            </div>
            
            <!-- Contact Information -->
            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">ติดต่อเรา</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary to-primary-dark rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-white text-sm"></i>
                        </div>
                        <span class="text-slate-700">support@example.com</span>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary to-primary-dark rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-white text-sm"></i>
                        </div>
                        <span class="text-slate-700">02-123-4567</span>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary to-primary-dark rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <span class="text-slate-700">24/7 Support</span>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <p class="text-slate-500 text-sm flex items-center justify-center gap-1">
                    <i class="fas fa-copyright"></i>
                    {{ now()->year }} {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }} - ระบบจัดการหลังบ้าน
                </p>
            </div>
        </div>
    </div>
    
    <!-- Auto Refresh Script -->
    <script>
        // Auto refresh every 30 seconds
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        
        // Show current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('th-TH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }
        
        // Check maintenance status
        function checkMaintenanceStatus() {
            const button = document.querySelector('.refresh-button');
            const spinner = document.getElementById('loading-spinner');
            const buttonText = document.getElementById('button-text');
            
            // Show loading
            spinner.classList.remove('hidden');
            buttonText.textContent = 'กำลังตรวจสอบ...';
            button.disabled = true;
            
            // Check status
            fetch('/api/maintenance-status')
                .then(response => response.json())
                .then(data => {
                    if (!data.maintenance) {
                        // Maintenance mode is off, reload page
                        window.location.reload();
                    } else {
                        // Still in maintenance mode
                        buttonText.textContent = 'ยังอยู่ในโหมดบำรุงรักษา';
                        setTimeout(() => {
                            buttonText.textContent = 'ตรวจสอบสถานะใหม่';
                            spinner.classList.add('hidden');
                            button.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error checking maintenance status:', error);
                    buttonText.textContent = 'เกิดข้อผิดพลาด';
                    setTimeout(() => {
                        buttonText.textContent = 'ตรวจสอบสถานะใหม่';
                        spinner.classList.add('hidden');
                        button.disabled = false;
                    }, 2000);
                });
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>