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
                maintenanceMode: document.getElementById('maintenanceMode').checked,
                debugMode: document.getElementById('debugMode').checked
            };
            
            console.log('Saving general settings:', formData);
            SwalHelper.loading('กำลังบันทึกการตั้งค่าทั่วไป...');
            
            // Save to localStorage
            localStorage.setItem('generalSettings', JSON.stringify(formData));
            
            // Simulate API call
            setTimeout(() => {
                SwalHelper.close();
                SwalHelper.success('บันทึกการตั้งค่าทั่วไปเรียบร้อยแล้ว');
                
                // Update page title if site name changed
                if (formData.siteName) {
                    document.title = formData.siteName + ' - Admin Panel';
                }
            }, 1500);
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
        document.getElementById('maintenanceMode').checked = data.maintenanceMode || false;
        document.getElementById('debugMode').checked = data.debugMode || false;
        
        // Update page title if site name exists
        if (data.siteName) {
            document.title = data.siteName + ' - Admin Panel';
        }
        
        console.log('General settings loaded');
    }
}
