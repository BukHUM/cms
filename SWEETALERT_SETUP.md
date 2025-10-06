# SweetAlert2 Setup Complete ✅

SweetAlert2 ได้ถูกติดตั้งและตั้งค่าเรียบร้อยแล้ว!

## การติดตั้งที่เสร็จสิ้น

### 1. Package Installation
```bash
npm install sweetalert2
```

### 2. CDN Integration
เพิ่ม SweetAlert2 CDN ใน layouts:
```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/sweetalert-helper.js') }}"></script>
```

### 3. Helper Functions
สร้างไฟล์ `public/js/sweetalert-helper.js` พร้อมฟังก์ชันช่วย

## คุณสมบัติของ SweetAlert2

### 🎨 **UI Features**
- **Modern Design**: การออกแบบที่ทันสมัยและสวยงาม
- **Responsive**: รองรับทุกอุปกรณ์
- **Animations**: เอฟเฟกต์การเคลื่อนไหวที่นุ่มนวล
- **Customizable**: สามารถปรับแต่งได้ตามต้องการ

### 🔧 **Helper Functions**

#### **Basic Alerts**
```javascript
// Success Alert
SwalHelper.success('การดำเนินการสำเร็จ!');

// Error Alert
SwalHelper.error('เกิดข้อผิดพลาด!');

// Warning Alert
SwalHelper.warning('คำเตือน!');

// Info Alert
SwalHelper.info('ข้อมูลเพิ่มเติม');
```

#### **Confirmation Dialogs**
```javascript
// Confirm Dialog
SwalHelper.confirm('คุณต้องการดำเนินการต่อหรือไม่?', 'ยืนยันการดำเนินการ', function() {
    // Code to execute when confirmed
});

// Delete Confirmation
SwalHelper.confirmDelete('คุณต้องการลบข้อมูลนี้หรือไม่?', function() {
    // Code to execute when confirmed
});

// Logout Confirmation
SwalHelper.confirmLogout(function() {
    // Code to execute when confirmed
});
```

#### **Input Prompts**
```javascript
// Text Input
SwalHelper.prompt('กรอกชื่อของคุณ', 'กรุณากรอกชื่อ', 'text', 'ชื่อของคุณ', function(value) {
    console.log('Name:', value);
});

// Email Input
SwalHelper.prompt('กรอกอีเมล', 'กรุณากรอกอีเมล', 'email', 'example@email.com', function(value) {
    console.log('Email:', value);
});
```

#### **Loading States**
```javascript
// Show Loading
SwalHelper.loading('กำลังประมวลผล...');

// Close Loading
SwalHelper.close();
```

#### **Toast Notifications**
```javascript
// Success Toast
SwalHelper.toast('ดำเนินการสำเร็จ!', 'success');

// Error Toast
SwalHelper.toast('เกิดข้อผิดพลาด!', 'error');

// Warning Toast
SwalHelper.toast('คำเตือน!', 'warning');

// Info Toast
SwalHelper.toast('ข้อมูลใหม่!', 'info');
```

### 🎯 **Auto Features**

#### **Session Messages**
ระบบจะแสดงข้อความจาก session อัตโนมัติ:
```php
// In Controller
return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ!');
return redirect()->back()->with('error', 'เกิดข้อผิดพลาด!');
return redirect()->back()->with('warning', 'คำเตือน!');
return redirect()->back()->with('info', 'ข้อมูลเพิ่มเติม');
```

#### **Enhanced Logout**
ปุ่ม logout จะแสดง confirmation dialog อัตโนมัติ:
```html
<a href="{{ route('logout') }}">ออกจากระบบ</a>
```

#### **Delete Confirmation**
ปุ่มลบจะแสดง confirmation dialog อัตโนมัติ:
```html
<button data-action="delete" data-message="คุณต้องการลบข้อมูลนี้หรือไม่?">
    ลบข้อมูล
</button>
```

## การใช้งาน

### หน้าทดสอบ SweetAlert2
เข้าถึงได้ที่: `http://localhost:8000/sweetalert-test`

