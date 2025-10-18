# Settings System Documentation

## Overview
ระบบการจัดการการตั้งค่าที่สมบูรณ์แบบที่ให้ความยืดหยุ่นในการจัดการค่าการตั้งค่าต่างๆ ของแอปพลิเคชัน โดยใช้ฐานข้อมูลเป็นหลักและสามารถ override ค่าจาก .env ได้

## Features
- ✅ จัดเก็บการตั้งค่าในฐานข้อมูล
- ✅ Cache เพื่อประสิทธิภาพ
- ✅ Type casting อัตโนมัติ
- ✅ Override ค่าจาก .env
- ✅ Helper functions ที่ใช้งานง่าย
- ✅ Blade directives
- ✅ Artisan commands สำหรับจัดการ

## Installation

### 1. Service Providers
ระบบจะลงทะเบียน Service Providers อัตโนมัติ:
- `SettingsServiceProvider` - โหลดการตั้งค่าจากฐานข้อมูล
- `BladeDirectivesServiceProvider` - Blade directives

### 2. Helper Functions
```php
// Get a setting value
$siteName = setting('site_name', 'Default Site');

// Get settings by category
$generalSettings = settings('general');

// Set a setting value
set_setting('site_name', 'My Site', 'string', 'general');

// Toggle setting status
toggle_setting('site_name');

// Clear cache
clear_settings_cache();
```

## Usage

### In Controllers
```php
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $siteName = setting('site_name');
        $maintenanceMode = setting('maintenance_mode', false);
        
        if ($maintenanceMode) {
            return view('maintenance');
        }
        
        return view('home', compact('siteName'));
    }
}
```

### In Blade Templates
```blade
<!DOCTYPE html>
<html>
<head>
    <title>@setting('site_name')</title>
    <meta name="description" content="@setting('site_description')">
</head>
<body>
    <h1>@setting('site_name')</h1>
    
    @ifsetting('maintenance_mode')
        <div class="alert">@setting('maintenance_message')</div>
    @endsetting
    
    @ifnotsetting('enable_registration')
        <div class="info">การลงทะเบียนถูกปิดใช้งาน</div>
    @endsetting
</body>
</html>
```

### In JavaScript (AJAX)
```javascript
// Get setting value
fetch('/api/settings/site_name')
    .then(response => response.json())
    .then(data => {
        document.title = data.value;
    });
```

## Artisan Commands

### View Settings
```bash
# View all settings
php artisan settings:show

# View settings by category
php artisan settings:show general
php artisan settings:show email
php artisan settings:show security
```

### Test Settings
```bash
# Test settings functionality
php artisan settings:test
```

### Sync from .env
```bash
# Sync settings from .env file
php artisan settings:sync-env
```

### Clear Cache
```bash
# Clear settings cache
php artisan settings:clear-cache
```

## Database Structure

### core_settings Table
```sql
CREATE TABLE core_settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    key VARCHAR(255) UNIQUE NOT NULL,
    value TEXT,
    type ENUM('string', 'boolean', 'integer', 'float', 'email', 'url', 'json', 'array') DEFAULT 'string',
    category ENUM('general', 'performance', 'backup', 'email', 'security', 'system') DEFAULT 'general',
    group_name VARCHAR(255) DEFAULT 'default',
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    is_public BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    validation_rules JSON,
    default_value TEXT,
    options JSON,
    created_by BIGINT,
    updated_by BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

## Configuration Override

ระบบจะ override ค่าจาก .env โดยอัตโนมัติ:

### App Settings
- `APP_NAME` → `site_name`
- `APP_LOCALE` → `site_language`
- `APP_TIMEZONE` → `site_timezone`

### Mail Settings
- `MAIL_FROM_ADDRESS` → `mail_from_address`
- `MAIL_FROM_NAME` → `mail_from_name`
- `MAIL_HOST` → `mail_host`
- `MAIL_PORT` → `mail_port`
- `MAIL_USERNAME` → `mail_username`
- `MAIL_PASSWORD` → `mail_password`
- `MAIL_ENCRYPTION` → `mail_encryption`

### Security Settings
- `SESSION_LIFETIME` → `session_lifetime`

### Performance Settings
- `CACHE_STORE` → `cache_driver`

## Type Casting

ระบบจะ cast ค่าให้ตรงกับ type ที่กำหนด:

```php
// Boolean
setting('maintenance_mode') // Returns: true/false

