// ========================================
// GENERAL SETTINGS FUNCTIONS
// ========================================

// General Settings Form
document.addEventListener('DOMContentLoaded', function() {
    const generalForm = document.getElementById('generalSettingsForm');
    if (generalForm) {
        generalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                siteName: document.getElementById('siteName').value,
                siteUrl: document.getElementById('siteUrl').value,
                timezone: document.getElementById('timezone').value,
                language: document.getElementById('language').value,
                siteEnabled: document.getElementById('siteEnabled').checked,
                maintenanceMode: document.getElementById('maintenanceMode').checked,
                debugMode: document.getElementById('debugMode').checked,
                autoSave: document.getElementById('autoSave').checked,
                notifications: document.getElementById('notifications').checked,
                analytics: document.getElementById('analytics').checked,
                updates: document.getElementById('updates').checked
            };
            
            SwalHelper.loading('กำลังบันทึกการตั้งค่าทั่วไป...');
            
            // Save to localStorage
            localStorage.setItem('generalSettings', JSON.stringify(formData));
            
            // Send timezone to server to update cache
            fetch('/api/system/timezone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    timezone: formData.timezone
                })
            })
            .then(response => response.json())
            .then(data => {
                SwalHelper.close();
                SwalHelper.success('บันทึกการตั้งค่าทั่วไปเรียบร้อยแล้ว');
                
                // Update page title if site name changed
                if (formData.siteName) {
                    document.title = formData.siteName + ' - Admin Panel';
                }
                
                // Dispatch timezone change event for other components
                window.dispatchEvent(new CustomEvent('timezoneChanged', {
                    detail: { timezone: formData.timezone }
                }));
            })
            .catch(error => {
                SwalHelper.close();
                SwalHelper.error('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า');
            });
        });
    }
});

// Load General Settings from localStorage
function loadGeneralSettings() {
    const generalSettings = localStorage.getItem('generalSettings');
    if (generalSettings) {
        const data = JSON.parse(generalSettings);
        document.getElementById('siteName').value = data.siteName || '';
        document.getElementById('siteUrl').value = data.siteUrl || '';
        document.getElementById('timezone').value = data.timezone || 'Asia/Bangkok';
        document.getElementById('language').value = data.language || 'th';
        document.getElementById('siteEnabled').checked = data.siteEnabled !== undefined ? data.siteEnabled : true;
        document.getElementById('maintenanceMode').checked = data.maintenanceMode || false;
        document.getElementById('debugMode').checked = data.debugMode !== undefined ? data.debugMode : true;
        document.getElementById('autoSave').checked = data.autoSave !== undefined ? data.autoSave : true;
        document.getElementById('notifications').checked = data.notifications !== undefined ? data.notifications : true;
        document.getElementById('analytics').checked = data.analytics !== undefined ? data.analytics : true;
        document.getElementById('updates').checked = data.updates !== undefined ? data.updates : true;
        
        // Update page title if site name exists
        if (data.siteName) {
            document.title = data.siteName + ' - Admin Panel';
        }
        
    }
}

// Initialize General Settings when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadGeneralSettings();
});
