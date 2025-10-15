<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบกำลังบำรุงรักษา - {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Prompt', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .maintenance-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            max-width: 500px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .maintenance-icon {
            font-size: 4rem;
            color: #3b82f6;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .maintenance-title {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .maintenance-message {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            font-weight: 400;
        }
        
        .maintenance-details {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }
        
        .maintenance-details h5 {
            color: #334155;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .maintenance-details p {
            color: #64748b;
            margin-bottom: 0.5rem;
            font-weight: 400;
        }
        
        .contact-info {
            background: #eff6ff;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #dbeafe;
        }
        
        .contact-info h6 {
            color: #1d4ed8;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .contact-info p {
            color: #475569;
            margin-bottom: 0;
            font-weight: 400;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #f59e0b;
            margin-right: 0.5rem;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        
        .maintenance-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 2rem;
                margin: 10px;
            }
            
            .maintenance-title {
                font-size: 1.5rem;
            }
            
            .maintenance-message {
                font-size: 1rem;
            }
            
            .maintenance-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1 class="maintenance-title">ระบบกำลังบำรุงรักษา</h1>
        
        <p class="maintenance-message">
            ขออภัยในความไม่สะดวก ระบบกำลังอยู่ในระหว่างการบำรุงรักษาเพื่อปรับปรุงประสิทธิภาพและเพิ่มฟีเจอร์ใหม่
        </p>
        
        <div class="maintenance-details">
            <h5><i class="fas fa-info-circle me-2"></i>ข้อมูลการบำรุงรักษา</h5>
            <p><span class="status-indicator"></span>สถานะ: กำลังบำรุงรักษา</p>
            <p><i class="fas fa-clock me-2"></i>เวลาเริ่มต้น: {{ now()->format('d/m/Y H:i') }}</p>
            <p><i class="fas fa-calendar me-2"></i>วันที่คาดการณ์เสร็จสิ้น: {{ now()->addHours(2)->format('d/m/Y H:i') }}</p>
        </div>
        
        <div class="contact-info">
            <h6><i class="fas fa-headset me-2"></i>ติดต่อสอบถาม</h6>
            <p>หากมีข้อสงสัยหรือต้องการความช่วยเหลือ กรุณาติดต่อทีมพัฒนา</p>
        </div>
        
        <div class="maintenance-footer">
            <p><i class="fas fa-copyright me-1"></i>{{ now()->year }} {{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }} - ระบบจัดการหลังบ้าน</p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh every 30 seconds to check if maintenance is over
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        
        // Show current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.title = `ระบบกำลังบำรุงรักษา - ${timeString}`;
        }
        
        // Update time every second
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>
