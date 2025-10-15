# Error Handling Security Guide

## ภาพรวม
เอกสารนี้อธิบายการใช้งาน Helper Functions สำหรับ Error Handling ที่ปลอดภัยตามมาตรฐาน OWASP เพื่อป้องกัน Information Disclosure

## Helper Functions ที่เพิ่มเข้ามา

### 1. `getSafeErrorMessage()`
ใช้สำหรับสร้าง error message ที่ปลอดภัย

```php
// การใช้งาน
$message = getSafeErrorMessage(
    $exception->getMessage(), // ข้อความ error ที่ละเอียด
    'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', // ข้อความที่ปลอดภัยสำหรับ production
    'ControllerName::methodName' // context สำหรับ logging
);
```

### 2. `getSafeApiErrorResponse()`
ใช้สำหรับ API responses ที่ปลอดภัย

```php
// การใช้งาน
try {
    // โค้ดที่อาจเกิด error
} catch (\Exception $e) {
    return getSafeApiErrorResponse(
        $e,
        'ไม่สามารถดำเนินการได้ กรุณาลองใหม่อีกครั้ง',
        'ControllerName::methodName',
        500 // HTTP status code
    );
}
```

### 3. `getSafeWebErrorResponse()`
ใช้สำหรับ web responses ที่ปลอดภัย

```php
// การใช้งาน
try {
    // โค้ดที่อาจเกิด error
} catch (\Exception $e) {
    return getSafeWebErrorResponse(
        $e,
        'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง',
        'ControllerName::methodName'
    );
}
```

## ตัวอย่างการใช้งาน

### Controller Method
```php
public function storeUser(Request $request)
{
    try {
        $user = User::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'เพิ่มผู้ใช้เรียบร้อยแล้ว',
            'data' => $user
        ]);
    } catch (\Exception $e) {
        return getSafeApiErrorResponse(
            $e,
            'ไม่สามารถเพิ่มผู้ใช้ได้ กรุณาลองใหม่อีกครั้ง',
            'UserManagementController::storeUser'
        );
    }
}
```

### Web Form Handler
```php
public function updateProfile(Request $request)
{
    try {
        $user = auth()->user();
        $user->update($request->validated());
        return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    } catch (\Exception $e) {
        return getSafeWebErrorResponse(
            $e,
            'เกิดข้อผิดพลาดในการอัปเดตข้อมูล กรุณาลองใหม่อีกครั้ง',
            'ProfileController::updateProfile'
        );
    }
}
```

## การตั้งค่า Environment

### Development Mode (APP_DEBUG=true)
- แสดง error message ที่ละเอียด
- แสดง stack trace
- แสดงไฟล์และบรรทัดที่เกิด error

### Production Mode (APP_DEBUG=false)
- แสดงเฉพาะข้อความ error ที่ปลอดภัย
- ไม่แสดง stack trace
- ไม่แสดงข้อมูลระบบภายใน

## การ Logging

ทุก error จะถูกบันทึกลง log file พร้อมกับ:
- Error message ที่ละเอียด
- Stack trace
- ไฟล์และบรรทัดที่เกิด error
- Context ที่ระบุ

## ข้อดีของการใช้ Helper Functions

1. **ความปลอดภัย**: ป้องกัน Information Disclosure
2. **ความสม่ำเสมอ**: Error handling แบบเดียวกันทั้งระบบ
3. **การ Debug**: บันทึกข้อมูลที่ละเอียดใน log
4. **การบำรุงรักษา**: แก้ไขได้ง่ายในจุดเดียว
5. **มาตรฐาน OWASP**: ปฏิบัติตามมาตรฐานความปลอดภัย

## การตรวจสอบ

### ตรวจสอบ Debug Mode
```php
if (config('app.debug', false)) {
    // แสดงข้อมูล debug
} else {
    // แสดงข้อความที่ปลอดภัย
}
```

### ตรวจสอบ Log Files
```bash
tail -f storage/logs/laravel.log
```

## คำแนะนำเพิ่มเติม

1. **ตั้งค่า APP_DEBUG=false ใน production**
2. **ตรวจสอบ log files เป็นประจำ**
3. **ใช้ context ที่ชัดเจนในการเรียกใช้ helper functions**
4. **ทดสอบ error handling ในทั้ง debug และ production mode**
5. **พิจารณาใช้ external logging service สำหรับ production**

## ตัวอย่างการทดสอบ

### ทดสอบใน Development
```php
// ตั้งค่า APP_DEBUG=true
// จะเห็น error message ที่ละเอียด
```

### ทดสอบใน Production
```php
// ตั้งค่า APP_DEBUG=false
// จะเห็นเฉพาะข้อความที่ปลอดภัย
// แต่ข้อมูลละเอียดจะอยู่ใน log
```

## การอัปเดต Controllers ที่มีอยู่

Controllers ที่ได้รับการอัปเดตแล้ว:
- ✅ UserManagementController
- ✅ SettingsController  
- ✅ ProfileController

Controllers ที่ยังต้องอัปเดต:
- ⏳ AuditController
- ⏳ PerformanceController
- ⏳ SystemInfoController
- ⏳ DashboardController

## สรุป

การใช้ Helper Functions เหล่านี้จะช่วยให้แอปพลิเคชันมีความปลอดภัยมากขึ้นโดยป้องกันการรั่วไหลของข้อมูลระบบภายใน และยังคงความสามารถในการ debug ได้ในสภาพแวดล้อม development
