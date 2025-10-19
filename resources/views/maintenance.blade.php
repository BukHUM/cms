<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - ระบบบำรุงรักษา</title>
    <meta name="description" content="{{ $siteDescription }}">
    <meta name="robots" content="noindex, nofollow">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        
        .maintenance-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            margin: 20px;
        }
        
        .maintenance-icon {
            font-size: 80px;
            color: #f39c12;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .maintenance-subtitle {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .maintenance-message {
            background: #f8f9fa;
            border-left: 4px solid #f39c12;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
            font-size: 1.1rem;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        .maintenance-info {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            color: #0c5460;
        }
        
        .maintenance-info h3 {
            color: #0c5460;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .maintenance-info p {
            margin-bottom: 5px;
            font-size: 0.95rem;
        }
        
        .refresh-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }
        
        .refresh-button:hover {
            background: #2980b9;
        }
        
        .contact-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .hidden {
            display: none;
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 20px;
            }
            
            .maintenance-title {
                font-size: 2rem;
            }
            
            .maintenance-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            🔧
        </div>
        
        <h1 class="maintenance-title">ระบบบำรุงรักษา</h1>
        
        <p class="maintenance-subtitle">
            {{ $siteName }} กำลังอยู่ในโหมดบำรุงรักษา
        </p>
        
        <div class="maintenance-message">
            {{ $message }}
        </div>
        
        <div class="maintenance-info">
            <h3>📋 ข้อมูลระบบ</h3>
            <p><strong>เว็บไซต์:</strong> {{ $siteName }}</p>
            <p><strong>คำอธิบาย:</strong> {{ $siteDescription }}</p>
            <p><strong>เวลา:</strong> <span id="current-time"></span></p>
            <p><strong>สถานะ:</strong> ระบบบำรุงรักษา</p>
        </div>
        
        <button class="refresh-button" onclick="checkMaintenanceStatus()">
            <span class="loading-spinner hidden" id="loading-spinner"></span>
            <span id="button-text">ตรวจสอบสถานะใหม่</span>
        </button>
        
        <div class="contact-info">
            <p>หากคุณเป็นผู้ดูแลระบบ กรุณาเข้าสู่ระบบผ่าน <a href="/backend/login" style="color: #3498db;">Backend</a></p>
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Update current time
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
        
        // Update time every second
        updateTime();
        setInterval(updateTime, 1000);
        
        // Auto-refresh every 30 seconds
        setInterval(checkMaintenanceStatus, 30000);
    </script>
</body>
</html>