// Integer
setting('max_upload_size') // Returns: 10 (integer)

// Float
setting('tax_rate') // Returns: 7.5 (float)

// JSON
setting('social_links') // Returns: ['facebook' => '...', 'twitter' => '...']

// Array
setting('allowed_extensions') // Returns: ['jpg', 'png', 'pdf']
```

## Cache Management

### Cache Keys
- `setting.{key}` - Individual setting
- `settings.category.{category}` - Category settings
- `settings.config` - All settings

### Cache Duration
- Default: 1 hour (3600 seconds)
- Configurable in `SettingsService`

## Security

### Public vs Private Settings
- `is_public = true` - สามารถเข้าถึงได้จากภายนอก
- `is_public = false` - เฉพาะภายในระบบเท่านั้น

### Active vs Inactive Settings
- `is_active = true` - การตั้งค่าที่ใช้งานได้
- `is_active = false` - การตั้งค่าที่ปิดใช้งาน

## Examples

### Website Settings
```php
// Get website information
$siteName = setting('site_name');
$siteDescription = setting('site_description');
$siteVersion = setting('site_version');
$siteLanguage = setting('site_language');
$siteTimezone = setting('site_timezone');
```

### System Settings
```php
// Check maintenance mode
if (setting('maintenance_mode')) {
    $message = setting('maintenance_message');
    return view('maintenance', compact('message'));
}

// Check registration status
if (!setting('enable_registration')) {
    return redirect()->back()->with('error', 'การลงทะเบียนถูกปิดใช้งาน');
}
```

### File Upload Settings
```php
// Get upload limits
$maxSize = setting('max_upload_size'); // MB
$allowedTypes = setting('allowed_file_types'); // Comma-separated string

// Convert to array
$allowedTypesArray = explode(',', $allowedTypes);
```

## Troubleshooting

### Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan settings:clear-cache
php artisan config:clear
```

### Database Issues
```bash
# Check settings table
php artisan settings:show

# Test settings functionality
php artisan settings:test
```

### Performance Issues
- ใช้ cache เพื่อลดการ query ฐานข้อมูล
- ตั้งค่า cache duration ที่เหมาะสม
- ใช้ `is_active` เพื่อปิดการตั้งค่าที่ไม่จำเป็น

## Best Practices

1. **ใช้ default values** เสมอเมื่อเรียกใช้ `setting()`
2. **Clear cache** หลังจากอัปเดตการตั้งค่า
3. **ใช้ type casting** ที่ถูกต้อง
4. **ตั้งค่า `is_public`** ให้เหมาะสม
5. **ใช้ `is_active`** เพื่อควบคุมการใช้งาน

## Migration from .env

หากต้องการย้ายจากการใช้ .env ไปใช้ระบบนี้:

1. รัน `php artisan settings:sync-env`
2. อัปเดตโค้ดให้ใช้ `setting()` แทน `env()`
3. ทดสอบการทำงาน
4. ลบค่าที่ไม่จำเป็นออกจาก .env

## Support

หากพบปัญหาหรือต้องการความช่วยเหลือ:
1. ตรวจสอบ logs ใน `storage/logs/laravel.log`
2. รัน `php artisan settings:test` เพื่อทดสอบ
3. ตรวจสอบ cache ด้วย `php artisan settings:clear-cache`
4. ตรวจสอบฐานข้อมูลด้วย `php artisan settings:show`
