# Performance Settings Module

## 📋 Overview
โมดูล Performance Settings สำหรับการจัดการการตั้งค่าประสิทธิภาพของระบบ CMS Backend

## 🏗️ Architecture

### Model
- **Performance** (`app/Models/Performance.php`)
  - จัดการข้อมูลการตั้งค่าประสิทธิภาพ
  - รองรับ Soft Deletes
  - มี Type Casting สำหรับข้อมูลประเภทต่างๆ
  - มี Validation Rules สำหรับตรวจสอบค่า

### Controller
- **PerformanceController** (`app/Http/Controllers/Backend/PerformanceController.php`)
  - จัดการ CRUD operations
  - รองรับการค้นหาและกรองข้อมูล
  - มีฟังก์ชัน Reset และ Bulk Update
  - รองรับการ Export ข้อมูล

### Views
- **index.blade.php** - รายการการตั้งค่าประสิทธิภาพ
- **create.blade.php** - สร้างการตั้งค่าใหม่
- **edit.blade.php** - แก้ไขการตั้งค่า
- **show.blade.php** - ดูรายละเอียดการตั้งค่า

## 🎯 Features

### 1. การจัดการการตั้งค่า
- ✅ สร้างการตั้งค่าใหม่
- ✅ แก้ไขการตั้งค่าที่มีอยู่
- ✅ ลบการตั้งค่า
- ✅ รีเซ็ตเป็นค่าเริ่มต้น
- ✅ เปิด/ปิดใช้งานการตั้งค่า

### 2. ประเภทข้อมูล
- **String** - ข้อความ
- **Integer** - ตัวเลขเต็ม
- **Float** - ตัวเลขทศนิยม
- **Boolean** - ค่าจริง/เท็จ
- **Array** - อาร์เรย์
- **JSON** - ข้อมูล JSON

### 3. หมวดหมู่การตั้งค่า
- **Cache Settings** - การตั้งค่า Cache
- **Database Settings** - การตั้งค่าฐานข้อมูล
- **Memory Settings** - การตั้งค่าหน่วยความจำ
- **Session Settings** - การตั้งค่า Session
- **Queue Settings** - การตั้งค่า Queue
- **Logging Settings** - การตั้งค่า Logging
- **Optimization Settings** - การตั้งค่าการปรับปรุงประสิทธิภาพ

### 4. ฟีเจอร์เพิ่มเติม
- ✅ การค้นหาและกรองข้อมูล
- ✅ การเรียงลำดับข้อมูล
- ✅ การ Export ข้อมูลเป็น CSV
- ✅ การตรวจสอบความถูกต้องของข้อมูล
- ✅ การบันทึก Audit Log
- ✅ การจัดการสิทธิ์การเข้าถึง

## 🔧 Installation

### 1. Migration
```bash
php artisan migrate
```

### 2. Seeder
```bash
php artisan db:seed --class=PerformanceSeeder
```

### 3. Permissions
```bash
php artisan db:seed --class=PermissionSeeder
```

## 📊 Database Schema

### Table: `core_performance_settings`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | varchar(255) | ชื่อการตั้งค่า |
| key | varchar(255) | คีย์การตั้งค่า (Unique) |
| value | text | ค่าการตั้งค่า |
| type | enum | ประเภทข้อมูล |
| description | text | คำอธิบาย |
| is_active | boolean | สถานะการใช้งาน |
| category | varchar(255) | หมวดหมู่ |
| sort_order | integer | ลำดับการเรียง |
| validation_rules | json | กฎการตรวจสอบ |
| default_value | text | ค่าเริ่มต้น |
| options | json | ตัวเลือก |
| created_by | bigint | ผู้สร้าง |
| updated_by | bigint | ผู้แก้ไขล่าสุด |
| created_at | timestamp | วันที่สร้าง |
| updated_at | timestamp | วันที่แก้ไขล่าสุด |
| deleted_at | timestamp | วันที่ลบ (Soft Delete) |

## 🛣️ Routes

### Web Routes
```php
// Performance Settings Routes
Route::resource('settings-performance', PerformanceController::class);
Route::post('settings-performance/{performance}/reset', [PerformanceController::class, 'reset'])->name('settings-performance.reset');
Route::post('settings-performance/bulk-update', [PerformanceController::class, 'bulkUpdate'])->name('settings-performance.bulk-update');
Route::get('settings-performance-export', [PerformanceController::class, 'export'])->name('settings-performance.export');
```

