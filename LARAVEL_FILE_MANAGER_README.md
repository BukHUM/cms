# Laravel File Manager Integration

## การติดตั้งและใช้งาน Laravel File Manager

### ✅ สิ่งที่ติดตั้งแล้ว

1. **Package**: `unisharp/laravel-filemanager` v2.11.0
2. **Configuration**: `config/lfm.php` - ปรับแต่งให้เหมาะสมกับระบบ
3. **Routes**: เพิ่ม routes สำหรับ Laravel File Manager
4. **Controller**: เพิ่ม method `lfm()` ใน `MediaBrowserController`
5. **Views**: สร้าง `lfm.blade.php` สำหรับแสดง Laravel File Manager
6. **Integration**: เพิ่มปุ่ม "File Manager" ใน Media Browser

### 🚀 วิธีการใช้งาน

#### 1. เข้าถึง Laravel File Manager
- ไปที่ **Media Browser** (`/backend/media-browser`)
- คลิกปุ่ม **"File Manager"** (สีม่วง)
- จะเปิด Laravel File Manager ในหน้าต่างใหม่

#### 2. ฟีเจอร์ที่ใช้งานได้
- **อัปโหลดไฟล์**: ลากและวาง หรือคลิกปุ่มอัปโหลด
- **สร้างโฟลเดอร์**: คลิกปุ่ม "New Folder"
- **ลบไฟล์/โฟลเดอร์**: เลือกไฟล์และคลิกปุ่มลบ
- **เปลี่ยนชื่อ**: เลือกไฟล์และคลิกปุ่มเปลี่ยนชื่อ
- **ย้ายไฟล์**: ลากและวางไฟล์ไปยังโฟลเดอร์อื่น
- **ดูรูปภาพ**: คลิกที่รูปภาพเพื่อดูขนาดเต็ม
- **ดาวน์โหลด**: คลิกปุ่มดาวน์โหลด

#### 3. ประเภทไฟล์ที่รองรับ
- **รูปภาพ**: JPG, PNG, GIF, SVG, WebP
- **เอกสาร**: PDF, DOC, DOCX, TXT
- **วิดีโอ**: MP4, AVI, MOV
- **เสียง**: MP3, WAV, OGG

### ⚙️ การตั้งค่า

#### 1. ขนาดไฟล์สูงสุด
```php
// config/lfm.php
'max_size' => 10240, // 10MB
```

#### 2. โฟลเดอร์ที่ใช้
- **Files**: `storage/app/public/media/`
- **Images**: `storage/app/public/images/`

#### 3. Thumbnail
- สร้าง thumbnail อัตโนมัติ
- ขนาด: 200x200 pixels
- เก็บในโฟลเดอร์ `thumbs/`

### 🔧 การปรับแต่งเพิ่มเติม

#### 1. เปลี่ยนโฟลเดอร์เริ่มต้น
```php
// config/lfm.php
'folder_categories' => [
    'file' => [
        'folder_name' => 'your-custom-folder',
        // ...
    ],
],
```

#### 2. เพิ่มประเภทไฟล์
```php
// config/lfm.php
'valid_mime' => [
    'image/jpeg',
    'image/png',
    'application/pdf',
    // เพิ่มประเภทไฟล์ใหม่ที่นี่
],
```

#### 3. ปรับขนาดไฟล์สูงสุด
```php
// config/lfm.php
'max_size' => 20480, // 20MB
```

### 🎨 UI/UX Features

#### 1. View Modes
- **Grid View**: แสดงไฟล์เป็นกริด
- **List View**: แสดงไฟล์เป็นรายการ

#### 2. Responsive Design
- รองรับหน้าจอทุกขนาด
- Mobile-friendly interface

#### 3. Modern UI
- Glassmorphism effects
- Smooth animations
- Color-coded file types
- Hover effects

### 🔒 Security Features

#### 1. Authentication
- ต้องล็อกอินก่อนใช้งาน
- ใช้ Laravel Auth middleware

#### 2. File Validation
- ตรวจสอบ MIME type
- จำกัดขนาดไฟล์
- ป้องกันไฟล์อันตราย

#### 3. Path Security
- ป้องกัน directory traversal
- จำกัดการเข้าถึงโฟลเดอร์

### 📱 Mobile Support

- Touch-friendly interface
- Responsive grid layout
- Mobile-optimized controls
- Swipe gestures support

### 🚀 Performance

#### 1. Thumbnail Generation
- สร้าง thumbnail อัตโนมัติ
- ลดขนาดไฟล์สำหรับการแสดงผล
- Cache thumbnails

#### 2. Lazy Loading
- โหลดไฟล์ตามต้องการ
- ปรับปรุงความเร็วการแสดงผล

#### 3. Pagination
- แสดงไฟล์ 30 รายการต่อหน้า
- ลดการใช้ memory

### 🐛 Troubleshooting

#### 1. ปัญหาการอัปโหลด
- ตรวจสอบสิทธิ์โฟลเดอร์ `storage/`
- ตรวจสอบขนาดไฟล์สูงสุด
- ตรวจสอบ MIME type

#### 2. ปัญหา Thumbnail
- ตรวจสอบ GD extension
- ตรวจสอบสิทธิ์โฟลเดอร์ `thumbs/`

#### 3. ปัญหา Routes
- รัน `php artisan route:clear`
- รัน `php artisan config:clear`

### 📞 Support

หากมีปัญหาหรือต้องการความช่วยเหลือ:
1. ตรวจสอบ Laravel logs
2. ตรวจสอบ browser console
3. ตรวจสอบ network requests
4. ติดต่อทีมพัฒนา

---

## 🎉 สรุป

Laravel File Manager ได้ถูกติดตั้งและตั้งค่าเรียบร้อยแล้ว! 

**ฟีเจอร์หลัก:**
- ✅ อัปโหลดไฟล์แบบลากและวาง
- ✅ จัดการโฟลเดอร์
- ✅ ดูรูปภาพแบบเต็มหน้าจอ
- ✅ เปลี่ยนชื่อและลบไฟล์
- ✅ UI/UX ที่สวยงามและใช้งานง่าย
- ✅ รองรับมือถือ
- ✅ ปลอดภัยและมีประสิทธิภาพ

**การใช้งาน:**
1. ไปที่ Media Browser
2. คลิกปุ่ม "File Manager"
3. เริ่มใช้งานได้เลย!

---
*อัปเดตล่าสุด: {{ date('Y-m-d H:i:s') }}*
