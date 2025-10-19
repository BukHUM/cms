# Maintenance Mode System

## Overview
ระบบ Maintenance Mode ที่สมบูรณ์แบบสำหรับ Laravel CMS Backend ที่ให้ความยืดหยุ่นในการจัดการโหมดบำรุงรักษา

## Features
- ✅ เปิด/ปิด maintenance mode ผ่านการตั้งค่า
- ✅ แสดงหน้า maintenance ที่สวยงาม
- ✅ API endpoint สำหรับตรวจสอบสถานะ
- ✅ Artisan command สำหรับจัดการ
- ✅ Auto-refresh และ real-time status
- ✅ Responsive design
- ✅ อนุญาตให้เข้าถึง backend และ API

## Installation

### 1. Middleware Registration
Middleware ถูกลงทะเบียนใน `bootstrap/app.php`:
```php
$middleware->web(append: [
    \App\Http\Middleware\CheckMaintenanceMode::class,
]);
```

### 2. Routes
```php
// Maintenance API (accessible even during maintenance mode)
Route::get('/api/maintenance-status', [App\Http\Controllers\Api\MaintenanceController::class, 'status']);
```

## Usage

### 1. Artisan Commands
```bash
# เปิด maintenance mode
php artisan maintenance on

# เปิด maintenance mode พร้อมข้อความ
php artisan maintenance on --message="ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ"

# ปิด maintenance mode
php artisan maintenance off

# ตรวจสอบสถานะ
php artisan maintenance status
```

### 2. Programmatic Control
```php
// เปิด maintenance mode
set_setting('maintenance_mode', true, 'boolean', 'general');
set_setting('maintenance_message', 'ข้อความบำรุงรักษา', 'string', 'general');

// ปิด maintenance mode
set_setting('maintenance_mode', false, 'boolean', 'general');

// ตรวจสอบสถานะ
$isMaintenance = setting('maintenance_mode', false);
$message = setting('maintenance_message', 'Default message');
```

### 3. API Endpoint
```javascript
// ตรวจสอบสถานะ maintenance
fetch('/api/maintenance-status')
    .then(response => response.json())
    .then(data => {
        if (data.maintenance) {
            console.log('Maintenance mode is ON');
            console.log('Message:', data.message);
        } else {
            console.log('System is operational');
        }
    });
```

## Configuration

### 1. Settings
- `maintenance_mode` (boolean): เปิด/ปิด maintenance mode
- `maintenance_message` (string): ข้อความแสดงในหน้า maintenance

### 2. Excluded Routes
ระบบจะอนุญาตให้เข้าถึง routes เหล่านี้แม้ในโหมด maintenance:
- `/backend/*` - Backend routes
- `/admin/*` - Admin routes  
- `/login`, `/logout` - Authentication routes
- `/auth/*` - Auth routes
- `/api/*` - API routes

### 3. Maintenance Page Features
- Responsive design
- Real-time clock
- Auto-refresh every 30 seconds
- Manual refresh button
- Loading spinner
- Contact information
- Backend login link

## Files Structure
```
app/
├── Http/
│   ├── Middleware/
│   │   └── CheckMaintenanceMode.php
│   └── Controllers/
│       └── Api/
│           └── MaintenanceController.php
├── Console/
│   └── Commands/
│       └── MaintenanceCommand.php
resources/
└── views/
    └── maintenance.blade.php
routes/
└── web.php
bootstrap/
└── app.php
```

## Testing

### 1. Enable Maintenance Mode
```bash
php artisan maintenance on
```

### 2. Test Frontend
- เปิดหน้าเว็บ frontend
- ควรเห็นหน้า maintenance
- ตรวจสอบว่า backend ยังเข้าถึงได้

### 3. Test API
```bash
curl http://localhost/api/maintenance-status
```

### 4. Disable Maintenance Mode
```bash
php artisan maintenance off
```

## Troubleshooting

### 1. Maintenance Mode ไม่ทำงาน
- ตรวจสอบว่า middleware ถูกลงทะเบียนใน `bootstrap/app.php`
- Clear cache: `php artisan cache:clear`
- ตรวจสอบการตั้งค่า: `php artisan maintenance status`

### 2. Backend เข้าไม่ได้
- ตรวจสอบว่า route `/backend/*` ถูก exclude ใน middleware
- ตรวจสอบการตั้งค่า authentication

### 3. API ไม่ทำงาน
- ตรวจสอบว่า route `/api/maintenance-status` ถูกเพิ่มใน `routes/web.php`
- ตรวจสอบว่า controller ถูกสร้างถูกต้อง

## Security Notes
- Maintenance mode จะไม่บล็อก backend routes
- API endpoints ยังทำงานได้ปกติ
- ใช้สำหรับการบำรุงรักษาระบบเท่านั้น
- ควรปิดทันทีหลังเสร็จสิ้นการบำรุงรักษา
