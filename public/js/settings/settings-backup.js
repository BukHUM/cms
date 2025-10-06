/**
 * Backup Settings JavaScript
 * จัดการการตั้งค่าสำรองข้อมูลของระบบ
 */

class BackupSettings {
    constructor() {
        this.form = document.getElementById('backupSettingsForm');
        this.isLoading = false;
        this.isCreatingBackup = false;
        this.backupHistory = [];
        
        this.init();
    }

    /**
     * Initialize Backup Settings
     */
    init() {
        if (!this.form) return;

        this.loadSettings();
        this.bindEvents();
        this.loadBackupHistory();
        this.setupBackupSchedule();
        this.forceUpdateSwitchLabels(); // Force update labels on init
    }

    /**
     * Force update all switch labels
     */
    forceUpdateSwitchLabels() {
        const switches = this.form.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switchElement => {
            const label = document.getElementById(switchElement.id + 'Label');
            if (label) {
                this.updateSwitchLabel(switchElement.id, switchElement.checked);
            }
        });
    }

    /**
     * Load settings from server
     */
    async loadSettings() {
        try {
            this.showLoading(true);
            
            const response = await fetch('/admin/settings/backup', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่าสำรองข้อมูลสำเร็จ'); // Disabled auto success message
                this.updateBackupSchedule();
            } else {
                this.showError(result.message || 'ไม่สามารถโหลดการตั้งค่าสำรองข้อมูลได้');
            }

        } catch (error) {
            console.error('Error loading backup settings:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่าสำรองข้อมูล');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Populate form with settings data
     */
    populateForm(data) {
        // Basic backup settings
        this.setValue('backupFrequency', data.backupFrequency);
        this.setValue('backupTime', data.backupTime);
        this.setValue('backupRetention', data.backupRetention);
        this.setValue('backupLocation', data.backupLocation);

        // Boolean settings
        this.setCheckbox('backupEnabled', data.backupEnabled);

        // Update backup schedule display
        this.updateBackupSchedule();
    }

    /**
     * Set input value
     */
    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || '';
        }
    }

    /**
     * Set checkbox value
     */
    setCheckbox(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.checked = Boolean(value);
            this.updateSwitchLabel(id, Boolean(value));
        }
    }

    /**
     * Update switch label text
     */
    updateSwitchLabel(id, checked) {
        const label = document.getElementById(id + 'Label');
        if (label) {
            const labels = {
                'backupEnabled': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' }
            };

            if (labels[id]) {
                label.textContent = labels[id][checked ? 'true' : 'false'];
                
                // Force inline styles for immediate effect
                if (checked) {
                    label.style.color = '#28a745';
                    label.style.fontWeight = '600';
                } else {
                    label.style.color = '#dc3545';
                    label.style.fontWeight = '600';
                }
                
                // Also add CSS classes for styling
                label.classList.remove('enabled', 'disabled');
                if (checked) {
                    label.classList.add('enabled');
                } else {
                    label.classList.add('disabled');
                }
            }
        }
    }

    /**
     * Bind form events
     */
    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveSettings();
        });

        // Switch change events
        const switches = this.form.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switchElement => {
            switchElement.addEventListener('change', (e) => {
                this.updateSwitchLabel(e.target.id, e.target.checked);
                this.updateBackupSchedule();
            });
        });

        // Input change events
        const inputs = this.form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updateBackupSchedule();
            });
        });

        // Create backup button
        const createBackupBtn = this.form.querySelector('button[onclick="createBackup()"]');
        if (createBackupBtn) {
            createBackupBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.createBackup();
            });
        }

        // Backup history actions
        this.bindBackupHistoryEvents();
    }

    /**
     * Bind backup history events
     */
    bindBackupHistoryEvents() {
        // This will be called after backup history is loaded
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('backup-download-btn')) {
                e.preventDefault();
                const backupId = e.target.dataset.backupId;
                this.downloadBackup(backupId);
            }

            if (e.target.classList.contains('backup-delete-btn')) {
                e.preventDefault();
                const backupId = e.target.dataset.backupId;
                this.deleteBackup(backupId);
            }
        });
    }

    /**
     * Setup backup schedule display
     */
    setupBackupSchedule() {
        // This will be populated when settings are loaded
    }

    /**
     * Update backup schedule display
     */
    updateBackupSchedule() {
        const frequency = document.getElementById('backupFrequency')?.value || 'daily';
        const time = document.getElementById('backupTime')?.value || '02:00';
        const enabled = document.getElementById('backupEnabled')?.checked || false;

        let scheduleText = '';
        const frequencyText = {
            'daily': 'รายวัน',
            'weekly': 'รายสัปดาห์',
            'monthly': 'รายเดือน'
        };

        if (enabled) {
            scheduleText = `การสำรองข้อมูลจะทำงาน${frequencyText[frequency]} เวลา ${time}`;
        } else {
            scheduleText = 'การสำรองข้อมูลอัตโนมัติถูกปิดใช้งาน';
        }

        this.displayBackupSchedule(scheduleText, enabled);
    }

    /**
     * Display backup schedule
     */
    displayBackupSchedule(scheduleText, enabled) {
        let container = document.getElementById('backupSchedule');
        if (!container) {
            container = document.createElement('div');
            container.id = 'backupSchedule';
            container.className = 'mt-3';
            this.form.appendChild(container);
        }

        const alertClass = enabled ? 'alert-info' : 'alert-warning';
        const iconClass = enabled ? 'fas fa-clock' : 'fas fa-exclamation-triangle';

        container.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="${iconClass} me-2"></i>
                ${scheduleText}
            </div>
        `;
    }

    /**
     * Load backup history
     */
    async loadBackupHistory() {
        try {
            const response = await fetch('/admin/settings/backup/history', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.backupHistory = result.data;
                this.displayBackupHistory();
            } else {
                // console.warn('Could not load backup history:', result.message); // Hidden
                this.displayBackupHistory(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading backup history:', error);
            this.displayBackupHistory(); // Show empty state
        }
    }

    /**
     * Display backup history
     */
    displayBackupHistory() {
        const tableBody = document.getElementById('auditLogsTable');
        if (!tableBody) return;

        if (this.backupHistory.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        ยังไม่มีประวัติการสำรองข้อมูล
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        this.backupHistory.forEach(backup => {
            const statusBadge = this.getStatusBadge(backup.status);
            const sizeFormatted = this.formatFileSize(backup.size);
            
            html += `
                <tr>
                    <td>${backup.created_at}</td>
                    <td>${sizeFormatted}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary backup-download-btn me-1" 
                                data-backup-id="${backup.id}">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger backup-delete-btn" 
                                data-backup-id="${backup.id}">
                            <i class="fas fa-trash"></i>
                        </button>
            </td>
        </tr>
    `;
        });

        tableBody.innerHTML = html;
    }

    /**
     * Get status badge HTML
     */
    getStatusBadge(status) {
        const badges = {
            'success': '<span class="badge bg-success">สำเร็จ</span>',
            'failed': '<span class="badge bg-danger">ล้มเหลว</span>',
            'in_progress': '<span class="badge bg-warning">กำลังดำเนินการ</span>',
            'pending': '<span class="badge bg-secondary">รอดำเนินการ</span>'
        };

        return badges[status] || '<span class="badge bg-secondary">ไม่ทราบสถานะ</span>';
    }

    /**
     * Format file size
     */
    formatFileSize(bytes) {
        if (!bytes) return '0 B';
        
        const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        
        return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
    }

    /**
     * Create backup immediately
     */
    async createBackup() {
        if (this.isCreatingBackup) return;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'ยืนยันการสร้างสำรองข้อมูล',
                text: 'คุณต้องการสร้างสำรองข้อมูลทันทีหรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, สร้างสำรองข้อมูล',
                cancelButtonText: 'ยกเลิก'
            });

            if (!result.isConfirmed) return;
        } else {
            if (!confirm('คุณต้องการสร้างสำรองข้อมูลทันทีหรือไม่?')) {
                return;
            }
        }

        try {
            this.isCreatingBackup = true;
            this.showCreateBackupLoading(true);

            const response = await fetch('/admin/settings/backup/create', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'สร้างสำรองข้อมูลสำเร็จ');
                this.loadBackupHistory(); // Refresh history
            } else {
                this.showError(result.message || 'ไม่สามารถสร้างสำรองข้อมูลได้');
            }

        } catch (error) {
            console.error('Error creating backup:', error);
            this.showError('เกิดข้อผิดพลาดในการสร้างสำรองข้อมูล');
        } finally {
            this.isCreatingBackup = false;
            this.showCreateBackupLoading(false);
        }
    }

    /**
     * Download backup
     */
    async downloadBackup(backupId) {
        try {
            const response = await fetch(`/admin/settings/backup/download/${backupId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `backup_${backupId}.sql`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                this.showSuccess('ดาวน์โหลดสำรองข้อมูลสำเร็จ');
            } else {
                this.showError('ไม่สามารถดาวน์โหลดสำรองข้อมูลได้');
            }

        } catch (error) {
            console.error('Error downloading backup:', error);
            this.showError('เกิดข้อผิดพลาดในการดาวน์โหลดสำรองข้อมูล');
        }
    }

    /**
     * Delete backup
     */
    async deleteBackup(backupId) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'ยืนยันการลบ',
                text: 'คุณต้องการลบสำรองข้อมูลนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบ',
                cancelButtonText: 'ยกเลิก'
            });

            if (!result.isConfirmed) return;
        } else {
            if (!confirm('คุณต้องการลบสำรองข้อมูลนี้หรือไม่?')) {
                return;
            }
        }

        try {
            const response = await fetch(`/admin/settings/backup/delete/${backupId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'ลบสำรองข้อมูลสำเร็จ');
                this.loadBackupHistory(); // Refresh history
            } else {
                this.showError(result.message || 'ไม่สามารถลบสำรองข้อมูลได้');
            }

        } catch (error) {
            console.error('Error deleting backup:', error);
            this.showError('เกิดข้อผิดพลาดในการลบสำรองข้อมูล');
        }
    }

    /**
     * Save settings to server
     */
    async saveSettings() {
        if (this.isLoading) return;

        try {
            this.isLoading = true;
            this.showLoading(true);

            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData.entries());

            // Convert checkbox values to boolean
            data.backupEnabled = formData.has('backupEnabled');

            const response = await fetch('/admin/settings/backup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'บันทึกการตั้งค่าสำรองข้อมูลสำเร็จ');
                this.updateBackupSchedule();
            } else {
                this.showError(result.message || 'ไม่สามารถบันทึกการตั้งค่าสำรองข้อมูลได้');
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }

        } catch (error) {
            console.error('Error saving backup settings:', error);
            this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่าสำรองข้อมูล');
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }

    /**
     * Show validation errors
     */
    showValidationErrors(errors) {
        // Clear previous errors
        this.clearValidationErrors();

        // Show new errors
        Object.keys(errors).forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.classList.add('is-invalid');
                
                // Create error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                
                element.parentNode.appendChild(errorDiv);
            }
        });
    }

    /**
     * Clear validation errors
     */
    clearValidationErrors() {
        const invalidElements = this.form.querySelectorAll('.is-invalid');
        invalidElements.forEach(element => {
            element.classList.remove('is-invalid');
        });

        const errorMessages = this.form.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(message => {
            message.remove();
        });
    }

    /**
     * Show loading state
     */
    showLoading(show) {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            if (show) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังบันทึก...';
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>บันทึกการตั้งค่า';
            }
        }
    }

    /**
     * Show create backup loading state
     */
    showCreateBackupLoading(show) {
        const createBtn = this.form.querySelector('button[onclick="createBackup()"]');
        if (createBtn) {
            if (show) {
                createBtn.disabled = true;
                createBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังสร้างสำรองข้อมูล...';
            } else {
                createBtn.disabled = false;
                createBtn.innerHTML = '<i class="fas fa-database me-2"></i>สร้างสำรองข้อมูลทันที';
            }
        }
    }

    /**
     * Show success message
     */
    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert(message);
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: message
            });
        } else {
            alert(message);
        }
    }

    /**
     * Reset form to default values
     */
    resetToDefaults() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'ยืนยันการรีเซ็ต',
                text: 'คุณต้องการรีเซ็ตการตั้งค่าสำรองข้อมูลเป็นค่าเริ่มต้นหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, รีเซ็ต',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loadSettings();
                }
            });
        } else {
            if (confirm('คุณต้องการรีเซ็ตการตั้งค่าสำรองข้อมูลเป็นค่าเริ่มต้นหรือไม่?')) {
                this.loadSettings();
            }
        }
    }
}

// Global function for create backup button
function createBackup() {
    if (window.backupSettings) {
        window.backupSettings.createBackup();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('backupSettingsForm')) {
        window.backupSettings = new BackupSettings();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BackupSettings;
}