### Available Routes
- `GET /backend/settings-performance` - รายการการตั้งค่า
- `GET /backend/settings-performance/create` - สร้างการตั้งค่าใหม่
- `POST /backend/settings-performance` - บันทึกการตั้งค่าใหม่
- `GET /backend/settings-performance/{id}` - ดูรายละเอียด
- `GET /backend/settings-performance/{id}/edit` - แก้ไขการตั้งค่า
- `PUT /backend/settings-performance/{id}` - อัพเดตการตั้งค่า
- `DELETE /backend/settings-performance/{id}` - ลบการตั้งค่า
- `POST /backend/settings-performance/{id}/reset` - รีเซ็ตการตั้งค่า
- `POST /backend/settings-performance/bulk-update` - อัพเดตหลายรายการ
- `GET /backend/settings-performance-export` - ส่งออกข้อมูล

## 🔐 Permissions

### Required Permissions
- `performance.view` - ดูการตั้งค่าประสิทธิภาพ
- `performance.create` - สร้างการตั้งค่าประสิทธิภาพ
- `performance.edit` - แก้ไขการตั้งค่าประสิทธิภาพ
- `performance.delete` - ลบการตั้งค่าประสิทธิภาพ

## 📝 Usage Examples

### 1. การสร้างการตั้งค่าใหม่
```php
$performance = new Performance();
$performance->name = 'Cache TTL';
$performance->key = 'performance.cache.ttl';
$performance->value = '3600';
$performance->type = 'integer';
$performance->category = 'cache';
$performance->save();
```

### 2. การดึงข้อมูลการตั้งค่า
```php
// ดึงการตั้งค่าตามหมวดหมู่
$cacheSettings = Performance::byCategory('cache')->active()->get();

// ดึงการตั้งค่าตามคีย์
$cacheTtl = Performance::where('key', 'performance.cache.ttl')->first();
$value = $cacheTtl->typed_value; // ได้ค่าเป็น integer
```

### 3. การตรวจสอบความถูกต้อง
```php
$performance = Performance::find(1);
$isValid = $performance->validateValue('7200'); // true/false
```

## 🎨 UI Components

### ใช้ตามมาตรฐาน UI Standard
- ✅ Tailwind CSS utility classes
- ✅ Custom Components (btn-primary, card, form-input, table)
- ✅ Font Awesome icons
- ✅ Responsive design
- ✅ Mobile-friendly interface

### Features
- ✅ Dynamic form inputs based on data type
- ✅ Real-time validation
- ✅ SweetAlert2 for confirmations
- ✅ Export functionality
- ✅ Search and filter
- ✅ Pagination

## 🔍 API Endpoints

### Performance Settings API
```php
// Get all performance settings
GET /api/performance-settings

// Get performance setting by ID
GET /api/performance-settings/{id}

// Create new performance setting
POST /api/performance-settings

// Update performance setting
PUT /api/performance-settings/{id}

// Delete performance setting
DELETE /api/performance-settings/{id}
```

## 📈 Performance Monitoring

### Built-in Settings
- Cache configuration
- Database optimization
- Memory management
- Session handling
- Queue processing
- Logging levels
- Compression settings

### Monitoring Features
- Real-time performance metrics
- System resource usage
- Query performance tracking
- Cache hit/miss ratios
- Memory usage monitoring

## 🚀 Future Enhancements

### Planned Features
- [ ] Real-time performance monitoring dashboard
- [ ] Automated performance optimization suggestions
- [ ] Performance benchmarking tools
- [ ] Integration with external monitoring services
- [ ] Performance alerts and notifications
- [ ] Historical performance data analysis

## 📚 Documentation

### Related Documentation
- [UI Standard Documentation](../documents/UI_STANDARD.md)
- [Audit Log System](../documents/AUDIT_LOG_SYSTEM.md)
- [Permissions System](../documents/PERMISSIONS_SYSTEM.md)

### External Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Font Awesome Documentation](https://fontawesome.com/docs)

---

**Last Updated**: 2024-10-18  
**Version**: 1.0.0  
**Author**: CMS Backend Team
