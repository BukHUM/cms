// ========================================
// SECURITY SETTINGS FUNCTIONS
// ========================================

// Security Settings Form
document.addEventListener('DOMContentLoaded', function() {
    const securityForm = document.getElementById('securitySettingsForm');
    if (securityForm) {
        securityForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                sessionLifetime: document.getElementById('sessionLifetime').value,
                maxLoginAttempts: document.getElementById('maxLoginAttempts').value,
                passwordMinLength: document.getElementById('passwordMinLength').value,
                requireSpecialChars: document.getElementById('requireSpecialChars').checked,
                twoFactorAuth: document.getElementById('twoFactorAuth').checked,
                ipWhitelist: document.getElementById('ipWhitelist').checked
            };
            
            // Validate password requirements
            if (formData.passwordMinLength < 6) {
                SwalHelper.error('ความยาวรหัสผ่านขั้นต่ำต้องไม่น้อยกว่า 6 ตัวอักษร');
                return;
            }
            
            if (formData.maxLoginAttempts < 3) {
                SwalHelper.error('จำนวนครั้งการเข้าสู่ระบบสูงสุดต้องไม่น้อยกว่า 3 ครั้ง');
                return;
            }
            
            SwalHelper.loading('กำลังบันทึกการตั้งค่าความปลอดภัย...');
            
            // Save to localStorage
            localStorage.setItem('securitySettings', JSON.stringify(formData));
            
            // Simulate API call
            setTimeout(() => {
                SwalHelper.close();
                SwalHelper.success('บันทึกการตั้งค่าความปลอดภัยเรียบร้อยแล้ว');
            }, 1500);
        });
    }
});

// Load Security Settings from localStorage
function loadSecuritySettings() {
    const securitySettings = localStorage.getItem('securitySettings');
    if (securitySettings) {
        const data = JSON.parse(securitySettings);
        document.getElementById('sessionLifetime').value = data.sessionLifetime || '120';
        document.getElementById('maxLoginAttempts').value = data.maxLoginAttempts || '5';
        document.getElementById('passwordMinLength').value = data.passwordMinLength || '8';
        document.getElementById('requireSpecialChars').checked = data.requireSpecialChars || false;
        document.getElementById('twoFactorAuth').checked = data.twoFactorAuth || false;
        document.getElementById('ipWhitelist').checked = data.ipWhitelist || false;
        
    }
}
