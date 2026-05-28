<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body {
            font-family: 'Prompt', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .message {
            background-color: #f8f9fa;
            border-left: 4px solid #3B82F6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #3B82F6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #2563eb;
        }
        .data-section {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .data-section h3 {
            margin-top: 0;
            color: #495057;
        }
        .data-item {
            margin: 8px 0;
        }
        .data-label {
            font-weight: bold;
            color: #495057;
        }
        .data-value {
            color: #6c757d;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>
                <i class="fas fa-cogs"></i>
                CMS Backend
            </h1>
        </div>
        
        <div class="content">
            <h2>การแจ้งเตือนจากระบบ</h2>
            
            <div class="message">
                {!! nl2br(e($message)) !!}
            </div>
            
            @if(isset($data) && !empty($data))
                <div class="data-section">
                    <h3>ข้อมูลเพิ่มเติม</h3>
                    @foreach($data as $key => $value)
                        @if($key !== 'type' && $key !== 'priority')
                            <div class="data-item">
                                <span class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                <span class="data-value">
                                    @if(is_object($value))
                                        {{ $value->name ?? $value->email ?? 'Object' }}
                                    @elseif(is_array($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
            
            @if(isset($data['type']))
                @switch($data['type'])
                    @case('welcome')
                        <p>ยินดีต้อนรับสู่ระบบ CMS Backend! หากคุณมีคำถามใดๆ กรุณาติดต่อทีมสนับสนุน</p>
                        @break
                    @case('password_reset')
                        <p><strong>หมายเหตุ:</strong> ลิงก์รีเซ็ตรหัสผ่านจะหมดอายุใน 60 นาที</p>
                        @break
                    @case('account_status_change')
                        <p>หากคุณมีคำถามเกี่ยวกับการเปลี่ยนแปลงสถานะบัญชี กรุณาติดต่อทีมสนับสนุน</p>
                        @break
                    @case('system_notification')
                        <p>นี่คือการแจ้งเตือนจากระบบ กรุณาติดตามและดำเนินการตามความเหมาะสม</p>
                        @break
                @endswitch
            @endif
        </div>
        
        <div class="footer">
            <p>อีเมลนี้ถูกส่งจากระบบ CMS Backend</p>
            <p>หากคุณไม่ต้องการรับการแจ้งเตือน กรุณาติดต่อผู้ดูแลระบบ</p>
            <p>&copy; {{ date('Y') }} CMS Backend. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
