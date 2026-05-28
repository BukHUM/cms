# ระบบ Settings Update สำหรับ Laravel Core

## 📋 ภาพรวม
ระบบ Settings Update เป็นระบบจัดการการอัพเดต Laravel Core, Packages และ Configuration ที่ออกแบบตาม UI Standard และมีฟีเจอร์ครบครันสำหรับการจัดการการอัพเดตระบบ

## 🏗️ โครงสร้างระบบ

### 1. Model: SettingsUpdate
**ไฟล์**: `app/Models/SettingsUpdate.php`

**ฟีเจอร์หลัก**:
- **Relationships**: เชื่อมโยงกับ User (creator, updater)
- **Scopes**: pending, inProgress, completed, failed, byType
- **Accessors**: statusBadge, statusText, updateTypeText
- **Methods**: canStart, canCancel, canRetry, startUpdate, completeUpdate, failUpdate, cancelUpdate

**ตาราง**: `core_settings_updates`
```sql
- id (Primary Key)
- update_type (core, package, config)
- component_name
- current_version
- target_version
- description
- changelog
- dependencies (JSON)
- backup_files (JSON)
- status (pending, in_progress, completed, failed, cancelled)
- error_message
- execution_log (JSON)
- scheduled_at
- started_at
- completed_at
- created_by (Foreign Key)
- updated_by (Foreign Key)
- timestamps
```

### 2. Controller: SettingsUpdateController
**ไฟล์**: `app/Http/Controllers/Backend/SettingsUpdateController.php`

**ฟีเจอร์หลัก**:
- **CRUD Operations**: index, create, store, show, edit, update, destroy
- **Update Management**: start, cancel, retry
- **Execution Methods**: updateLaravelCore, updatePackage, updateConfig
- **Audit Logging**: บันทึกทุกการดำเนินการ
- **Error Handling**: จัดการ error และ logging

**Methods**:
```php
- index() - แสดงรายการการอัพเดตพร้อม filters และ statistics
- create() - แสดงฟอร์มสร้างการอัพเดตใหม่
- store() - บันทึกการอัพเดตใหม่
- show() - แสดงรายละเอียดการอัพเดต
- edit() - แสดงฟอร์มแก้ไขการอัพเดต
- update() - บันทึกการแก้ไข
- destroy() - ลบการอัพเดต
- start() - เริ่มการอัพเดต
- cancel() - ยกเลิกการอัพเดต
- retry() - ลองใหม่
- executeUpdate() - ดำเนินการอัพเดตจริง
- updateLaravelCore() - อัพเดต Laravel Core
- updatePackage() - อัพเดต Package
- updateConfig() - อัพเดต Configuration
```

### 3. Middleware: SettingsUpdateAccess
**ไฟล์**: `app/Http/Middleware/SettingsUpdateAccess.php`

**ฟีเจอร์ความปลอดภัย**:
- **Authentication Check**: ตรวจสอบการเข้าสู่ระบบ
- **Permission Check**: ตรวจสอบสิทธิ์ admin หรือ settings_update_manage
- **IP Whitelist**: ตรวจสอบ IP ใน production
- **Session Validation**: ตรวจสอบเซสชันใน production
- **Access Logging**: บันทึกการเข้าถึง

### 4. Routes
**ไฟล์**: `routes/web.php`

```php
// Settings Update Routes
Route::resource('settings-update', SettingsUpdateController::class)->middleware('settings.update.access');
Route::post('settings-update/{settingsUpdate}/start', [SettingsUpdateController::class, 'start'])->name('settings-update.start')->middleware('settings.update.access');
Route::post('settings-update/{settingsUpdate}/cancel', [SettingsUpdateController::class, 'cancel'])->name('settings-update.cancel')->middleware('settings.update.access');
Route::post('settings-update/{settingsUpdate}/retry', [SettingsUpdateController::class, 'retry'])->name('settings-update.retry')->middleware('settings.update.access');
```

### 5. Views
**โฟลเดอร์**: `resources/views/backend/settings-update/`

#### 5.1 Index Page (`index.blade.php`)
**ฟีเจอร์**:
- **Statistics Cards**: แสดงสถิติการอัพเดตทั้งหมด
- **Filters**: ค้นหา, กรองตามสถานะและประเภท
- **Data Table**: แสดงรายการการอัพเดตพร้อม pagination
- **Action Buttons**: เริ่ม, ยกเลิก, ลองใหม่, แก้ไข, ลบ
- **Auto-refresh**: รีเฟรชอัตโนมัติสำหรับการอัพเดตที่กำลังดำเนินการ

