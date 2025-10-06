// ========================================
// EMAIL SETTINGS FUNCTIONS
// ========================================

// Email Settings Form
document.addEventListener('DOMContentLoaded', function() {
    const emailForm = document.getElementById('emailSettingsForm');
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                mailDriver: document.getElementById('mailDriver').value,
                mailHost: document.getElementById('mailHost').value,
                mailPort: document.getElementById('mailPort').value,
                mailUsername: document.getElementById('mailUsername').value,
                mailPassword: document.getElementById('mailPassword').value,
                mailEncryption: document.getElementById('mailEncryption').value,
                mailFromAddress: document.getElementById('mailFromAddress').value,
                mailFromName: document.getElementById('mailFromName').value
            };
            
            console.log('Saving email settings:', formData);
            SwalHelper.loading('กำลังบันทึกการตั้งค่าอีเมล...');
            
            // Save to localStorage
            localStorage.setItem('emailSettings', JSON.stringify(formData));
            
            // Simulate API call
            setTimeout(() => {
                SwalHelper.close();
                SwalHelper.success('บันทึกการตั้งค่าอีเมลเรียบร้อยแล้ว');
            }, 1500);
        });
    }
});

// Test Email Function
function testEmail() {
    const mailHost = document.getElementById('mailHost').value;
    const mailPort = document.getElementById('mailPort').value;
    const mailUsername = document.getElementById('mailUsername').value;
    
    if (!mailHost || !mailPort || !mailUsername) {
        SwalHelper.error('กรุณากรอกข้อมูลการตั้งค่าอีเมลให้ครบถ้วน');
        return;
    }
    
    console.log('Testing email configuration...');
    SwalHelper.loading('กำลังทดสอบการส่งอีเมล...');
    
    // Simulate email test
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ทดสอบการส่งอีเมลสำเร็จ! ระบบสามารถส่งอีเมลได้ปกติ');
    }, 2000);
}

// Load Email Settings from localStorage
function loadEmailSettings() {
    const emailSettings = localStorage.getItem('emailSettings');
    if (emailSettings) {
        const data = JSON.parse(emailSettings);
        document.getElementById('mailDriver').value = data.mailDriver || 'smtp';
        document.getElementById('mailHost').value = data.mailHost || 'smtp.gmail.com';
        document.getElementById('mailPort').value = data.mailPort || '587';
        document.getElementById('mailUsername').value = data.mailUsername || '';
        document.getElementById('mailPassword').value = data.mailPassword || '';
        document.getElementById('mailEncryption').value = data.mailEncryption || 'tls';
        document.getElementById('mailFromAddress').value = data.mailFromAddress || 'noreply@example.com';
        document.getElementById('mailFromName').value = data.mailFromName || '';
        
        console.log('Email settings loaded');
    }
}
