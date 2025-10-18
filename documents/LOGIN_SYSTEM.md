# ระบบ Login สำหรับ CMS Backend

## ภาพรวม
ระบบ login นี้ถูกสร้างขึ้นเพื่อป้องกันการเข้าถึง backend โดยไม่ได้รับอนุญาต ผู้ใช้ต้อง login ก่อนจึงจะสามารถเข้าถึงหน้า backend ได้

## ไฟล์ที่สร้างขึ้น

### 1. Views
- `resources/views/auth/login.blade.php` - หน้า login
- `resources/views/auth/forgot-password.blade.php` - หน้าลืมรหัสผ่าน

### 2. Controllers
- `app/Http/Controllers/Auth/AuthController.php` - Controller สำหรับจัดการ authentication

### 3. Routes
- เพิ่ม routes สำหรับ login, logout, และ forgot password ใน `routes/web.php`

### 4. Middleware
- เพิ่ม `auth` middleware ให้กับ backend routes เพื่อป้องกันการเข้าถึงโดยไม่ได้รับอนุญาต

## การใช้งาน

### 1. เข้าสู่ระบบ
- ไปที่ `/login`
- กรอกอีเมล์และรหัสผ่าน
- คลิก "เข้าสู่ระบบ"

### 2. ออกจากระบบ
- คลิกที่ avatar ของผู้ใช้ที่มุมขวาบน
- เลือก "ออกจากระบบ"

### 3. ลืมรหัสผ่าน
- คลิก "ลืมรหัสผ่าน?" ในหน้า login
- กรอกอีเมล์ของคุณ
- คลิก "ส่งลิงก์รีเซ็ตรหัสผ่าน"

## ข้อมูลผู้ใช้เริ่มต้น

หลังจากรัน seeder แล้ว สามารถใช้ข้อมูลต่อไปนี้:

- **อีเมล์**: admin@example.com
- **รหัสผ่าน**: password

## คุณสมบัติ

### 1. ความปลอดภัย
- Rate limiting (จำกัดการพยายาม login)
- บันทึก login attempts
- Audit logging
- Session management
- CSRF protection

### 2. UX/UI
- Responsive design
- Loading states
- Error handling
- Success messages
- Remember me functionality

### 3. การตรวจสอบสิทธิ์
- ตรวจสอบสถานะผู้ใช้ (active/inactive)
- ตรวจสอบการยืนยันอีเมล์
- บันทึก IP address และ user agent

## การตั้งค่าเพิ่มเติม

### 1. เปลี่ยนรหัสผ่านเริ่มต้น
```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'admin@example.com')->first();
$user->password = Hash::make('รหัสผ่านใหม่');
$user->save();
```

### 2. สร้างผู้ใช้ใหม่
```php
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$user = User::create([
    'name' => 'ชื่อผู้ใช้',
    'email' => 'email@example.com',
    'password' => Hash::make('รหัสผ่าน'),
    'first_name' => 'ชื่อ',
    'last_name' => 'นามสกุล',
    'is_active' => true,
    'email_verified_at' => now(),
]);

$adminRole = Role::where('name', 'admin')->first();
$user->roles()->attach($adminRole->id);
```

## การแก้ไขปัญหา

### 1. ไม่สามารถ login ได้
- ตรวจสอบว่าผู้ใช้มีอยู่ในฐานข้อมูล
- ตรวจสอบสถานะ is_active
- ตรวจสอบรหัสผ่าน

### 2. Session หมดอายุ
- ตรวจสอบการตั้งค่า session ใน `.env`
- ตรวจสอบการตั้งค่า SESSION_LIFETIME

### 3. CSRF Token Mismatch
- ตรวจสอบว่า form มี `@csrf` directive
- ตรวจสอบการตั้งค่า CSRF protection

## การพัฒนาต่อ

### 1. เพิ่มการยืนยันอีเมล์
- สร้างระบบส่งอีเมล์ยืนยัน
- เพิ่มหน้า verify email

### 2. เพิ่ม Two-Factor Authentication
- ใช้ SMS หรือ Authenticator app
- เพิ่มการตั้งค่าในหน้า profile

### 3. เพิ่มการจัดการ Session
- หน้าแสดง session ที่ active
- ฟีเจอร์ logout จากอุปกรณ์อื่น

## การทดสอบ

### 1. ทดสอบการ login
```bash
# รันเซิร์ฟเวอร์
php artisan serve

# เปิดเบราว์เซอร์ไปที่ http://localhost:8000/login
```

### 2. ทดสอบการป้องกัน ✅
- ลองเข้าถึง `/backend/dashboard` โดยไม่ login
- **ผลลัพธ์**: ถูก redirect ไปหน้า login อัตโนมัติ
- **สถานะ**: ✅ ทำงานถูกต้อง

### 3. ทดสอบการเข้าถึงหลัง login ✅
- Login ด้วย admin@example.com / password
- เข้าถึงหน้า backend ต่างๆ
- **ผลลัพธ์**: สามารถเข้าถึงได้โดยไม่ต้อง login ซ้ำ
- **สถานะ**: ✅ ทำงานถูกต้อง

### 4. ทดสอบการ logout ✅
- Login เข้าระบบ
- คลิก logout จาก dropdown menu
- **ผลลัพธ์**: ถูก redirect ไปหน้า login
- **สถานะ**: ✅ ทำงานถูกต้อง

### 5. ทดสอบการป้องกันทุกหน้า backend ✅
- ทุกหน้าใน `/backend/*` ถูกป้องกันด้วย `auth` middleware
- **ผลลัพธ์**: ไม่สามารถเข้าถึงได้โดยไม่ login
- **สถานะ**: ✅ ทำงานถูกต้อง

## หมายเหตุ

- ระบบนี้ใช้ Laravel's built-in authentication
- ข้อมูลผู้ใช้ถูกเก็บในตาราง `core_users`
- การตั้งค่าความปลอดภัยสามารถปรับได้ใน Security Settings
- Audit logs จะถูกบันทึกทุกครั้งที่มีการ login/logout
