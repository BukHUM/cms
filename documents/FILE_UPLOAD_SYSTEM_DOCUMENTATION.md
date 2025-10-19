# File Upload System for Settings Documentation

## Overview
ระบบอัปโหลดไฟล์สำหรับการตั้งค่า `site_logo` และ `site_favicon` ที่ให้ความยืดหยุ่นในการจัดการไฟล์รูปภาพ

## Features
- ✅ อัปโหลดไฟล์รูปภาพ (JPG, PNG, GIF, ICO)
- ✅ Preview ไฟล์ปัจจุบันและไฟล์ใหม่
- ✅ ตรวจสอบขนาดไฟล์ (ไม่เกิน 2MB)
- ✅ ตรวจสอบประเภทไฟล์
- ✅ ลบไฟล์ปัจจุบัน
- ✅ แสดงข้อมูลไฟล์ (ชื่อ, ขนาด)
- ✅ Responsive design

## Supported File Types
- **Images**: JPG, JPEG, PNG, GIF
- **Icons**: ICO (favicon)
- **Max Size**: 2MB per file

## File Storage
- **Path**: `storage/app/public/settings/`
- **Public URL**: `/storage/settings/`
- **Naming**: `{setting_key}_{timestamp}.{extension}`

## Usage

### 1. Upload New File
1. ไปที่หน้า Settings General
2. หา `site_logo` หรือ `site_favicon` ในตาราง
3. คลิกปุ่มแก้ไข (ไอคอน edit)
4. เลือกไฟล์ใหม่จาก file picker
5. ดู preview ไฟล์ใหม่
6. บันทึกการเปลี่ยนแปลง

### 2. Remove Current File
1. เปิด modal แก้ไข
2. คลิกปุ่ม "X" ที่ไฟล์ปัจจุบัน
3. บันทึกการเปลี่ยนแปลง

### 3. View Current File
- ไฟล์ปัจจุบันจะแสดงในส่วน "ไฟล์ปัจจุบัน"
- แสดงรูปภาพ preview และข้อมูลไฟล์

## File Validation

### Client-Side Validation
```javascript
// File type validation
const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/x-icon', 'image/vnd.microsoft.icon'];

// File size validation (2MB)
if (file.size > 2 * 1024 * 1024) {
    // Show error
}
```

### Server-Side Validation
```php
$request->validate([
    'file' => 'required|image|mimes:jpeg,png,gif,ico|max:2048'
]);
```

## File Processing

### 1. File Upload Process
```php
// Generate unique filename
$filename = $setting->key . '_' . time() . '.' . $file->getClientOriginalExtension();

// Store file
$path = $file->storeAs('public/settings', $filename);

// Update setting value
$setting->value = 'settings/' . $filename;
```

### 2. File Display
```blade
<!-- In templates -->
<img src="{{ asset('storage/' . setting('site_logo')) }}" alt="Site Logo">
<link rel="icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
```

## JavaScript Functions

### 1. File Preview Functions
```javascript
function showCurrentFilePreview(filePath) {
    // Display current file with preview
}

function showNewFilePreview(file) {
    // Display new file with preview
}

function formatFileSize(bytes) {
    // Format file size for display
}
```

### 2. File Management Functions
```javascript
function resetFileUploadSections() {
    // Reset all file upload sections
}

// Event listeners for file operations
document.getElementById('edit_file').addEventListener('change', function(e) {
    // Handle file selection
});
```

## UI Components

### 1. File Upload Section
```blade
<div id="file_upload_section" class="hidden">
    <input type="file" id="edit_file" name="file" accept="image/*,.ico">
    <p class="text-xs text-gray-500">รองรับไฟล์: JPG, PNG, GIF, ICO (ขนาดไม่เกิน 2MB)</p>
</div>
```

### 2. Current File Preview
```blade
<div id="current_file_preview" class="hidden">
    <img id="current_file_image" src="" alt="Current file">
    <p id="current_file_name"></p>
    <p id="current_file_size"></p>
    <button id="remove_current_file">Remove</button>
</div>
```

### 3. New File Preview
```blade
<div id="new_file_preview" class="hidden">
    <img id="new_file_image" src="" alt="New file">
    <p id="new_file_name"></p>
    <p id="new_file_size"></p>
    <button id="remove_new_file">Remove</button>
</div>
```

## Error Handling

### 1. File Type Error
```javascript
Swal.fire({
    title: 'ไฟล์ไม่ถูกต้อง!',
    text: 'กรุณาเลือกไฟล์รูปภาพ (JPG, PNG, GIF, ICO) เท่านั้น',
    icon: 'error'
});
```

### 2. File Size Error
```javascript
Swal.fire({
    title: 'ไฟล์ใหญ่เกินไป!',
    text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2MB',
    icon: 'error'
});
```

## Security Features

### 1. File Type Validation
- ตรวจสอบ MIME type
- ตรวจสอบ file extension
- อนุญาตเฉพาะไฟล์ที่กำหนด

### 2. File Size Limitation
- จำกัดขนาดไฟล์ไม่เกิน 2MB
- ป้องกันการอัปโหลดไฟล์ขนาดใหญ่

### 3. File Storage Security
- เก็บไฟล์ในโฟลเดอร์ที่ปลอดภัย
- ใช้ชื่อไฟล์ที่ไม่ซ้ำกัน
- ไม่เก็บไฟล์ใน web root

## Troubleshooting

### 1. File Not Uploading
- ตรวจสอบว่า storage link ถูกสร้างแล้ว: `php artisan storage:link`
- ตรวจสอบสิทธิ์การเขียนไฟล์ในโฟลเดอร์ `storage/app/public/settings/`
- ตรวจสอบการตั้งค่า `upload_max_filesize` ใน PHP

### 2. File Not Displaying
- ตรวจสอบว่า storage link ถูกสร้างแล้ว
- ตรวจสอบ path ของไฟล์ใน database
- ตรวจสอบว่าไฟล์มีอยู่จริงในโฟลเดอร์

### 3. Validation Errors
- ตรวจสอบประเภทไฟล์ที่อัปโหลด
- ตรวจสอบขนาดไฟล์
- ตรวจสอบการตั้งค่า validation rules

## Best Practices

### 1. File Management
- ใช้ชื่อไฟล์ที่ไม่ซ้ำกัน
- ลบไฟล์เก่าหลังอัปโหลดไฟล์ใหม่
- ตรวจสอบสิทธิ์การเข้าถึงไฟล์

### 2. Performance
- จำกัดขนาดไฟล์
- ใช้การบีบอัดรูปภาพ
- ใช้ CDN สำหรับไฟล์ขนาดใหญ่

### 3. User Experience
- แสดง preview ไฟล์
- แสดงข้อมูลไฟล์ (ชื่อ, ขนาด)
- ให้ feedback ที่ชัดเจน
