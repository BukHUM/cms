/**
 * Backup Settings JavaScript
 * Handles backup settings form, backup creation, and history management
 */

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

        // Create backup button
        const createBackupBtn = document.querySelector('[onclick="createBackup()"]');
        if (createBackupBtn) {
            createBackupBtn.addEventListener('click', (e) => this.createBackup(e));
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
            console.error('Error loading backup settings:', error);
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

    handleBackupEnabledToggle(event) {
        this.updateBackupEnabledLabel(event.target.checked);
    }

    async handleFormSubmit(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const settings = {
            backupEnabled: document.getElementById('backupEnabled').checked,
            backupFrequency: document.getElementById('backupFrequency').value,
            backupRetention: parseInt(document.getElementById('backupRetention').value),
            backupLocation: document.getElementById('backupLocation').value,
            backupType: document.getElementById('backupType').value,
            backupTime: document.getElementById('backupTime').value
        };

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
                this.showAlert(data.message || 'เกิดข้อผิดพลาดในการบันทึกการตั้งค่า', 'error');
            }
        } catch (error) {
            console.error('Error saving backup settings:', error);
            this.showAlert('ไม่สามารถบันทึกการตั้งค่าได้', 'error');
        }
    }

    async createBackup(event) {
        if (event) {
            event.preventDefault();
        }

        const backupType = document.getElementById('backupType').value;
        
        // Show loading state
        const createBtn = document.querySelector('[onclick="createBackup()"]');
        const originalText = createBtn.innerHTML;
        createBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังสร้างสำรองข้อมูล...';
        createBtn.disabled = true;

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
                this.showAlert(data.message || 'เกิดข้อผิดพลาดในการสร้างสำรองข้อมูล', 'error');
            }
        } catch (error) {
            console.error('Error creating backup:', error);
            this.showAlert('ไม่สามารถสร้างสำรองข้อมูลได้', 'error');
        } finally {
            // Restore button state
            createBtn.innerHTML = originalText;
            createBtn.disabled = false;
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
            
            // Hide loading
            loadingRow.style.display = 'none';

            if (data.success && data.data && data.data.length > 0) {
                this.renderBackupHistory(data.data);
            } else {
                emptyRow.style.display = 'table-row';
            }
        } catch (error) {
            console.error('Error loading backup history:', error);
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
                <td>${this.formatFileSize(backup.file_size)}</td>
                <td>
                    <span class="badge ${this.getStatusBadgeClass(backup.status)}">
                        ${this.getStatusText(backup.status)}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="backupSettings.downloadBackup('${backup.id}')" ${backup.status !== 'completed' ? 'disabled' : ''}>
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="backupSettings.deleteBackup('${backup.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
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

            const data = await response.json();

            if (data.success) {
                // Create download link
                const link = document.createElement('a');
                link.href = data.data.downloadUrl;
                link.download = data.data.filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                this.showAlert('เริ่มดาวน์โหลดสำรองข้อมูล', 'success');
            } else {
                this.showAlert(data.message || 'ไม่สามารถดาวน์โหลดสำรองข้อมูลได้', 'error');
            }
        } catch (error) {
            console.error('Error downloading backup:', error);
            this.showAlert('ไม่สามารถดาวน์โหลดสำรองข้อมูลได้', 'error');
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
            console.error('Error deleting backup:', error);
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
            'pending': 'bg-secondary'
        };
        return statusClasses[status] || 'bg-secondary';
    }

    getStatusText(status) {
        const statusTexts = {
            'completed': 'สำเร็จ',
            'failed': 'ล้มเหลว',
            'in_progress': 'กำลังดำเนินการ',
            'pending': 'รอดำเนินการ'
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

// Global function for backward compatibility
function createBackup() {
    if (window.backupSettings) {
        window.backupSettings.createBackup();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.backupSettings = new BackupSettings();
});