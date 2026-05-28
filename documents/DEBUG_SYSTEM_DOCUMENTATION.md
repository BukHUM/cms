# Debug System Documentation

## Overview
ระบบ Debug Mode และ Debug Bar ที่สมบูรณ์แบบสำหรับ Laravel CMS Backend ที่ให้ความยืดหยุ่นในการจัดการ debug settings

## Features
- ✅ เปิด/ปิด debug mode ผ่านการตั้งค่า
- ✅ เปิด/ปิด debug bar ผ่านการตั้งค่า
- ✅ Middleware สำหรับจัดการ debug settings
- ✅ Service Provider สำหรับ configure debug
- ✅ Artisan command สำหรับจัดการ
- ✅ ปุ่มเปิด/ปิดในหน้า settings
- ✅ ข้อความยืนยันและสำเร็จที่เหมาะสม

## Installation

### 1. Middleware Registration
Middleware ถูกลงทะเบียนใน `bootstrap/app.php`:
```php
$middleware->web(append: [
    \App\Http\Middleware\CheckMaintenanceMode::class,
    \App\Http\Middleware\CheckDebugMode::class,
]);
```

### 2. Service Provider Registration
Service Provider ถูกลงทะเบียนใน `bootstrap/providers.php`:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\BladeDirectivesServiceProvider::class,
    App\Providers\DebugServiceProvider::class,
    App\Providers\SettingsServiceProvider::class,
];
```

## Usage

### 1. Artisan Commands
```bash
# เปิด debug mode
php artisan debug on

# เปิด debug mode และ debug bar
php artisan debug on --bar

# ปิด debug mode และ debug bar
php artisan debug off

# ตรวจสอบสถานะ
php artisan debug status
```

### 2. Programmatic Control
```php
// เปิด debug mode
set_setting('debug_mode', true, 'boolean', 'general');

// เปิด debug bar
set_setting('debug_bar', true, 'boolean', 'general');

// ปิด debug mode
set_setting('debug_mode', false, 'boolean', 'general');

// ปิด debug bar
set_setting('debug_bar', false, 'boolean', 'general');

// ตรวจสอบสถานะ
$debugMode = setting('debug_mode', false);
$debugBar = setting('debug_bar', false);
```

### 3. Settings Page
- ไปที่หน้า Settings General
- หา `debug_mode` และ `debug_bar` ในตาราง
- คลิกปุ่มเปิด/ปิดในคอลัมน์ "การดำเนินการ"
- ระบบจะแสดงข้อความยืนยันที่เหมาะสม

## Configuration

### 1. Settings
- `debug_mode` (boolean): เปิด/ปิด debug mode
- `debug_bar` (boolean): เปิด/ปิด debug bar

### 2. Debug Mode Effects
เมื่อเปิด debug mode:
- `APP_DEBUG` = true
- `APP_LOG_LEVEL` = debug
- แสดง error messages รายละเอียด
- แสดง stack traces
- เปิดใช้งาน logging รายละเอียด

เมื่อปิด debug mode:
- `APP_DEBUG` = false
- `APP_LOG_LEVEL` = error
- ซ่อน error messages รายละเอียด
- แสดง error messages แบบสั้น
- ปิดใช้งาน logging รายละเอียด

### 3. Debug Bar Effects
เมื่อเปิด debug bar (ต้องเปิด debug mode ด้วย):
- `DEBUGBAR_ENABLED` = true
- แสดง debug toolbar ที่ด้านล่างหน้าเว็บ
- แสดงข้อมูล queries, routes, views, etc.
- เปิดใช้งาน debug bar storage

เมื่อปิด debug bar:
- `DEBUGBAR_ENABLED` = false
- ซ่อน debug toolbar
- ปิดใช้งาน debug bar storage

## Files Structure
```
app/
├── Http/
│   └── Middleware/
│       └── CheckDebugMode.php
├── Providers/
│   └── DebugServiceProvider.php
├── Console/
│   └── Commands/
│       └── DebugCommand.php
resources/
└── views/
    └── backend/
        └── settings-general/
            └── index.blade.php
bootstrap/
├── app.php
└── providers.php
```

## Testing

### 1. Enable Debug Mode
```bash
php artisan debug on
php artisan cache:clear
```

### 2. Test Frontend
- เปิดหน้าเว็บ frontend
- ควรเห็น error messages รายละเอียด (ถ้ามี error)
- ตรวจสอบว่า debug mode ทำงาน

### 3. Enable Debug Bar
```bash
php artisan debug on --bar
php artisan cache:clear
```

### 4. Test Debug Bar
- เปิดหน้าเว็บ frontend
- ควรเห็น debug toolbar ที่ด้านล่างหน้าเว็บ
- ตรวจสอบว่า debug bar ทำงาน

### 5. Disable Debug
```bash
php artisan debug off
php artisan cache:clear
```

## Troubleshooting

### 1. Debug Mode ไม่ทำงาน
- ตรวจสอบว่า middleware ถูกลงทะเบียนใน `bootstrap/app.php`
- ตรวจสอบว่า service provider ถูกลงทะเบียนใน `bootstrap/providers.php`
- Clear cache: `php artisan cache:clear`
- ตรวจสอบการตั้งค่า: `php artisan debug status`

### 2. Debug Bar ไม่แสดง
- ตรวจสอบว่า debug mode เปิดอยู่: `php artisan debug status`
- Debug bar จะทำงานได้เฉพาะเมื่อ debug mode เปิดอยู่
- ตรวจสอบว่า Laravel Debugbar package ติดตั้งแล้ว

### 3. Settings ไม่เปลี่ยนแปลง
- ตรวจสอบว่า middleware ทำงาน
- Clear cache: `php artisan cache:clear`
- ตรวจสอบว่า service provider ทำงาน

## Security Notes
- Debug mode ควรเปิดเฉพาะใน development environment
- Debug bar ควรเปิดเฉพาะใน development environment
- ไม่ควรเปิด debug mode ใน production environment
- ใช้สำหรับการ debug และ development เท่านั้น
