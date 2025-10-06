# ระบบการจัดการผู้ใช้และสิทธิ์การเข้าถึง

ระบบนี้ได้ถูกพัฒนาขึ้นเพื่อจัดการผู้ใช้และสิทธิ์การเข้าถึงอย่างสมบูรณ์ โดยใช้ Laravel Framework และระบบสิทธิ์แบบ Role-Based Access Control (RBAC)

## คุณสมบัติหลัก

### 1. ระบบผู้ใช้ (Users)
- จัดการข้อมูลผู้ใช้ (เพิ่ม, แก้ไข, ลบ, ดู)
- สถานะผู้ใช้ (ใช้งาน, ไม่ใช้งาน, รอการยืนยัน, ระงับการใช้งาน)
- รองรับการอัปโหลดรูปโปรไฟล์
- ระบบค้นหาและกรองข้อมูล
- ส่งออกข้อมูลเป็น CSV

### 2. ระบบบทบาท (Roles)
- จัดการบทบาทต่างๆ ในระบบ
- กำหนดสีและคำอธิบายสำหรับแต่ละบทบาท
- จัดการสิทธิ์ที่เกี่ยวข้องกับแต่ละบทบาท
- ระบบลำดับการแสดงผล

### 3. ระบบสิทธิ์ (Permissions)
- จัดการสิทธิ์การเข้าถึงแบบละเอียด
- จัดกลุ่มสิทธิ์ตามหมวดหมู่
- รองรับการกำหนดการกระทำและทรัพยากร
- ระบบเปิด/ปิดการใช้งานสิทธิ์

## โครงสร้างฐานข้อมูล

### ตารางหลัก
- `laravel_users` - ข้อมูลผู้ใช้
- `laravel_roles` - ข้อมูลบทบาท
- `laravel_permissions` - ข้อมูลสิทธิ์
- `laravel_user_roles` - ความสัมพันธ์ระหว่างผู้ใช้และบทบาท
- `laravel_role_permissions` - ความสัมพันธ์ระหว่างบทบาทและสิทธิ์

### ฟิลด์สำคัญ
- **Users**: name, email, password, phone, avatar, status, last_login_at
- **Roles**: name, slug, description, color, is_active, sort_order
- **Permissions**: name, slug, description, group, action, resource, is_active, sort_order

## บทบาทเริ่มต้น

