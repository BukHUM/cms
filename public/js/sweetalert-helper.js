/**
 * SweetAlert2 Helper Functions
 * ฟังก์ชันช่วยสำหรับใช้งาน SweetAlert2
 */

// Global SweetAlert2 Helper Object
window.SwalHelper = {
    
    /**
     * แสดงข้อความสำเร็จ
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {string} title - หัวข้อ (optional)
     */
    success: function(message, title = 'สำเร็จ!') {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    },
    
    /**
     * แสดงข้อความผิดพลาด
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {string} title - หัวข้อ (optional)
     */
    error: function(message, title = 'เกิดข้อผิดพลาด!') {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonText: 'ตกลง'
        });
    },
    
    /**
     * แสดงข้อความคำเตือน
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {string} title - หัวข้อ (optional)
     */
    warning: function(message, title = 'คำเตือน!') {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            confirmButtonText: 'ตกลง'
        });
    },
    
    /**
     * แสดงข้อความข้อมูล
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {string} title - หัวข้อ (optional)
     */
    info: function(message, title = 'ข้อมูล!') {
        return Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    },
    
    /**
     * แสดงข้อความยืนยัน
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {string} title - หัวข้อ (optional)
     * @param {function} callback - ฟังก์ชันที่จะเรียกเมื่อยืนยัน
     */
    confirm: function(message, title = 'ยืนยันการดำเนินการ', callback = null) {
        return Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
            return result.isConfirmed;
        });
    },
    
    /**
     * แสดงข้อความยืนยันการลบ
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {function} callback - ฟังก์ชันที่จะเรียกเมื่อยืนยัน
     */
    confirmDelete: function(message = 'คุณต้องการลบข้อมูลนี้หรือไม่?', callback = null) {
        return Swal.fire({
            title: 'ยืนยันการลบ',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
            return result.isConfirmed;
        });
    },
    
    /**
     * แสดงข้อความยืนยันการออกจากระบบ
     * @param {function} callback - ฟังก์ชันที่จะเรียกเมื่อยืนยัน
     */
    confirmLogout: function(callback = null) {
        return Swal.fire({
            title: 'ยืนยันการออกจากระบบ',
            text: 'คุณต้องการออกจากระบบหรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ออกจากระบบ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
            return result.isConfirmed;
        });
    },
    
    /**
     * แสดงข้อความโหลด
     * @param {string} message - ข้อความที่ต้องการแสดง
     */
    loading: function(message = 'กำลังโหลด...') {
        return Swal.fire({
            title: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },
    
    /**
     * ปิดข้อความโหลด
     */
    close: function() {
        Swal.close();
    },
    
    /**
     * แสดงฟอร์มป้อนข้อมูล
     * @param {string} title - หัวข้อ
     * @param {string} text - คำอธิบาย
     * @param {string} inputType - ประเภท input
     * @param {string} placeholder - placeholder
     * @param {function} callback - ฟังก์ชันที่จะเรียกเมื่อยืนยัน
     */
    prompt: function(title, text, inputType = 'text', placeholder = '', callback = null) {
        return Swal.fire({
            title: title,
            text: text,
            input: inputType,
            inputPlaceholder: placeholder,
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            inputValidator: (value) => {
                if (!value) {
                    return 'กรุณากรอกข้อมูล!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback(result.value);
            }
            return result;
        });
    },
    
    /**
     * แสดงข้อความแบบ Toast
     * @param {string} message - ข้อความที่ต้องการแสดง
     * @param {string} type - ประเภท (success, error, warning, info)
     */
    toast: function(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        return Toast.fire({
            icon: type,
            title: message
        });
    }
};

// Auto-initialize SweetAlert2 with Thai language
document.addEventListener('DOMContentLoaded', function() {
    // Configure global defaults
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
    
    // Enhanced logout confirmation for all logout links
    const logoutLinks = document.querySelectorAll('a[href*="logout"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            SwalHelper.confirmLogout(() => {
                const form = document.getElementById('logout-form');
                if (form) {
                    form.submit();
                }
            });
        });
    });
    
    // Enhanced delete confirmation for all delete buttons
    const deleteButtons = document.querySelectorAll('button[data-action="delete"], a[data-action="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.message || 'คุณต้องการลบข้อมูลนี้หรือไม่?';
            SwalHelper.confirmDelete(message, () => {
                const form = this.closest('form');
                if (form) {
                    form.submit();
                } else {
                    window.location.href = this.href;
                }
            });
        });
    });
});
