// ========================================
// AUDIT LOG SETTINGS FUNCTIONS
// ========================================

// Audit Settings Form
document.addEventListener('DOMContentLoaded', function() {
    const auditForm = document.getElementById('auditSettingsForm');
    if (auditForm) {
        auditForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                auditEnabled: document.getElementById('auditEnabled').checked,
                auditRetention: document.getElementById('auditRetention').value,
                auditLevel: document.getElementById('auditLevel').value,
                auditRealTime: document.getElementById('auditRealTime').checked,
                auditEmailAlerts: document.getElementById('auditEmailAlerts').checked,
                auditSensitiveActions: document.getElementById('auditSensitiveActions').checked
            };
            
            // Validate audit retention
            if (formData.auditRetention < 7) {
                SwalHelper.error('การเก็บข้อมูล Audit Log ต้องไม่น้อยกว่า 7 วัน');
                return;
            }
            
            console.log('Saving audit settings:', formData);
            SwalHelper.loading('กำลังบันทึกการตั้งค่า Audit Log...');
            
            // Save to localStorage
            localStorage.setItem('auditSettings', JSON.stringify(formData));
            
            // Simulate API call
            setTimeout(() => {
                SwalHelper.close();
                SwalHelper.success('บันทึกการตั้งค่า Audit Log เรียบร้อยแล้ว');
            }, 1500);
        });
    }
});

// Load Audit Logs from API
function loadAuditLogs() {
    fetch('/api/audit/recent?limit=10')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAuditLogsTable(data.data);
            } else {
                console.error('Error loading audit logs:', data.message);
                showAuditLogsError();
            }
        })
        .catch(error => {
            console.error('Error fetching audit logs:', error);
            showAuditLogsError();
        });
}

// Update Audit Logs Table
function updateAuditLogsTable(logs) {
    const tbody = document.getElementById('auditLogsTable');
    tbody.innerHTML = '';
    
    if (logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มีข้อมูล Audit Log</td></tr>';
        return;
    }
    
     logs.forEach(log => {
         const row = `
             <tr>
                 <td>${log.created_at}</td>
                 <td>${log.user_name || 'N/A'}</td>
                 <td>${log.formatted_action}</td>
                 <td>${log.ip_address || 'N/A'}</td>
                 <td><span class="badge ${log.status_badge}">${log.formatted_status}</span></td>
             </tr>
         `;
         tbody.insertAdjacentHTML('beforeend', row);
     });
}

// Show Audit Logs Error
function showAuditLogsError() {
    const tbody = document.getElementById('auditLogsTable');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger"><i class="fas fa-exclamation-triangle me-2"></i>ไม่สามารถโหลดข้อมูลได้</td></tr>';
}

// Export Audit Logs Function
function exportAuditLogs() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะส่งออก Audit Logs?', 'ยืนยันการส่งออก', function() {
        SwalHelper.loading('กำลังส่งออก Audit Logs...');
        
        // Export audit logs from API
        fetch('/api/audit/export?format=csv')
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('ไม่สามารถส่งออกข้อมูลได้');
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'audit_logs_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                SwalHelper.close();
                SwalHelper.success('ส่งออก Audit Logs สำเร็จ!');
            })
            .catch(error => {
                SwalHelper.close();
                console.error('Error exporting audit logs:', error);
                SwalHelper.error('ไม่สามารถส่งออก Audit Logs ได้: ' + error.message);
            });
    });
}

// Clear Audit Logs Function
function clearAuditLogs() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะลบ Audit Logs ทั้งหมด?\n\nการกระทำนี้ไม่สามารถย้อนกลับได้', 'ยืนยันการลบ Audit Logs', function() {
        SwalHelper.loading('กำลังลบ Audit Logs...');
        
        // Clear audit logs from API
        fetch('/api/audit/cleanup', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                days: 0 // ลบทั้งหมด
            })
        })
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                SwalHelper.success('ลบ Audit Logs สำเร็จ!\n\nลบทั้งหมด ' + data.deleted_count + ' รายการ');
                
                // Refresh audit logs table
                loadAuditLogs();
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถลบ Audit Logs ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error clearing audit logs:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการลบ Audit Logs');
        });
    });
}

// Load Audit Settings from localStorage
function loadAuditSettings() {
    const auditSettings = localStorage.getItem('auditSettings');
    if (auditSettings) {
        const data = JSON.parse(auditSettings);
        document.getElementById('auditEnabled').checked = data.auditEnabled || false;
        document.getElementById('auditRetention').value = data.auditRetention || '90';
        document.getElementById('auditLevel').value = data.auditLevel || 'basic';
        document.getElementById('auditRealTime').checked = data.auditRealTime || false;
        document.getElementById('auditEmailAlerts').checked = data.auditEmailAlerts || false;
        document.getElementById('auditSensitiveActions').checked = data.auditSensitiveActions || false;
        
        console.log('Audit settings loaded');
    }
}