### 1. Super Admin
- สิทธิ์สูงสุดในระบบ
- สามารถจัดการทุกอย่างได้
- สี: แดง (#dc3545)

### 2. Admin
- จัดการผู้ใช้และระบบ
- ไม่สามารถจัดการสิทธิ์ได้
- สี: ส้ม (#fd7e14)

### 3. Moderator
- จัดการเนื้อหาและผู้ใช้ทั่วไป
- สิทธิ์จำกัด
- สี: เขียว (#20c997)

### 4. User
- ผู้ใช้ทั่วไป
- สิทธิ์พื้นฐาน
- สี: เทา (#6c757d)

## สิทธิ์เริ่มต้น

### ระบบผู้ใช้
- `users.manage` - จัดการผู้ใช้ทั้งหมด
- `users.view` - ดูข้อมูลผู้ใช้
- `users.create` - เพิ่มผู้ใช้ใหม่
- `users.update` - แก้ไขข้อมูลผู้ใช้
- `users.delete` - ลบผู้ใช้

### ระบบบทบาทและสิทธิ์
- `roles.manage` - จัดการบทบาททั้งหมด
- `permissions.manage` - จัดการสิทธิ์ทั้งหมด

### ระบบการตั้งค่า
- `settings.manage` - จัดการการตั้งค่าระบบ

### ระบบ Audit Log
- `audit.view` - ดู Audit Log
- `audit.manage` - จัดการ Audit Log

### ระบบ Dashboard
- `dashboard.access` - เข้าถึง Dashboard

## การใช้งาน

### 1. เข้าสู่ระบบ
```
อีเมล: superadmin@example.com
รหัสผ่าน: password
```

### 2. จัดการผู้ใช้
- เข้าถึง: `/admin/users`
- เพิ่มผู้ใช้ใหม่พร้อมกำหนดบทบาท
- แก้ไขข้อมูลผู้ใช้และบทบาท
- เปลี่ยนสถานะผู้ใช้
- ส่งออกข้อมูลผู้ใช้

### 3. จัดการบทบาท
- เข้าถึง: `/admin/roles`
- สร้างบทบาทใหม่พร้อมกำหนดสิทธิ์
- แก้ไขข้อมูลบทบาทและสิทธิ์
- จัดการสิทธิ์สำหรับแต่ละบทบาท

### 4. จัดการสิทธิ์
- เข้าถึง: `/admin/permissions`
- สร้างสิทธิ์ใหม่
- จัดกลุ่มสิทธิ์ตามหมวดหมู่
- แก้ไขข้อมูลสิทธิ์

## Middleware

### CheckPermission
ตรวจสอบสิทธิ์การเข้าถึง
```php
Route::middleware(['auth', 'permission:users.manage'])->group(function () {
    // Routes ที่ต้องการสิทธิ์ users.manage
});
```

### CheckRole
ตรวจสอบบทบาท
```php
Route::middleware(['auth', 'role:admin,super-admin'])->group(function () {
    // Routes ที่ต้องการบทบาท admin หรือ super-admin
});
```

## Helper Methods

### User Model
```php
// ตรวจสอบบทบาท
$user->hasRole('admin');
$user->hasAnyRole(['admin', 'moderator']);

// ตรวจสอบสิทธิ์
$user->hasPermission('users.create');
$user->hasAnyPermission(['users.create', 'users.update']);

// จัดการบทบาท
$user->assignRole($role);
$user->removeRole($role);
$user->syncRoles([1, 2, 3]);

// ตรวจสอบสถานะ
$user->isAdmin();
$user->isSuperAdmin();
```

### Role Model
```php
// ตรวจสอบสิทธิ์
$role->hasPermission('users.create');

// จัดการสิทธิ์
$role->givePermissionTo($permission);
$role->revokePermissionTo($permission);
$role->syncPermissions([1, 2, 3]);
```

## Blade Directives

### ตรวจสอบสิทธิ์
```blade
@can('users.create')
    <button>เพิ่มผู้ใช้</button>
@endcan

@cannot('users.delete')
    <p>คุณไม่มีสิทธิ์ลบผู้ใช้</p>
@endcannot
```

### ตรวจสอบบทบาท
```blade
@role('admin')
    <p>คุณเป็น Admin</p>
@endrole

@hasrole('admin')
    <p>คุณมีบทบาท Admin</p>
@endhasrole
```

## การตั้งค่า

### 1. เปิดใช้งาน Middleware
เพิ่มใน `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    'permission' => \App\Http\Middleware\CheckPermission::class,
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

### 2. กำหนดค่าใน Service Provider
เพิ่มใน `app/Providers/AppServiceProvider.php`:
```php
use Illuminate\Support\Facades\Gate;

public function boot()
{
    Gate::before(function ($user, $ability) {
        if ($user->isSuperAdmin()) {
            return true;
        }
    });
}
```

## การทดสอบ

### 1. รัน Migration
```bash
php artisan migrate
```

### 2. รัน Seeder
```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminUserSeeder
```

### 3. ทดสอบการเข้าถึง
- เข้าสู่ระบบด้วยบัญชี Super Admin
- ทดสอบการจัดการผู้ใช้
- ทดสอบการจัดการบทบาทและสิทธิ์

## การบำรุงรักษา

### 1. สำรองข้อมูล
```bash
php artisan backup:run
```

### 2. ล้างข้อมูลเก่า
```bash
php artisan audit:cleanup
```

### 3. ตรวจสอบสิทธิ์
```bash
php artisan permission:check
```

## การพัฒนาต่อ

### 1. เพิ่มสิทธิ์ใหม่
- สร้าง Permission ใหม่ในฐานข้อมูล
- เพิ่มใน Seeder
- อัปเดต Controllers และ Views

### 2. เพิ่มบทบาทใหม่
- สร้าง Role ใหม่ในฐานข้อมูล
- กำหนดสิทธิ์ที่เกี่ยวข้อง
- อัปเดต UI สำหรับการเลือกบทบาท

### 3. เพิ่มฟีเจอร์ใหม่
- ใช้ระบบสิทธิ์ที่มีอยู่
- เพิ่ม Middleware ตามความจำเป็น
- อัปเดต Views และ Controllers

## การแก้ไขปัญหา

### 1. ปัญหาสิทธิ์ไม่ทำงาน
- ตรวจสอบ Middleware registration
- ตรวจสอบการกำหนดสิทธิ์ในฐานข้อมูล
- ตรวจสอบการเข้าสู่ระบบ

### 2. ปัญหาการแสดงผล
- ตรวจสอบ Blade directives
- ตรวจสอบการส่งข้อมูลจาก Controller
- ตรวจสอบ CSS และ JavaScript

### 3. ปัญหาฐานข้อมูล
- ตรวจสอบ Migration status
- ตรวจสอบ Foreign Key constraints
- ตรวจสอบ Index และ Performance

## การอัปเดต

### 1. อัปเดตสิทธิ์
- เพิ่มสิทธิ์ใหม่ใน Seeder
- อัปเดตการกำหนดสิทธิ์ให้บทบาท
- ทดสอบการทำงาน

### 2. อัปเดตบทบาท
- เพิ่มบทบาทใหม่
- กำหนดสิทธิ์ที่เหมาะสม
- อัปเดต UI

### 3. อัปเดตระบบ
- Backup ข้อมูลก่อนอัปเดต
- รัน Migration ใหม่
- ทดสอบการทำงานทั้งหมด

---

ระบบนี้ได้รับการออกแบบให้มีความยืดหยุ่นและขยายได้ง่าย สามารถปรับแต่งและเพิ่มฟีเจอร์ใหม่ได้ตามความต้องการของแต่ละโปรเจ็กต์
