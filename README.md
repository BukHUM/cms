# Laravel Backend API

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
<a href="https://github.com/BukHUM/corebackend"><img src="https://img.shields.io/badge/Version-1.0.0-blue.svg" alt="Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
</p>

## เกี่ยวกับโปรเจค

Laravel Backend API เป็นระบบ Backend ที่พัฒนาด้วย Laravel Framework สำหรับให้บริการ API endpoints ต่างๆ เพื่อรองรับการทำงานของแอปพลิเคชัน Frontend

## คุณสมบัติหลัก

- 🚀 **Laravel Framework 12.32.5** - เวอร์ชันล่าสุด
- 🗄️ **MySQL Database** - ฐานข้อมูลหลักพร้อม prefix `laravel_`
- 🔐 **Authentication System** - ระบบยืนยันตัวตน
- 📊 **Database Migrations** - จัดการโครงสร้างฐานข้อมูล
- 🔄 **Queue System** - ประมวลผลงานในพื้นหลัง
- 💾 **Cache System** - ระบบแคชข้อมูล
- 📝 **Session Management** - จัดการ session
- 🛡️ **Security Features** - ความปลอดภัยระดับสูง

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

- `laravel_users` - ข้อมูลผู้ใช้
- `laravel_sessions` - session data
- `laravel_cache` - cache data
- `laravel_jobs` - queue jobs
- `laravel_failed_jobs` - failed jobs
- `laravel_migrations` - migration tracking

## การพัฒนา

### สร้าง Migration ใหม่
```bash
php artisan make:migration create_your_table
```

### สร้าง Model
```bash
php artisan make:model YourModel
```

### สร้าง Controller
```bash
php artisan make:controller YourController
```

### รัน Tests
```bash
php artisan test
```

## API Documentation

API endpoints จะถูกสร้างขึ้นตามความต้องการของระบบ โดยจะมีการจัดกลุ่มตามฟีเจอร์ต่างๆ

### Authentication Endpoints
- `POST /api/auth/login` - เข้าสู่ระบบ
- `POST /api/auth/register` - สมัครสมาชิก
- `POST /api/auth/logout` - ออกจากระบบ
- `POST /api/auth/refresh` - รีเฟรช token

## การ Deploy

### Production Environment

1. **ตั้งค่า Environment**
```bash
cp .env.example .env.production
# แก้ไขการตั้งค่าสำหรับ production
```

2. **Optimize Application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Set Permissions**
```bash
chmod -R 755 storage bootstrap/cache
```

## การมีส่วนร่วม

หากต้องการมีส่วนร่วมในการพัฒนาโปรเจค กรุณาติดต่อทีมพัฒนา

## License

โปรเจคนี้อยู่ภายใต้ [MIT License](https://opensource.org/licenses/MIT)

## ติดต่อ

- **Repository**: [BukHUM/corebackend](https://github.com/BukHUM/corebackend)
- **Issues**: [GitHub Issues](https://github.com/BukHUM/corebackend/issues)

---

<p align="center">สร้างด้วย ❤️ โดยทีมพัฒนา BukHUM</p>
