// ========================================
// BACKUP SETTINGS FUNCTIONS
// ========================================

// Backup Settings Form
document.addEventListener('DOMContentLoaded', function() {
    const backupForm = document.getElementById('backupSettingsForm');
    if (backupForm) {
        backupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                backupFrequency: document.getElementById('backupFrequency').value,
                backupTime: document.getElementById('backupTime').value,
                backupRetention: document.getElementById('backupRetention').value,
                backupLocation: document.getElementById('backupLocation').value
            };
            
            // Validate backup retention
            if (formData.backupRetention < 7) {
                SwalHelper.error('การเก็บไฟล์สำรองต้องไม่น้อยกว่า 7 วัน');
                return;
            }
            
            console.log('Saving backup settings:', formData);
            SwalHelper.loading('กำลังบันทึกการตั้งค่าสำรองข้อมูล...');
            
            // Save to localStorage
            localStorage.setItem('backupSettings', JSON.stringify(formData));
            
            // Simulate API call
            setTimeout(() => {
                SwalHelper.close();
                SwalHelper.success('บันทึกการตั้งค่าสำรองข้อมูลเรียบร้อยแล้ว');
            }, 1500);
        });
    }
});

// Create Backup Function
function createBackup() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะสร้างสำรองข้อมูลตอนนี้?', 'ยืนยันการสร้างสำรองข้อมูล', function() {
        SwalHelper.loading('กำลังสร้างสำรองข้อมูล...');
        
        // Simulate backup creation
        setTimeout(() => {
            SwalHelper.close();
            SwalHelper.success('สร้างสำรองข้อมูลสำเร็จ! ไฟล์สำรองถูกสร้างเรียบร้อยแล้ว');
            
            // Add new backup to history table
            addBackupToHistory();
        }, 3000);
    });
}

// Add new backup to history table
function addBackupToHistory() {
    const tbody = document.querySelector('.backup-history-table tbody');
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 19).replace('T', ' ');
    const fileSize = (Math.random() * 5 + 10).toFixed(1) + ' MB';
    
    const newRow = `
        <tr>
            <td>${dateStr}</td>
            <td>${fileSize}</td>
            <td><span class="badge bg-success">สำเร็จ</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="downloadBackup('${dateStr}')">ดาวน์โหลด</button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteBackup(this)">ลบ</button>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('afterbegin', newRow);
}

// Download Backup Function
function downloadBackup(dateStr) {
    console.log('Downloading backup:', dateStr);
    SwalHelper.loading('กำลังดาวน์โหลดไฟล์สำรอง...');
    
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ดาวน์โหลดไฟล์สำรองสำเร็จ!');
    }, 1500);
}

// Delete Backup Function
function deleteBackup(button) {
    SwalHelper.confirmDelete('คุณแน่ใจหรือไม่ที่จะลบไฟล์สำรองนี้?', function() {
        const row = button.closest('tr');
        row.remove();
        SwalHelper.success('ลบไฟล์สำรองเรียบร้อยแล้ว');
    });
}

// Load Backup Settings from localStorage
function loadBackupSettings() {
    const backupSettings = localStorage.getItem('backupSettings');
    if (backupSettings) {
        const data = JSON.parse(backupSettings);
        document.getElementById('backupFrequency').value = data.backupFrequency || 'daily';
        document.getElementById('backupTime').value = data.backupTime || '02:00';
        document.getElementById('backupRetention').value = data.backupRetention || '30';
        document.getElementById('backupLocation').value = data.backupLocation || 'local';
        
        console.log('Backup settings loaded');
    }
}
