# Laravel Backend API

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
<a href="https://github.com/BukHUM/corebackend"><img src="https://img.shields.io/badge/Version-1.1.0-blue.svg" alt="Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
</p>

## เกี่ยวกับโปรเจค

Laravel Backend API เป็นระบบ Backend ที่พัฒนาด้วย Laravel Framework สำหรับให้บริการ API endpoints ต่างๆ เพื่อรองรับการทำงานของแอปพลิเคชัน Frontend พร้อมระบบจัดการผู้ใช้ การตั้งค่า และการตรวจสอบความปลอดภัย

## คุณสมบัติหลัก

### 🚀 **Core Features**
- **Laravel Framework 12.32.5** - เวอร์ชันล่าสุด
- **MySQL Database** - ฐานข้อมูลหลักพร้อม prefix `laravel_`
- **Authentication System** - ระบบยืนยันตัวตนพร้อมความปลอดภัยสูง
- **Role & Permission System** - ระบบจัดการสิทธิ์และบทบาทผู้ใช้
- **Settings Management** - ระบบจัดการการตั้งค่าทั้งระบบ
- **Audit Logging** - บันทึกการใช้งานและกิจกรรมต่างๆ

### ⚡ **Performance Features**
- **Database Indexes** - เพิ่มความเร็วในการค้นหาข้อมูล
- **Caching System** - ระบบแคชข้อมูลที่ใช้บ่อย
- **Query Optimization** - ปรับปรุงการทำงานของฐานข้อมูล
- **Pagination Support** - รองรับข้อมูลจำนวนมาก
- **Performance Monitoring** - เครื่องมือตรวจสอบประสิทธิภาพ

### 🛡️ **Security Features**
- **Security Headers** - CSP, X-Frame-Options, X-XSS-Protection
- **Login Security** - ป้องกัน brute force attack
- **Session Management** - จัดการ session อย่างปลอดภัย
- **Input Validation** - ตรวจสอบข้อมูลนำเข้า
- **Audit Trail** - ติดตามการใช้งานระบบ

### 📊 **Management Features**
- **User Management** - จัดการผู้ใช้ บทบาท และสิทธิ์
- **Settings Configuration** - ตั้งค่าระบบแบบครบวงจร
- **System Monitoring** - ตรวจสอบสถานะระบบ
- **Backup Management** - จัดการการสำรองข้อมูล
- **Email Configuration** - ตั้งค่าระบบอีเมล

## การติดตั้ง

### ความต้องการของระบบ

- PHP >= 8.1
- Composer
- MySQL >= 5.7 หรือ MariaDB >= 10.2
- Node.js & NPM (สำหรับ frontend assets)

### ขั้นตอนการติดตั้ง

1. **Clone Repository**
```bash
git clone https://github.com/BukHUM/corebackend.git
cd corebackend
```

2. **ติดตั้ง Dependencies**
```bash
composer install
npm install
npm run build
```

3. **ตั้งค่าสภาพแวดล้อม**
```bash
cp .env.example .env
php artisan key:generate
```

4. **ตั้งค่าฐานข้อมูล**
แก้ไขไฟล์ `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_backend
DB_USERNAME=root
DB_PASSWORD=
```

5. **สร้างฐานข้อมูล**
```bash
mysql -u root -e "CREATE DATABASE laravel_backend CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

6. **รัน Migrations**
```bash
php artisan migrate
```

7. **เริ่มเซิร์ฟเวอร์**
```bash
php artisan serve
```

## โครงสร้างฐานข้อมูล

ระบบใช้ prefix `laravel_` สำหรับตารางของ Laravel เพื่อแยกแยะจากตารางของแอปพลิเคชัน:

### 📊 **Core Tables**
- `laravel_users` - ข้อมูลผู้ใช้และโปรไฟล์
- `laravel_roles` - บทบาทผู้ใช้
- `laravel_permissions` - สิทธิ์การใช้งาน
- `laravel_user_roles` - ความสัมพันธ์ผู้ใช้-บทบาท
- `laravel_role_permissions` - ความสัมพันธ์บทบาท-สิทธิ์

### 🔍 **System Tables**
- `laravel_settings` - การตั้งค่าระบบ
- `laravel_audit_logs` - บันทึกการใช้งาน
- `laravel_login_attempts` - บันทึกการเข้าสู่ระบบ
- `laravel_sessions` - ข้อมูล session
- `laravel_cache` - ข้อมูล cache
- `laravel_jobs` - queue jobs
- `laravel_failed_jobs` - failed jobs
- `laravel_migrations` - migration tracking

## การพัฒนา

### 🛠️ **Artisan Commands**

#### **Performance Management**
```bash
# ตรวจสอบประสิทธิภาพฐานข้อมูล
php artisan performance:monitor check

# ปรับปรุงประสิทธิภาพฐานข้อมูล
php artisan performance:monitor optimize

# สร้างรายงานประสิทธิภาพ
php artisan performance:monitor report
```

#### **Cache Management**
```bash
# ล้าง cache ทั้งหมด
php artisan cache:manage clear --type=all

# Warm cache สำหรับข้อมูลที่ใช้บ่อย
php artisan cache:manage warm --type=all

