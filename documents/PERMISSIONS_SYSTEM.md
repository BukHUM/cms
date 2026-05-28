# ระบบสิทธิ์การเข้าถึง (Permission System)

ระบบสิทธิ์การเข้าถึงที่สมบูรณ์สำหรับ Laravel CMS Backend

## ฟีเจอร์หลัก

### 1. Permission Management
- **CRUD Operations**: สร้าง, อ่าน, แก้ไข, ลบสิทธิ์
- **Group Management**: จัดกลุ่มสิทธิ์ตามหมวดหมู่
- **Status Toggle**: เปิด/ปิดใช้งานสิทธิ์
- **Bulk Actions**: ดำเนินการกับสิทธิ์หลายรายการพร้อมกัน

### 2. Views ที่สร้างขึ้น
- `resources/views/backend/permissions/index.blade.php` - รายการสิทธิ์ทั้งหมด
- `resources/views/backend/permissions/create.blade.php` - สร้างสิทธิ์ใหม่
- `resources/views/backend/permissions/edit.blade.php` - แก้ไขสิทธิ์
- `resources/views/backend/permissions/show.blade.php` - รายละเอียดสิทธิ์

### 3. Controller
- `app/Http/Controllers/PermissionController.php` - จัดการ CRUD operations

### 4. Middleware
- `app/Http/Middleware/CheckPermission.php` - ตรวจสอบสิทธิ์เดียว
- `app/Http/Middleware/CheckAnyPermission.php` - ตรวจสอบสิทธิ์ใดสิทธิ์หนึ่ง
- `app/Http/Middleware/CheckAllPermissions.php` - ตรวจสอบสิทธิ์ทั้งหมด

### 5. Model Enhancements
- `app/Models/Permission.php` - เพิ่ม helper methods
- `app/Models/User.php` - เพิ่ม permission checking methods
- `app/Models/Role.php` - เพิ่ม permission relationship

## การใช้งาน

### Routes
```php
// Permission Management Routes
Route::resource('permissions', PermissionController::class);
Route::patch('permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
Route::post('permissions/bulk-action', [PermissionController::class, 'bulkAction'])->name('permissions.bulk-action');
```

### Middleware Usage
```php
// ตรวจสอบสิทธิ์เดียว
Route::get('/admin', [AdminController::class, 'index'])->middleware('permission:admin.access');

// ตรวจสอบสิทธิ์ใดสิทธิ์หนึ่ง
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission.any:dashboard.view,dashboard.statistics');

// ตรวจสอบสิทธิ์ทั้งหมด
Route::get('/reports', [ReportController::class, 'index'])->middleware('permission.all:report.view,report.export');
```

### Model Methods
```php
// User Model
$user->hasPermission('user.create');
$user->hasAnyPermission(['user.create', 'user.edit']);
$user->hasAllPermissions(['user.create', 'user.edit', 'user.delete']);

// Permission Model
$permission->canBeDeleted();
$permission->isUsed();
$permission->role_count;

// Role Model
$role->hasPermission('user.create');
$role->hasAnyPermission(['user.create', 'user.edit']);
$role->hasAllPermissions(['user.create', 'user.edit']);
```

## Permissions ที่สร้างขึ้น

### User Management
- `user.view` - ดูข้อมูลผู้ใช้
- `user.create` - สร้างผู้ใช้
- `user.edit` - แก้ไขผู้ใช้
- `user.delete` - ลบผู้ใช้
- `user.toggle-status` - เปลี่ยนสถานะผู้ใช้

### Role Management
- `role.view` - ดูข้อมูลบทบาท
- `role.create` - สร้างบทบาท
- `role.edit` - แก้ไขบทบาท
- `role.delete` - ลบบทบาท
- `role.assign-permissions` - กำหนดสิทธิ์ให้บทบาท

### Permission Management
- `permission.view` - ดูข้อมูลสิทธิ์
- `permission.create` - สร้างสิทธิ์
- `permission.edit` - แก้ไขสิทธิ์
- `permission.delete` - ลบสิทธิ์
- `permission.toggle-status` - เปลี่ยนสถานะสิทธิ์

### Dashboard
- `dashboard.view` - ดู Dashboard
- `dashboard.statistics` - ดูสถิติ

### Settings
- `setting.view` - ดูการตั้งค่า
- `setting.edit` - แก้ไขการตั้งค่า

### Audit Logs
- `audit-log.view` - ดู Audit Log
- `audit-log.export` - ส่งออก Audit Log

### System Administration
- `system.admin` - ผู้ดูแลระบบ
- `system.backup` - สำรองข้อมูล
- `system.maintenance` - บำรุงรักษาระบบ

## การติดตั้ง

1. **รัน Migration** (ถ้ายังไม่ได้รัน):
```bash
php artisan migrate
```

2. **รัน Seeder**:
```bash
php artisan db:seed --class=PermissionSeeder
```

3. **เข้าถึงระบบ**:
- ไปที่ `http://localhost:8000/backend/permissions`
- เริ่มจัดการสิทธิ์การเข้าถึง

## หมายเหตุ

- ระบบนี้ใช้ตาราง `core_permissions` สำหรับเก็บข้อมูลสิทธิ์
- สิทธิ์จะถูกเชื่อมโยงกับบทบาทผ่านตาราง `core_role_permissions`
- ผู้ใช้จะได้รับสิทธิ์ผ่านบทบาทที่ได้รับมอบหมาย
- Middleware จะตรวจสอบสิทธิ์ก่อนเข้าถึงหน้าเว็บ
- ระบบรองรับทั้ง Web และ API requests