#### 5.2 Create Page (`create.blade.php`)
**ฟีเจอร์**:
- **Form Sections**: ข้อมูลพื้นฐาน, รายละเอียด, กำหนดเวลา
- **Auto-fill**: กรอกข้อมูลอัตโนมัติตามประเภทการอัพเดต
- **Validation**: ตรวจสอบข้อมูลก่อนส่ง
- **Confirmation**: ยืนยันก่อนสร้าง

#### 5.3 Show Page (`show.blade.php`)
**ฟีเจอร์**:
- **Information Display**: แสดงข้อมูลครบถ้วน
- **Timeline**: แสดงประวัติการดำเนินการ
- **Execution Log**: แสดง log การทำงาน
- **Error Display**: แสดงข้อความ error
- **Action Panel**: ปุ่มดำเนินการต่างๆ
- **Dependencies & Backup**: แสดง dependencies และไฟล์ที่ backup

#### 5.4 Edit Page (`edit.blade.php`)
**ฟีเจอร์**:
- **Form Pre-fill**: กรอกข้อมูลเดิม
- **Status Check**: ตรวจสอบสถานะก่อนแก้ไข
- **Validation**: ตรวจสอบข้อมูล
- **Confirmation**: ยืนยันก่อนบันทึก

## 🎨 UI Design ตาม UI Standard

### 1. Layout Components
- **ใช้ Tailwind Classes**: `card`, `card-header`, `card-body`, `btn-primary`, `btn-secondary`
- **Responsive Design**: `grid`, `flex`, `md:grid-cols-2`, `lg:grid-cols-3`
- **Statistics Cards**: `stats-grid`, `stats-card`, `stats-icon-container`
- **Form Components**: `form-input`, `form-label`, `form-group`, `form-error`

### 2. Color Scheme
- **Primary**: Blue (`bg-blue-600`, `text-blue-600`)
- **Success**: Green (`bg-green-600`, `text-green-600`)
- **Warning**: Yellow (`bg-yellow-600`, `text-yellow-600`)
- **Danger**: Red (`bg-red-600`, `text-red-600`)
- **Secondary**: Gray (`bg-gray-600`, `text-gray-600`)

### 3. Icons
- **Font Awesome Icons**: `fas fa-sync-alt`, `fas fa-play`, `fas fa-stop`, `fas fa-redo`
- **Local Loading**: ไม่ใช้ CDN ตาม UI Standard

### 4. Badges & Status
```php
'pending' => 'badge-warning' (สีเหลือง)
'in_progress' => 'badge-primary' (สีน้ำเงิน)
'completed' => 'badge-success' (สีเขียว)
'failed' => 'badge-danger' (สีแดง)
'cancelled' => 'badge-secondary' (สีเทา)
```

## 🔧 ฟีเจอร์การทำงาน

### 1. การอัพเดต Laravel Core
```php
private function updateLaravelCore(SettingsUpdate $settingsUpdate, &$log)
{
    // 1. Backup ไฟล์สำคัญ
    $backupFiles = ['composer.json', 'composer.lock', '.env'];
    
    // 2. รัน composer update
    $result = Process::run('composer update --no-dev --optimize-autoloader');
    
    // 3. Clear caches
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    // 4. รัน migrations
    Artisan::call('migrate', ['--force' => true]);
}
```

### 2. การอัพเดต Package
```php
private function updatePackage(SettingsUpdate $settingsUpdate, &$log)
{
    // 1. อัพเดต package เฉพาะ
    $packageName = $settingsUpdate->component_name;
    $result = Process::run("composer update {$packageName} --no-dev --optimize-autoloader");
    
    // 2. Clear caches
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
}
```

### 3. การอัพเดต Configuration
```php
private function updateConfig(SettingsUpdate $settingsUpdate, &$log)
{
    // 1. อัพเดต configuration ตามความต้องการ
    // 2. Clear caches
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
}
```

## 🔒 ความปลอดภัย

### 1. Middleware Protection
- **Authentication**: ต้อง login ก่อน
- **Authorization**: ต้องมีสิทธิ์ admin หรือ settings_update_manage
- **IP Whitelist**: ตรวจสอบ IP ใน production
- **Session Validation**: ตรวจสอบเซสชันใน production