# ดูสถิติ cache
php artisan cache:manage stats
```

#### **Standard Commands**
```bash
# สร้าง Migration ใหม่
php artisan make:migration create_your_table

# สร้าง Model
php artisan make:model YourModel

# สร้าง Controller
php artisan make:controller YourController

# รัน Tests
php artisan test
```

### 📁 **Project Structure**
```
app/
├── Console/Commands/          # Artisan Commands
├── Http/Controllers/          # Controllers
├── Http/Middleware/           # Middleware
├── Models/                    # Eloquent Models
├── Services/                  # Business Logic Services
└── Helpers/                   # Helper Classes

database/
├── migrations/                # Database Migrations
└── seeders/                   # Database Seeders

resources/
├── views/                     # Blade Templates
└── css/                       # Stylesheets

public/
├── js/                        # JavaScript Files
└── build/                     # Compiled Assets
```

## API Documentation

### 🔐 **Authentication Endpoints**
- `POST /admin/login` - เข้าสู่ระบบ Admin
- `POST /logout` - ออกจากระบบ
- `GET /login` - หน้าเข้าสู่ระบบ

### 👥 **User Management Endpoints**
- `GET /admin/user-management` - จัดการผู้ใช้
- `GET /admin/api/user-management/users` - ข้อมูลผู้ใช้ (API)
- `POST /admin/api/user-management/users` - สร้างผู้ใช้ใหม่
- `PUT /admin/api/user-management/users/{id}` - แก้ไขผู้ใช้
- `DELETE /admin/api/user-management/users/{id}` - ลบผู้ใช้

### ⚙️ **Settings Endpoints**
- `GET /admin/settings` - หน้าตั้งค่า
- `GET /admin/settings/general` - การตั้งค่าทั่วไป
- `POST /admin/settings/general` - บันทึกการตั้งค่าทั่วไป
- `GET /admin/settings/security` - การตั้งค่าความปลอดภัย
- `POST /admin/settings/security` - บันทึกการตั้งค่าความปลอดภัย
- `GET /admin/settings/email` - การตั้งค่าอีเมล
- `POST /admin/settings/email` - บันทึกการตั้งค่าอีเมล

### 📊 **Performance Endpoints**
- `GET /admin/api/performance/metrics` - ข้อมูลประสิทธิภาพ
- `GET /admin/api/performance/slow-queries` - Slow queries
- `GET /admin/api/performance/table-statistics` - สถิติตาราง

### 🔍 **Audit Endpoints**
- `GET /admin/api/audit/logs` - บันทึกการใช้งาน
- `GET /admin/api/audit/statistics` - สถิติการใช้งาน
- `POST /admin/api/audit/export` - ส่งออกข้อมูล audit

## การ Deploy

### 🚀 **Production Environment**

#### **1. ตั้งค่า Environment**
```bash
cp .env.example .env.production
# แก้ไขการตั้งค่าสำหรับ production
```

#### **2. ติดตั้งและ Optimize**
```bash
# ติดตั้ง dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# รัน migrations
php artisan migrate --force

# Warm cache
php artisan cache:manage warm --type=all
```

#### **3. ตั้งค่า Permissions**
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### **4. ตรวจสอบ Performance**
```bash
php artisan performance:monitor check
php artisan performance:monitor optimize
```

### 🔧 **Maintenance Commands**
```bash
# ล้าง cache เมื่อต้องการ
php artisan cache:manage clear --type=all

# ตรวจสอบสถานะระบบ
php artisan performance:monitor report

# สำรองข้อมูล
php artisan backup:run
```

## 📈 **Performance & Monitoring**

### ⚡ **Performance Features**
- **Database Indexes** - เพิ่มความเร็วในการค้นหา 30-50%
- **Caching System** - ลดการใช้ทรัพยากรและเพิ่มความเร็ว
- **Query Optimization** - ลด N+1 queries และปรับปรุงประสิทธิภาพ
- **Pagination** - รองรับข้อมูลจำนวนมากอย่างมีประสิทธิภาพ

### 📊 **Monitoring Tools**
- **Performance Monitor** - ตรวจสอบประสิทธิภาพฐานข้อมูล
- **Cache Management** - จัดการ cache อย่างมีประสิทธิภาพ
- **Audit Logging** - ติดตามการใช้งานและกิจกรรม
- **System Statistics** - สถิติการใช้งานระบบ

### 🔧 **Maintenance**
```bash
# ตรวจสอบประสิทธิภาพ
php artisan performance:monitor check

# ปรับปรุงประสิทธิภาพ
php artisan performance:monitor optimize

# จัดการ cache
php artisan cache:manage warm --type=all
```

## 🤝 **การมีส่วนร่วม**

หากต้องการมีส่วนร่วมในการพัฒนาโปรเจค กรุณาติดต่อทีมพัฒนา

## 📄 **License**

โปรเจคนี้อยู่ภายใต้ [MIT License](https://opensource.org/licenses/MIT)

## 📞 **ติดต่อ**

- **Repository**: [BukHUM/corebackend](https://github.com/BukHUM/corebackend)
- **Issues**: [GitHub Issues](https://github.com/BukHUM/corebackend/issues)

---

<p align="center">สร้างด้วย ❤️ โดยทีมพัฒนา BukHUM</p>
