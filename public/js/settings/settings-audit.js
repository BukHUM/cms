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
        this.currentPage = 1;
        this.perPage = 20;
        this.totalPages = 1;
        this.totalLogs = 0;
        
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
                // Settings loaded successfully
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่า Audit Log สำเร็จ'); // Disabled auto success message
            } else {
                console.error('Failed to load settings:', result.message);
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
        // Populate form with loaded data
        
        // Basic audit settings
        this.setCheckbox('auditEnabled', data.auditEnabled);
        this.setValue('auditRetention', data.auditRetention);
        this.setValue('auditLevel', data.auditLevel);

        // Update audit level description
        this.updateAuditLevelDescription(data.auditLevel);
        
        // Form populated successfully
    }

    /**
     * Set select value
     */
    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            // Set element value
            element.value = value || '';
        } else {
            console.error(`Element with id ${id} not found`);
        }
    }

    /**
     * Set checkbox value
     */
    setCheckbox(id, value) {
        const element = document.getElementById(id);
        if (element) {
            // Set checkbox value
            element.checked = Boolean(value);
            this.updateSwitchLabel(id, Boolean(value));
        } else {
            console.error(`Checkbox with id ${id} not found`);
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
            });
        });

        // Audit level change event
        const levelSelect = document.getElementById('auditLevel');
        if (levelSelect) {
            levelSelect.addEventListener('change', (e) => {
                this.updateAuditLevelDescription(e.target.value);
            });
        }

        // Retention change event
        const retentionInput = document.getElementById('auditRetention');
        if (retentionInput) {
            retentionInput.addEventListener('change', () => {
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
     * Load audit logs with pagination
     */
    async loadAuditLogs(silent = false, page = 1) {
        try {
            if (!silent) this.showAuditLogsLoading(true);
            
            const response = await fetch(`/admin/settings/audit/logs?page=${page}&per_page=${this.perPage}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.auditLogs = result.data;
                this.currentPage = result.pagination.current_page;
                this.totalPages = result.pagination.last_page;
                this.totalLogs = result.pagination.total;
                this.displayAuditLogs();
                this.displayPagination();
            } else {
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
            
                    // Debug action and icon
            
            html += `
                <tr>
                    <td>${log.created_at}</td>
                    <td>${log.user_name || 'ไม่ทราบ'}</td>
                    <td>
                        <i class="${actionIcon} me-1"></i>
                        ${this.getFormattedAction(log.action)}
                    </td>
                    <td>${log.ip_address || '-'}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    /**
     * Display pagination
     */
    displayPagination() {
        const tableContainer = document.querySelector('.audit-logs-table');
        if (!tableContainer) return;

        // Remove existing pagination
        const existingPagination = tableContainer.querySelector('.pagination');
        if (existingPagination) {
            existingPagination.remove();
        }

        if (this.totalPages <= 1) return;

        // Create pagination HTML
        let paginationHTML = `
            <div class="pagination mt-3 d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <small class="text-muted">
                        แสดง ${this.auditLogs.length} รายการ จากทั้งหมด ${this.totalLogs} รายการ
                    </small>
                </div>
                <nav aria-label="Audit logs pagination">
                    <ul class="pagination pagination-sm mb-0">
        `;

        // Previous button
        if (this.currentPage > 1) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="window.auditSettings.loadAuditLogs(false, ${this.currentPage - 1})">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        }

        // Page numbers
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(this.totalPages, this.currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <li class="page-item ${i === this.currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="window.auditSettings.loadAuditLogs(false, ${i})">${i}</a>
                </li>
            `;
        }

        // Next button
        if (this.currentPage < this.totalPages) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="window.auditSettings.loadAuditLogs(false, ${this.currentPage + 1})">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        }

        paginationHTML += `
                    </ul>
                </nav>
            </div>
        `;

        tableContainer.insertAdjacentHTML('beforeend', paginationHTML);
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
     * Get formatted action name
     */
    getFormattedAction(action) {
        const actions = {
            'login': 'เข้าสู่ระบบ',
            'logout': 'ออกจากระบบ',
            'create': 'สร้างข้อมูล',
            'update': 'แก้ไขข้อมูล',
            'delete': 'ลบข้อมูล',
            'view': 'ดูข้อมูล',
            'search': 'ค้นหา',
            'export': 'ส่งออกข้อมูล',
            'import': 'นำเข้าข้อมูล',
            'settings': 'การตั้งค่า',
            'settings_update': 'แก้ไขการตั้งค่า',
            'password_change': 'เปลี่ยนรหัสผ่าน',
            'profile_update': 'แก้ไขโปรไฟล์',
            'audit_clear': 'ล้าง Audit Logs',
            'audit_clear_all': 'ล้าง Audit Logs ทั้งหมด',
            'user_create': 'สร้างผู้ใช้',
            'user_update': 'แก้ไขผู้ใช้',
            'user_delete': 'ลบผู้ใช้',
            'role_create': 'สร้างบทบาท',
            'role_update': 'แก้ไขบทบาท',
            'role_delete': 'ลบบทบาท',
            'permission_create': 'สร้างสิทธิ์',
            'permission_update': 'แก้ไขสิทธิ์',
            'permission_delete': 'ลบสิทธิ์',
            'file_upload': 'อัปโหลดไฟล์',
            'file_download': 'ดาวน์โหลดไฟล์',
            'email_send': 'ส่งอีเมล',
            'backup': 'สำรองข้อมูล',
            'restore': 'กู้คืนข้อมูล',
            'system_restart': 'รีสตาร์ทระบบ',
            'maintenance': 'ซ่อมบำรุง'
        };

        return actions[action.toLowerCase()] || action;
    }

    /**
     * Get action icon
     */
    getActionIcon(action) {
        const icons = {
            // English actions
            'login': 'fas fa-sign-in-alt text-success',
            'logout': 'fas fa-sign-out-alt text-warning',
            'create': 'fas fa-plus text-primary',
            'update': 'fas fa-edit text-info',
            'delete': 'fas fa-trash text-danger',
            'view': 'fas fa-eye text-secondary',
            'search': 'fas fa-search text-dark',
            'export': 'fas fa-download text-success',
            'import': 'fas fa-upload text-primary',
            'settings': 'fas fa-cog text-warning',
            'settings_update': 'fas fa-cog text-warning',
            'password_change': 'fas fa-key text-info',
            'profile_update': 'fas fa-user-edit text-primary',
            'audit_clear': 'fas fa-trash-alt text-danger',
            'audit_clear_all': 'fas fa-trash-alt text-danger',
            'user_create': 'fas fa-user-plus text-success',
            'user_update': 'fas fa-user-edit text-primary',
            'user_delete': 'fas fa-user-times text-danger',
            'role_create': 'fas fa-user-tag text-success',
            'role_update': 'fas fa-user-tag text-primary',
            'role_delete': 'fas fa-user-tag text-danger',
            'permission_create': 'fas fa-shield-alt text-success',
            'permission_update': 'fas fa-shield-alt text-primary',
            'permission_delete': 'fas fa-shield-alt text-danger',
            'file_upload': 'fas fa-upload text-primary',
            'file_download': 'fas fa-download text-success',
            'email_send': 'fas fa-envelope text-info',
            'backup': 'fas fa-database text-success',
            'restore': 'fas fa-undo text-warning',
            'system_restart': 'fas fa-power-off text-danger',
            'maintenance': 'fas fa-tools text-warning',
            
            // Thai actions
            'เข้าสู่ระบบ': 'fas fa-sign-in-alt text-success',
            'ออกจากระบบ': 'fas fa-sign-out-alt text-warning',
            'สร้างข้อมูล': 'fas fa-plus text-primary',
            'แก้ไขข้อมูล': 'fas fa-edit text-info',
            'ลบข้อมูล': 'fas fa-trash text-danger',
            'ดูข้อมูล': 'fas fa-eye text-secondary',
            'ค้นหา': 'fas fa-search text-dark',
            'ส่งออกข้อมูล': 'fas fa-download text-success',
            'นำเข้าข้อมูล': 'fas fa-upload text-primary',
            'การตั้งค่า': 'fas fa-cog text-warning',
            'แก้ไขการตั้งค่า': 'fas fa-cog text-warning',
            'เปลี่ยนรหัสผ่าน': 'fas fa-key text-info',
            'แก้ไขโปรไฟล์': 'fas fa-user-edit text-primary',
            'ล้าง Audit Logs': 'fas fa-trash-alt text-danger',
            'ล้าง Audit Logs ทั้งหมด': 'fas fa-trash-alt text-danger',
            'สร้างผู้ใช้': 'fas fa-user-plus text-success',
            'แก้ไขผู้ใช้': 'fas fa-user-edit text-primary',
            'ลบผู้ใช้': 'fas fa-user-times text-danger',
            'สร้างบทบาท': 'fas fa-user-tag text-success',
            'แก้ไขบทบาท': 'fas fa-user-tag text-primary',
            'ลบบทบาท': 'fas fa-user-tag text-danger',
            'สร้างสิทธิ์': 'fas fa-shield-alt text-success',
            'แก้ไขสิทธิ์': 'fas fa-shield-alt text-primary',
            'ลบสิทธิ์': 'fas fa-shield-alt text-danger',
            'อัปโหลดไฟล์': 'fas fa-upload text-primary',
            'ดาวน์โหลดไฟล์': 'fas fa-download text-success',
            'ส่งอีเมล': 'fas fa-envelope text-info',
            'สำรองข้อมูล': 'fas fa-database text-success',
            'กู้คืนข้อมูล': 'fas fa-undo text-warning',
            'รีสตาร์ทระบบ': 'fas fa-power-off text-danger',
            'ซ่อมบำรุง': 'fas fa-tools text-warning'
        };

        return icons[action] || 'fas fa-circle text-muted';
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

            // Create a form to submit the request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/settings/audit/export';
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            this.showSuccess('กำลังส่งออก Audit Log...');

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
 // Update statistics
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
     * Clear all audit logs (ignore retention period)
     */
    async clearAllAuditLogs() {
        if (this.isClearingLogs) return;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'ยืนยันการล้าง Log ทั้งหมด',
                text: 'คุณต้องการล้าง Audit Log ทั้งหมดโดยไม่สนใจระยะเวลาเก็บข้อมูลหรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้!',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ล้างทั้งหมด',
                cancelButtonText: 'ยกเลิก',
                input: 'text',
                inputLabel: 'พิมพ์ "DELETE ALL" เพื่อยืนยัน',
                inputValidator: (value) => {
                    if (value !== 'DELETE ALL') {
                        return 'กรุณาพิมพ์ "DELETE ALL" เพื่อยืนยัน';
                    }
                }
            });

            if (!result.isConfirmed) return;
        } else {
            const confirmText = prompt('กรุณาพิมพ์ "DELETE ALL" เพื่อยืนยันการล้าง Log ทั้งหมด:');
            if (confirmText !== 'DELETE ALL') {
                this.showError('การยืนยันไม่ถูกต้อง');
                return;
            }
        }

        try {
            this.isClearingLogs = true;
            this.showClearLoading(true);

            const response = await fetch('/admin/settings/audit/clear-all', {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'ล้าง Audit Log ทั้งหมดสำเร็จ');
                this.loadAuditLogs(); // Refresh logs
            } else {
                this.showError(result.message || 'ไม่สามารถล้าง Audit Log ทั้งหมดได้');
            }

        } catch (error) {
            console.error('Error clearing all audit logs:', error);
            this.showError('เกิดข้อผิดพลาดในการล้าง Audit Log ทั้งหมด');
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
            
            // Save settings data

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
                // โหลดการตั้งค่าใหม่หลังบันทึกสำเร็จ
                await this.loadSettings();
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

// Global functions for onclick handlers
function clearAllAuditLogs() {
    if (window.auditSettings) {
        window.auditSettings.clearAllAuditLogs();
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuditSettings;
}