/**
 * General Settings JavaScript
 * จัดการการตั้งค่าทั่วไปของระบบ
 */

class GeneralSettings {
    constructor() {
        this.form = document.getElementById('generalSettingsForm');
        this.autoSaveEnabled = true;
        this.autoSaveInterval = null;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize General Settings
     */
    init() {
        if (!this.form) return;

        this.loadSettings();
        this.bindEvents();
        this.setupAutoSave();
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
            
            const response = await fetch('/admin/settings/general', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่าสำเร็จ'); // Disabled auto success message
            } else {
                this.showError(result.message || 'ไม่สามารถโหลดการตั้งค่าได้');
            }

        } catch (error) {
            console.error('Error loading general settings:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่า');
        } finally {
            // Don't hide loading spinner since we didn't show it
            // this.showLoading(false);
        }
    }

    /**
     * Populate form with settings data
     */
    populateForm(data) {
        // Basic settings
        this.setValue('siteName', data.siteName);
        this.setValue('siteUrl', data.siteUrl);
        this.setValue('timezone', data.timezone);
        this.setValue('language', data.language);

        // Debug level setting
        this.setValue('debugLevel', data.debugLevel || 'standard');

        // Boolean settings
        this.setCheckbox('maintenanceMode', data.maintenanceMode);
        this.setCheckbox('debugBar', data.debugBar);

        // Update auto-save setting - disabled since autoSave field is removed
        this.autoSaveEnabled = false;
        this.toggleAutoSave();
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
                'maintenanceMode': { true: 'เปิดใช้งานโหมดบำรุงรักษา', false: 'ปิดใช้งานโหมดบำรุงรักษา' },
                'debugMode': { true: 'เปิดใช้งานโหมด Debug', false: 'ปิดใช้งานโหมด Debug' },
                'debugBar': { true: 'เปิดใช้งาน Debug Bar', false: 'ปิดใช้งาน Debug Bar' }
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
                
                // Special handling for auto-save toggle - disabled since field is removed
                if (e.target.id === 'autoSave') {
                    // Auto-save is disabled, do nothing
                    return;
                }
            });
        });

        // Input change events for auto-save - disabled since autoSave field is removed
        const inputs = this.form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                // Auto-save is disabled, do nothing
            });
        });
    }

    /**
     * Setup auto-save functionality
     */
    setupAutoSave() {
        this.debounceAutoSave = this.debounce(() => {
            this.saveSettings(true); // Silent save
        }, 2000);
    }

    /**
     * Toggle auto-save based on setting
     */
    toggleAutoSave() {
        if (this.autoSaveInterval) {
            clearInterval(this.autoSaveInterval);
            this.autoSaveInterval = null;
        }

        if (this.autoSaveEnabled) {
            // Auto-save every 30 seconds if there are changes
            this.autoSaveInterval = setInterval(() => {
                if (this.hasFormChanges() && !this.isLoading) {
                    this.saveSettings(true);
                }
            }, 30000);
        }
    }

    /**
     * Check if form has changes
     */
    hasFormChanges() {
        // This is a simple implementation - you might want to track original values
        return true; // For now, always return true to enable auto-save
    }

    /**
     * Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Save settings to server
     */
    async saveSettings(silent = false) {
        if (this.isLoading) return;

        try {
            this.isLoading = true;
            if (!silent) this.showLoading(true);

            // Collect all form data manually
            const data = {
                siteName: document.getElementById('siteName')?.value || '',
                siteUrl: document.getElementById('siteUrl')?.value || '',
                timezone: document.getElementById('timezone')?.value || 'Asia/Bangkok',
                language: document.getElementById('language')?.value || 'th',
                maintenanceMode: document.getElementById('maintenanceMode')?.checked || false,
                debugLevel: document.getElementById('debugLevel')?.value || 'standard',
                debugBar: document.getElementById('debugBar')?.checked || false
            };

                // console.log('Sending data:', data); // Debug log - Hidden

            const response = await fetch('/admin/settings/general', {
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
                if (!silent) {
                    this.showSuccess(result.message || 'บันทึกการตั้งค่าสำเร็จ');
                }
                
                // Update auto-save setting if changed
                if (data.autoSave !== this.autoSaveEnabled) {
                    this.autoSaveEnabled = data.autoSave;
                    this.toggleAutoSave();
                }
            } else {
                this.showError(result.message || 'ไม่สามารถบันทึกการตั้งค่าได้');
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }

        } catch (error) {
            console.error('Error saving general settings:', error);
            this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า');
        } finally {
            this.isLoading = false;
            if (!silent) this.showLoading(false);
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
                text: 'คุณต้องการรีเซ็ตการตั้งค่าเป็นค่าเริ่มต้นหรือไม่?',
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
            if (confirm('คุณต้องการรีเซ็ตการตั้งค่าเป็นค่าเริ่มต้นหรือไม่?')) {
                this.loadSettings();
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('generalSettingsForm')) {
        window.generalSettings = new GeneralSettings();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GeneralSettings;
}