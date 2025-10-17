/**
 * Backup Settings JavaScript - Version 2.0
 * Handles backup settings form, backup creation, and history management
 * Updated: 2025-10-16 - Simplified backup system (removed view and restore functions)
 */

// Check if BackupSettings class already exists
if (typeof BackupSettings !== 'undefined') {
    // Skip initialization if already exists
} else {
    class BackupSettings {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadSettings();
        this.loadBackupHistory();
    }

    bindEvents() {
        // Form submission
        const form = document.getElementById('backupSettingsForm');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Backup enabled toggle
        const backupEnabled = document.getElementById('backupEnabled');
        if (backupEnabled) {
            backupEnabled.addEventListener('change', (e) => this.handleBackupEnabledToggle(e));
        }

        // Create backup buttons
        const createBackupBtn = document.getElementById('createBackupBtn');
        const createBackupBtnMobile = document.getElementById('createBackupBtnMobile');
        
        if (createBackupBtn) {
            createBackupBtn.addEventListener('click', (e) => this.createBackup(e));
        }
        if (createBackupBtnMobile) {
            createBackupBtnMobile.addEventListener('click', (e) => this.createBackup(e));
        }
    }

    async loadSettings() {
        try {
            const response = await fetch('/admin/settings/backup', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load backup settings');
            }

            const data = await response.json();
            if (data.success) {
                this.populateForm(data.data);
            }
        } catch (error) {
            this.showAlert('ไม่สามารถโหลดการตั้งค่าได้', 'error');
        }
    }

    populateForm(settings) {
        // Populate form fields
        const fields = {
            'backupEnabled': settings.backupEnabled,
            'backupFrequency': settings.backupFrequency,
            'backupRetention': settings.backupRetention,
            'backupLocation': settings.backupLocation,
            'backupType': settings.backupType || 'database',
            'backupTime': settings.backupTime || '02:00'
        };

        Object.entries(fields).forEach(([fieldId, value]) => {
            const field = document.getElementById(fieldId);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = value;
                } else {
                    field.value = value;
                }
            }
        });

        // Update backup enabled label
        this.updateBackupEnabledLabel(settings.backupEnabled);
    }

    updateBackupEnabledLabel(enabled) {
        const label = document.getElementById('backupEnabledLabel');
        if (label) {
            label.textContent = enabled ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        }
    }

    async handleBackupEnabledToggle(event) {
        this.updateBackupEnabledLabel(event.target.checked);
        
        // Save the setting immediately when toggled
        try {
            const settings = {
                backupEnabled: event.target.checked
            };
            
            const response = await fetch('/admin/settings/backup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(settings)
            });

            const data = await response.json();
            
            if (data.success) {
                this.showAlert(data.message || 'บันทึกการตั้งค่าสำเร็จ', 'success');
            } else {
                this.showAlert(data.message || 'ไม่สามารถบันทึกการตั้งค่าได้', 'error');
                // Revert the toggle if save failed
                event.target.checked = !event.target.checked;
                this.updateBackupEnabledLabel(event.target.checked);
            }
        } catch (error) {
            this.showAlert('ไม่สามารถบันทึกการตั้งค่าได้', 'error');
            // Revert the toggle if save failed
            event.target.checked = !event.target.checked;
            this.updateBackupEnabledLabel(event.target.checked);
        }
    }

    async handleFormSubmit(event) {
        event.preventDefault();

        // Use FormData to collect form data
        const formData = new FormData(event.target);
        
        // Convert FormData to object for JSON submission
        const settings = {};
        for (let [key, value] of formData.entries()) {
            if (key === 'backupEnabled') {
                settings[key] = value === 'on';
            } else if (key === 'backupRetention') {
                settings[key] = parseInt(value);
            } else {
                settings[key] = value;
            }
        }

        // Debug: Log the settings being sent

        try {
            const response = await fetch('/admin/settings/backup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(settings)
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert(data.message, 'success');
            } else {
                // Show validation errors if available
                if (data.errors) {
                    let errorMessage = 'ข้อมูลไม่ถูกต้อง:\n';
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        errorMessage += `- ${field}: ${messages.join(', ')}\n`;
                    });
                    this.showAlert(errorMessage, 'error');
                } else {
                    this.showAlert(data.message || 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่า', 'error');
                }
            }
        } catch (error) {
            this.showAlert('ไม่สามารถบันทึกการตั้งค่าได้', 'error');
        }
    }

    async createBackup(event) {
        if (event) {
            event.preventDefault();
        }

        const backupType = document.getElementById('backupType').value;
        
        // Debug: Log the backup type being sent
        
        // Validate backup type
        if (!backupType) {
            this.showAlert('กรุณาเลือกประเภทการสำรองข้อมูล', 'error');
            return;
        }
        
        // Show loading state
        const createBackupBtn = document.getElementById('createBackupBtn');
        const createBackupBtnMobile = document.getElementById('createBackupBtnMobile');
        
        // Store original content for restoration
        let originalText = '';
        let originalTextMobile = '';
        
        // Disable both buttons
        if (createBackupBtn) {
            originalText = createBackupBtn.innerHTML;
            createBackupBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังสร้างสำรองข้อมูล...';
            createBackupBtn.disabled = true;
        }
        if (createBackupBtnMobile) {
            originalTextMobile = createBackupBtnMobile.innerHTML;
            createBackupBtnMobile.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังสร้างสำรองข้อมูล...';
            createBackupBtnMobile.disabled = true;
        }

        try {
            const response = await fetch('/admin/settings/backup/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ backupType })
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert(data.message, 'success');
                // Reload backup history
                this.loadBackupHistory();
            } else {
                // Show validation errors if available
                if (data.errors) {
                    let errorMessage = 'ข้อมูลไม่ถูกต้อง:\n';
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        errorMessage += `- ${field}: ${messages.join(', ')}\n`;
                    });
                    this.showAlert(errorMessage, 'error');
                } else {
                    this.showAlert(data.message || 'เกิดข้อผิดพลาดในการสร้างสำรองข้อมูล', 'error');
                }
            }
        } catch (error) {
            this.showAlert('ไม่สามารถสร้างสำรองข้อมูลได้', 'error');
        } finally {
            // Restore button state
            if (createBackupBtn && originalText) {
                createBackupBtn.innerHTML = originalText;
                createBackupBtn.disabled = false;
            }
            if (createBackupBtnMobile && originalTextMobile) {
                createBackupBtnMobile.innerHTML = originalTextMobile;
                createBackupBtnMobile.disabled = false;
            }
        }
    }

    async loadBackupHistory() {
        const loadingRow = document.getElementById('backupLoadingRow');
        const emptyRow = document.getElementById('backupEmptyRow');
        const historyBody = document.getElementById('backupHistoryBody');

        // Show loading
        loadingRow.style.display = 'table-row';
        emptyRow.style.display = 'none';

        try {
            const response = await fetch('/admin/settings/backup/history', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load backup history');
            }

            const data = await response.json();
            
            // Debug: Log the received data
            
            // Hide loading
            loadingRow.style.display = 'none';

            if (data.success && data.data && data.data.length > 0) {
                this.renderBackupHistory(data.data);
            } else {
                emptyRow.style.display = 'table-row';
            }
        } catch (error) {
            loadingRow.style.display = 'none';
            emptyRow.style.display = 'table-row';
            this.showAlert('ไม่สามารถโหลดประวัติการสำรองข้อมูลได้', 'error');
        }
    }

    renderBackupHistory(backups) {
        
        const historyBody = document.getElementById('backupHistoryBody');
        const emptyRow = document.getElementById('backupEmptyRow');
        
        // Clear existing rows except loading and empty rows
        const existingRows = historyBody.querySelectorAll('tr:not(#backupLoadingRow):not(#backupEmptyRow)');
        existingRows.forEach(row => row.remove());

        // Hide empty row
        emptyRow.style.display = 'none';

        // Render backup history
        backups.forEach(backup => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${this.formatDate(backup.created_at)}</td>
                <td>
                    <span class="badge bg-info">
                        ${this.getBackupTypeText(backup.type)}
                    </span>
                </td>
                <td>${this.formatFileSize(backup.file_size)}</td>
                <td>
                    <span class="badge ${this.getStatusBadgeClass(backup.status)}">
                        ${this.getStatusText(backup.status)}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        ${backup.status === 'file_not_found' ? 
                            `<button class="btn-action btn-delete" onclick="backupSettings.deleteBackup('${backup.id}')" title="ลบประวัติ">
                                <i class="fas fa-trash"></i>
                            </button>` :
                            `<button class="btn-action btn-download" onclick="backupSettings.downloadBackup('${backup.id}')" ${backup.status !== 'completed' ? 'disabled' : ''} title="ดาวน์โหลด">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn-action btn-delete" onclick="backupSettings.deleteBackup('${backup.id}')" title="ลบ">
                                <i class="fas fa-trash"></i>
                            </button>`
                        }
                    </div>
                </td>
            `;
            historyBody.appendChild(row);
        });
    }

    async downloadBackup(backupId) {
        try {
            const response = await fetch(`/admin/settings/backup/download/${backupId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            // Check if response is successful
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'ไม่สามารถดาวน์โหลดสำรองข้อมูลได้');
            }

            // Check if response is a file download
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                // Handle JSON error response
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message || 'ไม่สามารถดาวน์โหลดสำรองข้อมูลได้');
                }
            } else {
                // Handle file download
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                
                // Get filename from response headers or use backup ID
                const contentDisposition = response.headers.get('content-disposition');
                let filename = `backup_${backupId}.sql`;
                
                if (contentDisposition) {
                    const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                    if (filenameMatch) {
                        filename = filenameMatch[1];
                    }
                }
                
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
                
                this.showAlert('เริ่มดาวน์โหลดสำรองข้อมูล', 'success');
            }

        } catch (error) {
            this.showAlert('ไม่สามารถดาวน์โหลดสำรองข้อมูลได้: ' + error.message, 'error');
        }
    }

    async deleteBackup(backupId) {
        if (!confirm('คุณแน่ใจหรือไม่ที่จะลบสำรองข้อมูลนี้?')) {
            return;
        }

        try {
            const response = await fetch(`/admin/settings/backup/delete/${backupId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert(data.message, 'success');
                // Reload backup history
                this.loadBackupHistory();
            } else {
                this.showAlert(data.message || 'ไม่สามารถลบสำรองข้อมูลได้', 'error');
            }
        } catch (error) {
            this.showAlert('ไม่สามารถลบสำรองข้อมูลได้', 'error');
        }
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('th-TH', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    getStatusBadgeClass(status) {
        const statusClasses = {
            'completed': 'bg-success',
            'failed': 'bg-danger',
            'in_progress': 'bg-warning',
            'pending': 'bg-secondary',
            'file_not_found': 'bg-danger'
        };
        return statusClasses[status] || 'bg-secondary';
    }

    getBackupTypeText(type) {
        const typeTexts = {
            'database': 'ฐานข้อมูล',
            'files': 'ไฟล์',
            'both': 'ทั้งหมด'
        };
        return typeTexts[type] || type;
    }

    getStatusText(status) {
        const statusTexts = {
            'completed': 'สำเร็จ',
            'failed': 'ล้มเหลว',
            'in_progress': 'กำลังดำเนินการ',
            'pending': 'รอดำเนินการ',
            'file_not_found': 'ไม่พบไฟล์'
        };
        return statusTexts[status] || 'ไม่ทราบสถานะ';
    }

    showAlert(message, type = 'info') {
        // Use SweetAlert if available, otherwise use browser alert
        if (typeof Swal !== 'undefined') {
            const iconMap = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info'
            };

            Swal.fire({
                icon: iconMap[type] || 'info',
                title: type === 'success' ? 'สำเร็จ' : type === 'error' ? 'เกิดข้อผิดพลาด' : 'แจ้งเตือน',
                text: message,
                timer: type === 'success' ? 3000 : null,
                showConfirmButton: type !== 'success'
            });
        } else {
            alert(message);
        }
    }
}

// Global function for backward compatibility (deprecated)
// Use event listeners instead of onclick attributes
function createBackup() {
    if (window.backupSettings) {
        window.backupSettings.createBackup();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (!window.backupSettings) {
        window.backupSettings = new BackupSettings();
    }
});
}