หน้าทดสอบนี้จะแสดง:
- Basic Alerts (Success, Error, Warning, Info)
- Confirmation Dialogs
- Input Prompts
- Loading States
- Toast Notifications
- Custom Alerts
- Session Messages Test

### การใช้งานในโค้ด

#### **ใน Blade Templates**
```html
<button onclick="SwalHelper.success('บันทึกข้อมูลสำเร็จ!')">
    บันทึกข้อมูล
</button>

<button onclick="SwalHelper.confirmDelete('ลบข้อมูลนี้?', function() { /* delete code */ })">
    ลบข้อมูล
</button>
```

#### **ใน JavaScript**
```javascript
// Form submission with confirmation
document.getElementById('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    SwalHelper.confirm('คุณต้องการบันทึกข้อมูลหรือไม่?', 'ยืนยันการบันทึก', function() {
        // Submit form
        this.submit();
    }.bind(this));
});
```

#### **ใน AJAX Requests**
```javascript
// AJAX with loading state
SwalHelper.loading('กำลังบันทึกข้อมูล...');

fetch('/api/save', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    SwalHelper.close();
    SwalHelper.success('บันทึกข้อมูลสำเร็จ!');
})
.catch(error => {
    SwalHelper.close();
    SwalHelper.error('เกิดข้อผิดพลาดในการบันทึก!');
});
```

## การตั้งค่าเพิ่มเติม

### Custom Configuration
```javascript
// ในไฟล์ sweetalert-helper.js
Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary me-2',
        cancelButton: 'btn btn-secondary',
        denyButton: 'btn btn-warning',
        input: 'form-control'
    },
    buttonsStyling: false,
    confirmButtonText: 'ตกลง',
    cancelButtonText: 'ยกเลิก',
    denyButtonText: 'ไม่',
    allowOutsideClick: false,
    allowEscapeKey: false
});
```

### Custom Styling
```css
/* Custom styles for SweetAlert2 */
.swal2-popup {
    border-radius: 15px;
}

.swal2-title {
    font-family: 'Kanit', sans-serif;
}

.swal2-content {
    font-family: 'Kanit', sans-serif;
}
```

## ข้อมูลเพิ่มเติม

- **Documentation**: [SweetAlert2 Official Docs](https://sweetalert2.github.io/)
- **Version**: 11.x
- **License**: MIT
- **Compatibility**: Modern browsers, IE11+

## ตัวอย่างการใช้งาน

### 1. Form Validation
```javascript
function validateForm() {
    const name = document.getElementById('name').value;
    
    if (!name) {
        SwalHelper.error('กรุณากรอกชื่อ');
        return false;
    }
    
    SwalHelper.confirm('คุณต้องการบันทึกข้อมูลหรือไม่?', 'ยืนยันการบันทึก', function() {
        document.getElementById('form').submit();
    });
    
    return false;
}
```

### 2. Delete with Confirmation
```javascript
function deleteItem(id) {
    SwalHelper.confirmDelete('คุณต้องการลบรายการนี้หรือไม่?', function() {
        fetch(`/api/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            SwalHelper.success('ลบข้อมูลสำเร็จ!');
            location.reload();
        })
        .catch(error => {
            SwalHelper.error('เกิดข้อผิดพลาดในการลบ!');
        });
    });
}
```

### 3. Bulk Operations
```javascript
function bulkDelete() {
    const selectedItems = document.querySelectorAll('input[type="checkbox"]:checked');
    
    if (selectedItems.length === 0) {
        SwalHelper.warning('กรุณาเลือกรายการที่ต้องการลบ');
        return;
    }
    
    SwalHelper.confirmDelete(`คุณต้องการลบ ${selectedItems.length} รายการหรือไม่?`, function() {
        SwalHelper.loading('กำลังลบข้อมูล...');
        
        // Bulk delete logic here
        setTimeout(() => {
            SwalHelper.close();
            SwalHelper.success('ลบข้อมูลสำเร็จ!');
        }, 2000);
    });
}
```

---

🎉 **SweetAlert2 พร้อมใช้งานแล้ว!**

ตอนนี้คุณสามารถใช้ SweetAlert2 แสดง alert ที่สวยงามและใช้งานง่ายได้แล้ว