### 2. Audit Logging
- **ทุกการดำเนินการ**: สร้าง, แก้ไข, ลบ, เริ่ม, ยกเลิก, ลองใหม่
- **User Tracking**: บันทึกผู้ดำเนินการ
- **Request Information**: บันทึก IP, User Agent
- **Timestamp**: บันทึกเวลาทุกการดำเนินการ

### 3. Error Handling
- **Try-Catch**: จับ error ทุกการดำเนินการ
- **Logging**: บันทึก error ใน log file
- **User Feedback**: แสดงข้อความ error ที่เข้าใจง่าย
- **Rollback**: สามารถยกเลิกการอัพเดตได้

## 📊 การใช้งาน

### 1. การสร้างการอัพเดตใหม่
1. เข้าเมนู "อัพเดตระบบ"
2. คลิก "สร้างการอัพเดตใหม่"
3. กรอกข้อมูล:
   - ประเภทการอัพเดต (Core, Package, Config)
   - ชื่อ Component
   - เวอร์ชันปัจจุบันและเป้าหมาย
   - คำอธิบายและ Changelog
   - กำหนดเวลา (ถ้าต้องการ)
4. คลิก "สร้างการอัพเดต"

### 2. การดำเนินการอัพเดต
1. ดูรายการการอัพเดต
2. คลิก "เริ่มการอัพเดต" สำหรับสถานะ "รอดำเนินการ"
3. ระบบจะ:
   - Backup ไฟล์สำคัญ
   - ดำเนินการอัพเดตตามประเภท
   - Clear caches
   - รัน migrations (ถ้าจำเป็น)
   - บันทึก log การทำงาน

### 3. การจัดการการอัพเดต
- **ยกเลิก**: สำหรับสถานะ "รอดำเนินการ" หรือ "กำลังดำเนินการ"
- **ลองใหม่**: สำหรับสถานะ "ล้มเหลว"
- **แก้ไข**: สำหรับสถานะ "รอดำเนินการ" หรือ "ยกเลิก"
- **ลบ**: สำหรับสถานะ "รอดำเนินการ" หรือ "ยกเลิก"

## 🚀 การติดตั้งและใช้งาน

### 1. Migration
```bash
php artisan migrate
```

### 2. การเข้าถึง
- URL: `http://localhost:8000/backend/settings-update`
- ต้องมีสิทธิ์ admin หรือ settings_update_manage
- ต้อง login ก่อน

### 3. การทดสอบ
```bash
# ทดสอบการเข้าถึงหน้า
curl http://localhost:8000/backend/settings-update

# ทดสอบการ login
curl -X POST http://localhost:8000/login \
  -d "email=admin@example.com&password=password"
```

## 📝 หมายเหตุ

### 1. การใช้งานใน Production
- ควรตั้งค่า IP whitelist ใน config
- ควรตั้งค่า admin_ips ใน .env
- ควรทดสอบการอัพเดตใน staging environment ก่อน

### 2. การ Backup
- ระบบจะ backup ไฟล์สำคัญก่อนอัพเดต
- Backup เก็บไว้ใน `storage/app/backups/{update_id}/`
- ควรมี backup เพิ่มเติมก่อนการอัพเดตสำคัญ

### 3. การ Monitor
- ระบบมี auto-refresh สำหรับการอัพเดตที่กำลังดำเนินการ
- มี execution log สำหรับติดตามการทำงาน
- มี error logging สำหรับการ debug

## 🎯 สรุป

ระบบ Settings Update ได้รับการออกแบบให้:
- **ครบครัน**: มีฟีเจอร์ครบถ้วนสำหรับการจัดการการอัพเดต
- **ปลอดภัย**: มีระบบความปลอดภัยหลายชั้น
- **ใช้งานง่าย**: UI ที่เข้าใจง่ายและใช้งานสะดวก
- **ตามมาตรฐาน**: ใช้ UI Standard และ Tailwind CSS
- **ติดตามได้**: มี audit logging และ execution log
- **ยืดหยุ่น**: รองรับการอัพเดตหลายประเภท

ระบบนี้พร้อมใช้งานและสามารถขยายฟีเจอร์เพิ่มเติมได้ตามความต้องการ
