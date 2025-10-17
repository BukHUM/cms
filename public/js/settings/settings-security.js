// Security Settings JavaScript
(function() {
    'use strict';
    
    // Check if already initialized
    if (window.SecuritySettingsManager) {
        return;
    }

    class SecuritySettingsManager {
    constructor() {
        this.form = document.getElementById('securitySettingsForm');
        this.init();
    }

    init() {
            if (this.form) {
        this.loadSettings();
        this.bindEvents();
            }
        }

        bindEvents() {
            // Form submission
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveSettings();
            });

            // Switch toggle events
        const switches = this.form.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switchElement => {
                switchElement.addEventListener('change', (e) => {
                    this.updateSwitchLabel(e.target);
                });
            });
        }

        updateSwitchLabel(switchElement) {
            const label = document.getElementById(switchElement.id + 'Label');
            if (label) {
                label.textContent = switchElement.checked ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
            }
    }

    async loadSettings() {
        try {
                const response = await fetch('/api/settings/security', {
                method: 'GET',
                headers: {
                        'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                } else {
                    this.showError('ไม่สามารถโหลดการตั้งค่าได้: ' + result.message);
                }
            } catch (error) {
                console.error('Error loading security settings:', error);
                this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่า');
            }
        }

        populateForm(data) {
            // Populate form fields
            const sessionLifetime = document.getElementById('sessionLifetime');
            const maxLoginAttempts = document.getElementById('maxLoginAttempts');
            const passwordMinLength = document.getElementById('passwordMinLength');
            const requireSpecialChars = document.getElementById('requireSpecialChars');
            const twoFactorAuth = document.getElementById('twoFactorAuth');
            const ipWhitelist = document.getElementById('ipWhitelist');

            if (sessionLifetime) sessionLifetime.value = data.sessionLifetime || 120;
            if (maxLoginAttempts) maxLoginAttempts.value = data.maxLoginAttempts || 5;
            if (passwordMinLength) passwordMinLength.value = data.passwordMinLength || 8;
            if (requireSpecialChars) {
                requireSpecialChars.checked = data.requireSpecialChars || false;
                this.updateSwitchLabel(requireSpecialChars);
            }
            if (twoFactorAuth) {
                twoFactorAuth.checked = data.twoFactorAuth || false;
                this.updateSwitchLabel(twoFactorAuth);
            }
            if (ipWhitelist) {
                ipWhitelist.checked = data.ipWhitelist || false;
                this.updateSwitchLabel(ipWhitelist);
            }
        }

    async saveSettings() {
            const submitButton = this.form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังบันทึก...';

                // Collect form data
            const formData = new FormData(this.form);
                const data = {
                    sessionLifetime: parseInt(formData.get('sessionLifetime') || document.getElementById('sessionLifetime').value),
                    maxLoginAttempts: parseInt(formData.get('maxLoginAttempts') || document.getElementById('maxLoginAttempts').value),
                    passwordMinLength: parseInt(formData.get('passwordMinLength') || document.getElementById('passwordMinLength').value),
                    requireSpecialChars: document.getElementById('requireSpecialChars').checked,
                    twoFactorAuth: document.getElementById('twoFactorAuth').checked,
                    ipWhitelist: document.getElementById('ipWhitelist').checked
                };

                const response = await fetch('/api/settings/security', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                    this.showSuccess(result.message);
            } else {
                    this.showError(result.message);
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }
        } catch (error) {
            console.error('Error saving security settings:', error);
                this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า');
        } finally {
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        }

    showSuccess(message) {
            // Use SweetAlert2 if available, otherwise use alert
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: message,
                    timer: 3000,
                showConfirmButton: false
            });
        } else {
            alert(message);
        }
    }

    showError(message) {
            // Use SweetAlert2 if available, otherwise use alert
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: message
            });
        } else {
                alert('ข้อผิดพลาด: ' + message);
            }
        }

        showValidationErrors(errors) {
            // Clear previous error styling
            this.clearValidationErrors();

            // Apply error styling to invalid fields
            Object.keys(errors).forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.classList.add('is-invalid');
                    
                    // Create or update error message
                    let errorDiv = field.parentNode.querySelector('.invalid-feedback');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        field.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = errors[fieldName][0];
                }
            });
        }

        clearValidationErrors() {
            const invalidFields = this.form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
            });

            const errorMessages = this.form.querySelectorAll('.invalid-feedback');
            errorMessages.forEach(message => {
                message.remove();
            });
        }
    }

    // Make class globally available
    window.SecuritySettingsManager = SecuritySettingsManager;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
        // Only initialize if we're on the security settings tab
        const securityTab = document.getElementById('security');
        if (securityTab && !window.securitySettingsManager) {
            window.securitySettingsManager = new SecuritySettingsManager();
        }
    });

    // Also initialize when the security tab is shown
    document.addEventListener('shown.bs.tab', function(e) {
        if (e.target.getAttribute('href') === '#security') {
            // Reinitialize if not already done
            if (!window.securitySettingsManager) {
                window.securitySettingsManager = new SecuritySettingsManager();
            }
        }
    });
})();