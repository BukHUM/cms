# Session Security Guide

## ภาพรวม
เอกสารนี้อธิบายการปรับปรุง Session Security ตามมาตรฐาน OWASP เพื่อป้องกัน Session Hijacking, Session Fixation และ Session Management Vulnerabilities

## การปรับปรุงที่ทำ

### 1. **Session Configuration (config/session.php)**

#### การตั้งค่าที่ปรับปรุง:
```php
// เปลี่ยนจาก file เป็น database driver
'driver' => env('SESSION_DRIVER', 'database'),

// ลด session lifetime จาก 120 เป็น 30 นาที
'lifetime' => (int) env('SESSION_LIFETIME', 30),

// เปิดใช้ session encryption
'encrypt' => env('SESSION_ENCRYPT', true),

// ตั้งค่า database connection
'connection' => env('SESSION_CONNECTION', 'mysql'),

// ตั้งค่า secure cookie
'secure' => env('SESSION_SECURE_COOKIE', true),

// เปลี่ยน Same-Site จาก lax เป็น strict
'same_site' => env('SESSION_SAME_SITE', 'strict'),
```

### 2. **Session Table Migration**

#### ตาราง `laravel_sessions`:
```sql
CREATE TABLE laravel_sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity),
    INDEX idx_user_activity (user_id, last_activity),
    INDEX idx_ip_activity (ip_address, last_activity)
);
```

### 3. **SessionTimeoutMiddleware**

#### ฟีเจอร์ใหม่ที่เพิ่ม:
- **Session Timeout Detection**: ตรวจสอบ session timeout และล็อกกิจกรรม
- **Suspicious Activity Detection**: ตรวจสอบการเปลี่ยน IP address
- **Secure Session Cleanup**: ล้างข้อมูล session อย่างปลอดภัย
- **Session Regeneration**: สร้าง session ID ใหม่เมื่อ timeout

#### ตัวอย่างการใช้งาน:
```php
// ตรวจสอบ session timeout
if ($lastActivity && (time() - $lastActivity) > $timeout) {
    Log::warning('Session timeout detected', [
        'user_id' => Auth::id(),
        'ip_address' => $request->ip(),
        'timeout_duration' => $timeout
    ]);
    
    $this->clearSessionData();
    session()->regenerate();
}
```

### 4. **Login/Logout Security**

#### การปรับปรุง Login:
```php
// Regenerate session ID for security
session()->regenerate();

// Set session with security information
session([
    'admin_logged_in' => true,
    'admin_user_id' => $user->id,
    'last_activity' => time(),
    'user_ip_address' => $request->ip(),
    'login_time' => time(),
    'session_fingerprint' => hash('sha256', $request->ip() . $request->userAgent())
]);
```

#### การปรับปรุง Logout:
```php
// Log logout activity
Log::info('User logout', [
    'user_id' => session('admin_user_id'),
    'session_duration' => time() - session('login_time')
]);

// Clear all session data
session()->forget([...]);
session()->invalidate();
```

## Environment Variables สำหรับ Session Security

### Development (.env)
```bash
# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=30
SESSION_ENCRYPT=true
SESSION_CONNECTION=mysql
SESSION_TABLE=laravel_sessions
SESSION_SECURE_COOKIE=false  # false for local development
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax  # lax for development
```

### Production (.env)
```bash
# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=30
SESSION_ENCRYPT=true
SESSION_CONNECTION=mysql
SESSION_TABLE=laravel_sessions
SESSION_SECURE_COOKIE=true   # true for HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict     # strict for production
```

## ความปลอดภัยที่เพิ่มขึ้น

### 1. **Session Hijacking Prevention**
- เก็บ IP address และ User Agent ใน session
- ตรวจสอบการเปลี่ยน IP address
- ใช้ session fingerprinting

### 2. **Session Fixation Prevention**
- Regenerate session ID หลัง login/logout
- Regenerate session ID เมื่อ timeout
- ใช้ secure session cookies

### 3. **Session Management**
- Session timeout ที่สั้นลง (30 นาที)
- Session encryption
- Database storage แทน file storage
- Secure cookie settings

### 4. **Monitoring และ Logging**
- บันทึก session timeout events
- บันทึก suspicious activity
- บันทึก login/logout activities
- บันทึก session duration

## การทดสอบ Session Security

### 1. **ทดสอบ Session Timeout**
```php
// ตั้งค่า session lifetime เป็น 1 นาที
SESSION_LIFETIME=1

// รอ 2 นาทีแล้วลองเข้าถึง protected route
// ควรถูก redirect ไปหน้า login
```

### 2. **ทดสอบ Session Hijacking**
```php
// Login จาก IP A
// เปลี่ยน IP เป็น B
// ควรเห็น warning log และ session ยังใช้งานได้
```

### 3. **ทดสอบ Session Regeneration**
```php
// ตรวจสอบ session ID ก่อนและหลัง login
$sessionIdBefore = session()->getId();
// ... login process ...
$sessionIdAfter = session()->getId();
// session ID ควรเปลี่ยน
```

## คำแนะนำเพิ่มเติม

### 1. **Production Deployment**
- ตั้งค่า `SESSION_SECURE_COOKIE=true` สำหรับ HTTPS
- ตั้งค่า `SESSION_SAME_SITE=strict` สำหรับความปลอดภัยสูงสุด
- ใช้ Redis หรือ Memcached สำหรับ session storage ใน production

### 2. **Monitoring**
- ตรวจสอบ log files เป็นประจำ
- ตั้งค่า alert สำหรับ suspicious activity
- Monitor session timeout patterns

### 3. **Performance**
- ใช้ database indexes สำหรับ session queries
- พิจารณาใช้ Redis สำหรับ session storage
- Cleanup old sessions เป็นประจำ

## การแก้ไขปัญหา

### 1. **Session ไม่ทำงาน**
```bash
# ตรวจสอบ database connection
php artisan migrate:status

# ตรวจสอบ session table
php artisan tinker
>>> DB::table('laravel_sessions')->count()
```

### 2. **Session Timeout เร็วเกินไป**
```bash
# ปรับค่า SESSION_LIFETIME ใน .env
SESSION_LIFETIME=60  # 60 นาที
```

### 3. **HTTPS Issues**
```bash
# สำหรับ development
SESSION_SECURE_COOKIE=false

# สำหรับ production
SESSION_SECURE_COOKIE=true
```

## สรุป

การปรับปรุง Session Security นี้จะช่วยป้องกัน:
- ✅ Session Hijacking
- ✅ Session Fixation  
- ✅ Session Management Vulnerabilities
- ✅ Information Disclosure
- ✅ Session Timeout Issues

และยังคงความสามารถในการใช้งานและ performance ของแอปพลิเคชัน
