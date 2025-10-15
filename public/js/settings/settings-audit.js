/**
 * Audit Settings JavaScript
 * จัดการการตั้งค่า Audit Log ของระบบ
 */

class AuditSettings {
    constructor() {
        this.form = document.getElementById('auditSettingsForm');
        this.isLoading = false;
        this.isExportingLogs = false;
        this.isClearingLogs = false;
        this.auditLogs = [];
        
        this.init();
    }

    /**
     * Initialize Audit Settings
     */
    init() {
        if (!this.form) return;

        this.loadSettings();
        this.bindEvents();
        this.loadAuditLogs();
        this.setupAuditStatistics();
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
            // Don't show loading spinner when loading settings on page load
            // this.showLoading(true);
            
            const response = await fetch('/admin/settings/audit', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่า Audit Log สำเร็จ'); // Disabled auto success message
                this.updateAuditStatistics();
            } else {
                this.showError(result.message || 'ไม่สามารถโหลดการตั้งค่า Audit Log ได้');
            }

        } catch (error) {
            console.error('Error loading audit settings:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่า Audit Log');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Populate form with settings data
     */
    populateForm(data) {
        // Basic audit settings
        this.setCheckbox('auditEnabled', data.auditEnabled);
        this.setValue('auditRetention', data.auditRetention);
        this.setValue('auditLevel', data.auditLevel);

        // Update audit level description
        this.updateAuditLevelDescription(data.auditLevel);
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
                'auditEnabled': { true: 'บันทึกการใช้งานระบบ', false: 'ไม่บันทึกการใช้งานระบบ' }
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
     * Update audit level description
     */
    updateAuditLevelDescription(level) {
        const descriptions = {
            'basic': 'บันทึกเฉพาะการเข้าสู่ระบบ, ออกจากระบบ, และการเปลี่ยนแปลงข้อมูลสำคัญ',
            'detailed': 'บันทึกทุกการกระทำของผู้ใช้ รวมถึงการดูข้อมูล',
            'comprehensive': 'บันทึกครบถ้วน รวมถึงการเข้าถึงข้อมูล, การค้นหา, และการกรองข้อมูล'
        };

        let container = document.getElementById('auditLevelDescription');
        if (!container) {
            container = document.createElement('div');
            container.id = 'auditLevelDescription';
            container.className = 'mt-2';
            const levelSelect = document.getElementById('auditLevel');
            if (levelSelect) {
                levelSelect.parentNode.appendChild(container);
            }
        }

        container.innerHTML = `
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                ${descriptions[level] || descriptions['basic']}
            </small>
        `;
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
                this.updateAuditStatistics();
            });
        });

        // Audit level change event
        const levelSelect = document.getElementById('auditLevel');
        if (levelSelect) {
            levelSelect.addEventListener('change', (e) => {
                this.updateAuditLevelDescription(e.target.value);
                this.updateAuditStatistics();
            });
        }

        // Retention change event
        const retentionInput = document.getElementById('auditRetention');
        if (retentionInput) {
            retentionInput.addEventListener('change', () => {
                this.updateAuditStatistics();
            });
        }

        // Export logs button
        const exportBtn = this.form.querySelector('button[onclick="exportAuditLogs()"]');
        if (exportBtn) {
            exportBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.exportAuditLogs();
            });
        }

        // Clear logs button
        const clearBtn = this.form.querySelector('button[onclick="clearAuditLogs()"]');
        if (clearBtn) {
            clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.clearAuditLogs();
            });
        }

        // Audit logs refresh
        this.setupAuditLogsRefresh();
    }

    /**
     * Setup audit logs refresh
     */
    setupAuditLogsRefresh() {
        // Auto-refresh every 30 seconds
        setInterval(() => {
            if (document.getElementById('auditLogsTable')) {
                this.loadAuditLogs(true); // Silent refresh
            }
        }, 30000);
    }

    /**
     * Load audit logs
     */
    async loadAuditLogs(silent = false) {
        try {
            if (!silent) this.showAuditLogsLoading(true);
            
            const response = await fetch('/admin/settings/audit/logs', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.auditLogs = result.data;
                this.displayAuditLogs();
            } else {
                // console.warn('Could not load audit logs:', result.message); // Hidden
                this.displayAuditLogs(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading audit logs:', error);
            this.displayAuditLogs(); // Show empty state
        } finally {
            if (!silent) this.showAuditLogsLoading(false);
        }
    }

    /**
     * Display audit logs
     */
    displayAuditLogs() {
        const tableBody = document.getElementById('auditLogsTable');
        if (!tableBody) return;

        if (this.auditLogs.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        ยังไม่มี Audit Log
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        this.auditLogs.forEach(log => {
            const statusBadge = this.getStatusBadge(log.status);
            const actionIcon = this.getActionIcon(log.action);
            
            html += `
                <tr>
                    <td>${log.created_at}</td>
                    <td>${log.user_name || 'ไม่ทราบ'}</td>
                    <td>
                        <i class="${actionIcon} me-1"></i>
                        ${log.action}
                    </td>
                    <td>${log.ip_address || '-'}</td>
                    <td>${statusBadge}</td>
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
            'warning': '<span class="badge bg-warning">คำเตือน</span>',
            'info': '<span class="badge bg-info">ข้อมูล</span>'
        };

        return badges[status] || '<span class="badge bg-secondary">ไม่ทราบสถานะ</span>';
    }

    /**
     * Get action icon
     */
    getActionIcon(action) {
        const icons = {
            'login': 'fas fa-sign-in-alt',
            'logout': 'fas fa-sign-out-alt',
            'create': 'fas fa-plus',
            'update': 'fas fa-edit',
            'delete': 'fas fa-trash',
            'view': 'fas fa-eye',
            'search': 'fas fa-search',
            'export': 'fas fa-download',
            'import': 'fas fa-upload',
            'settings': 'fas fa-cog'
        };

        return icons[action.toLowerCase()] || 'fas fa-circle';
    }

    /**
     * Setup audit statistics
     */
    setupAuditStatistics() {
        // This will be populated when settings are loaded
    }

    /**
     * Update audit statistics
     */
    updateAuditStatistics() {
        const enabled = document.getElementById('auditEnabled')?.checked || false;
        const retention = parseInt(document.getElementById('auditRetention')?.value || 90);
        const level = document.getElementById('auditLevel')?.value || 'basic';

        let statistics = '';
        
        if (enabled) {
            const levelText = {
                'basic': 'พื้นฐาน',
                'detailed': 'ละเอียด',
                'comprehensive': 'ครบถ้วน'
            };

            statistics = `
                <div class="alert alert-info">
                    <h6><i class="fas fa-chart-bar me-2"></i>สถิติการตั้งค่า Audit Log</h6>
                    <ul class="mb-0">
                        <li><strong>สถานะ:</strong> เปิดใช้งาน</li>
                        <li><strong>ระดับการบันทึก:</strong> ${levelText[level]}</li>
                        <li><strong>เก็บข้อมูล:</strong> ${retention} วัน</li>
                        <li><strong>จำนวน Log ทั้งหมด:</strong> ${this.auditLogs.length} รายการ</li>
                    </ul>
                </div>
            `;
        } else {
            statistics = `
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Audit Log ถูกปิดใช้งาน</h6>
                    <p class="mb-0">การบันทึกการใช้งานระบบถูกปิดใช้งาน ควรเปิดใช้งานเพื่อความปลอดภัย</p>
                </div>
            `;
        }

        this.displayAuditStatistics(statistics);
    }

    /**
     * Display audit statistics
     */
    displayAuditStatistics(statistics) {
        let container = document.getElementById('auditStatistics');
        if (!container) {
            container = document.createElement('div');
            container.id = 'auditStatistics';
            container.className = 'mt-3';
            this.form.appendChild(container);
        }

        container.innerHTML = statistics;
    }

    /**
     * Export audit logs
     */
    async exportAuditLogs() {
        if (this.isExportingLogs) return;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'ยืนยันการส่งออก Log',
                text: 'คุณต้องการส่งออก Audit Log ทั้งหมดหรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ส่งออก',
                cancelButtonText: 'ยกเลิก'
            });

            if (!result.isConfirmed) return;
        } else {
            if (!confirm('คุณต้องการส่งออก Audit Log ทั้งหมดหรือไม่?')) {
                return;
            }
        }

        try {
            this.isExportingLogs = true;
            this.showExportLoading(true);

            const response = await fetch('/admin/settings/audit/export', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                // Download the exported file
                if (result.data && result.data.download_url) {
                    window.open(result.data.download_url, '_blank');
                }
                this.showSuccess(result.message || 'ส่งออก Audit Log สำเร็จ');
            } else {
                this.showError(result.message || 'ไม่สามารถส่งออก Audit Log ได้');
            }

        } catch (error) {
                console.error('Error exporting audit logs:', error);
            this.showError('เกิดข้อผิดพลาดในการส่งออก Audit Log');
        } finally {
            this.isExportingLogs = false;
            this.showExportLoading(false);
        }
    }

    /**
     * Clear audit logs
     */
    async clearAuditLogs() {
        if (this.isClearingLogs) return;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'ยืนยันการล้าง Log',
                text: 'คุณต้องการล้าง Audit Log ทั้งหมดหรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ล้างทั้งหมด',
                cancelButtonText: 'ยกเลิก'
            });

            if (!result.isConfirmed) return;
        } else {
            if (!confirm('คุณต้องการล้าง Audit Log ทั้งหมดหรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้!')) {
                return;
            }
        }

        try {
            this.isClearingLogs = true;
            this.showClearLoading(true);

            const response = await fetch('/admin/settings/audit/clear', {
            method: 'DELETE',
            headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'ล้าง Audit Log สำเร็จ');
                this.loadAuditLogs(); // Refresh logs
                this.updateAuditStatistics(); // Update statistics
            } else {
                this.showError(result.message || 'ไม่สามารถล้าง Audit Log ได้');
            }

        } catch (error) {
            console.error('Error clearing audit logs:', error);
            this.showError('เกิดข้อผิดพลาดในการล้าง Audit Log');
        } finally {
            this.isClearingLogs = false;
            this.showClearLoading(false);
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
            data.auditEnabled = formData.has('auditEnabled');

            const response = await fetch('/admin/settings/audit', {
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
                this.showSuccess(result.message || 'บันทึกการตั้งค่า Audit Log สำเร็จ');
                this.updateAuditStatistics();
            } else {
                this.showError(result.message || 'ไม่สามารถบันทึกการตั้งค่า Audit Log ได้');
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }

        } catch (error) {
            console.error('Error saving audit settings:', error);
            this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า Audit Log');
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
     * Show audit logs loading state
     */
    showAuditLogsLoading(show) {
        const tableBody = document.getElementById('auditLogsTable');
        if (tableBody && show) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        กำลังโหลดข้อมูล...
                    </td>
                </tr>
            `;
        }
    }

    /**
     * Show export loading state
     */
    showExportLoading(show) {
        const exportBtn = this.form.querySelector('button[onclick="exportAuditLogs()"]');
        if (exportBtn) {
            if (show) {
                exportBtn.disabled = true;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังส่งออก...';
            } else {
                exportBtn.disabled = false;
                exportBtn.innerHTML = '<i class="fas fa-download me-2"></i>ส่งออก Log';
            }
        }
    }

    /**
     * Show clear loading state
     */
    showClearLoading(show) {
        const clearBtn = this.form.querySelector('button[onclick="clearAuditLogs()"]');
        if (clearBtn) {
            if (show) {
                clearBtn.disabled = true;
                clearBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังล้าง...';
            } else {
                clearBtn.disabled = false;
                clearBtn.innerHTML = '<i class="fas fa-trash me-2"></i>ล้าง Log';
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
                text: 'คุณต้องการรีเซ็ตการตั้งค่า Audit Log เป็นค่าเริ่มต้นหรือไม่?',
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
            if (confirm('คุณต้องการรีเซ็ตการตั้งค่า Audit Log เป็นค่าเริ่มต้นหรือไม่?')) {
                this.loadSettings();
            }
        }
    }
}

// Global functions for buttons
function exportAuditLogs() {
    if (window.auditSettings) {
        window.auditSettings.exportAuditLogs();
    }
}

function clearAuditLogs() {
    if (window.auditSettings) {
        window.auditSettings.clearAuditLogs();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('auditSettingsForm')) {
        window.auditSettings = new AuditSettings();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuditSettings;
}