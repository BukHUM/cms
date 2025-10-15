/**
 * Email Settings JavaScript
 * จัดการการตั้งค่าอีเมลของระบบ
 */

class EmailSettings {
    constructor() {
        this.form = document.getElementById('emailSettingsForm');
        this.isLoading = false;
        this.isTestingEmail = false;
        
        this.init();
    }

    /**
     * Initialize Email Settings
     */
    init() {
        if (!this.form) return;

        this.loadSettings();
        this.bindEvents();
        this.setupPasswordToggle();
        this.forceUpdateSwitchLabels(); // Force update labels on init
        this.clearValidationErrors(); // Clear any existing validation errors
        this.setupInputClearValidation(); // Setup input clear validation
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
            
            const response = await fetch('/admin/settings/email', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่าอีเมลสำเร็จ'); // Disabled auto success message
            } else {
                console.error('Failed to load email settings:', result.message);
                this.showError(result.message || 'ไม่สามารถโหลดการตั้งค่าอีเมลได้');
            }

        } catch (error) {
            console.error('Error loading email settings:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่าอีเมล');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Populate form with settings data
     */
    populateForm(data) {
        
        // Clear validation errors first
        this.clearValidationErrors();
        
        // Basic email settings
        this.setValue('mailDriver', data.mailDriver);
        this.setValue('mailHost', data.mailHost);
        this.setValue('mailPort', data.mailPort);
        this.setValue('mailUsername', data.mailUsername);
        this.setValue('mailPassword', data.mailPassword);
        this.setValue('mailEncryption', data.mailEncryption);
        this.setValue('mailFromAddress', data.mailFromAddress);
        this.setValue('mailFromName', data.mailFromName || 'Laravel Backend');

        // Boolean settings
        this.setCheckbox('mailEnabled', data.mailEnabled);

        // Update password hint based on driver (but don't clear values)
        this.updatePasswordHint(data.mailDriver);
        
        // Force clear validation errors after populating
        setTimeout(() => {
            this.clearValidationErrors();
        }, 100);
        
    }

    /**
     * Set input value
     */
    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || '';
        } else {
            console.warn(`Element with id '${id}' not found`);
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
                'mailEnabled': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' }
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
     * Update password hint based on driver (without clearing values)
     */
    updatePasswordHint(driver) {
        const passwordField = document.getElementById('mailPassword');
        const passwordHint = passwordField ? passwordField.parentNode.querySelector('.form-text') : null;

        const driverHints = {
            'google': '⚠️ สำหรับ Gmail: ต้องใช้ App Password ไม่ใช่รหัสผ่านปกติ! ดูวิธีสร้าง App Password ที่ <a href="https://support.google.com/accounts/answer/185833" target="_blank">Google Support</a>',
            'office365': '⚠️ สำหรับ Office 365: ต้องใช้ App Password ไม่ใช่รหัสผ่านปกติ! ดูวิธีสร้าง App Password ที่ <a href="https://support.microsoft.com/en-us/account-billing/using-app-passwords-with-apps-that-don-t-support-two-step-verification-5896ed9b-4263-e681-128a-a6f2979a7944" target="_blank">Microsoft Support</a>',
            'microsoft': '⚠️ สำหรับ Microsoft (Hotmail/Live/Outlook): ต้องใช้ App Password ไม่ใช่รหัสผ่านปกติ! ดูวิธีสร้าง App Password ที่ <a href="https://support.microsoft.com/en-us/account-billing/using-app-passwords-with-apps-that-don-t-support-two-step-verification-5896ed9b-4263-e681-128a-a6f2979a7944" target="_blank">Microsoft Support</a>',
            'mailgun': 'Mailgun SMTP Password',
            'ses': 'SES SMTP Password'
        };

        if (passwordHint) {
            passwordHint.innerHTML = driverHints[driver] || 'รหัสผ่านอีเมล';
        }
    }

    /**
     * Update driver-specific settings
     */
    updateDriverSettings(driver) {
        const hostField = document.getElementById('mailHost');
        const portField = document.getElementById('mailPort');
        const encryptionField = document.getElementById('mailEncryption');
        const usernameField = document.getElementById('mailUsername');
        const passwordField = document.getElementById('mailPassword');
        const passwordHint = passwordField ? passwordField.parentNode.querySelector('.form-text') : null;

        if (!hostField || !portField || !encryptionField) return;

        const driverSettings = {
            'smtp': {
                host: 'smtp.gmail.com',
                port: '587',
                encryption: 'tls',
                usernamePlaceholder: 'your-email@gmail.com',
                passwordHint: 'รหัสผ่านของอีเมล หรือ App Password สำหรับ Gmail'
            },
            'google': {
                host: 'smtp.gmail.com',
                port: '587',
                encryption: 'tls',
                usernamePlaceholder: 'your-email@gmail.com',
                passwordHint: '⚠️ สำหรับ Gmail: ต้องใช้ App Password ไม่ใช่รหัสผ่านปกติ! ดูวิธีสร้าง App Password ที่ <a href="https://support.google.com/accounts/answer/185833" target="_blank">Google Support</a>'
            },
            'office365': {
                host: 'smtp.office365.com',
                port: '587',
                encryption: 'tls',
                usernamePlaceholder: 'your-email@yourdomain.com',
                passwordHint: '⚠️ สำหรับ Office 365: ต้องใช้ App Password ไม่ใช่รหัสผ่านปกติ! ดูวิธีสร้าง App Password ที่ <a href="https://support.microsoft.com/en-us/account-billing/using-app-passwords-with-apps-that-don-t-support-two-step-verification-5896ed9b-4263-e681-128a-a6f2979a7944" target="_blank">Microsoft Support</a>'
            },
            'microsoft': {
                host: 'smtp-mail.outlook.com',
                port: '587',
                encryption: 'tls',
                usernamePlaceholder: 'your-email@hotmail.com หรือ your-email@live.com หรือ your-email@outlook.com',
                passwordHint: '⚠️ สำหรับ Microsoft (Hotmail/Live/Outlook): ต้องใช้ App Password ไม่ใช่รหัสผ่านปกติ! ดูวิธีสร้าง App Password ที่ <a href="https://support.microsoft.com/en-us/account-billing/using-app-passwords-with-apps-that-don-t-support-two-step-verification-5896ed9b-4263-e681-128a-a6f2979a7944" target="_blank">Microsoft Support</a>'
            },
            'mailgun': {
                host: 'smtp.mailgun.org',
                port: '587',
                encryption: 'tls',
                usernamePlaceholder: 'postmaster@your-domain.mailgun.org',
                passwordHint: 'Mailgun SMTP Password'
            },
            'ses': {
                host: 'email-smtp.us-east-1.amazonaws.com',
                port: '587',
                encryption: 'tls',
                usernamePlaceholder: 'SES SMTP Username',
                passwordHint: 'SES SMTP Password'
            }
        };

        if (driverSettings[driver]) {
            const settings = driverSettings[driver];
            
            // Clear and set new values
            hostField.value = settings.host;
            portField.value = settings.port;
            encryptionField.value = settings.encryption;
            
            // Clear username and password fields
            if (usernameField) {
                usernameField.value = '';
                usernameField.placeholder = settings.usernamePlaceholder;
            }
            
            if (passwordField) {
                passwordField.value = '';
            }
            
            if (passwordHint) {
                passwordHint.innerHTML = settings.passwordHint;
            }
        } else {
            // For SMTP or unknown drivers, clear fields but keep defaults
            if (usernameField) {
                usernameField.value = '';
                usernameField.placeholder = 'your-email@example.com';
            }
            
            if (passwordField) {
                passwordField.value = '';
            }
            
            if (passwordHint) {
                passwordHint.innerHTML = 'รหัสผ่านอีเมล';
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

        // Driver change event
        const driverSelect = document.getElementById('mailDriver');
        if (driverSelect) {
            driverSelect.addEventListener('change', (e) => {
                this.updateDriverSettings(e.target.value);
                this.clearValidationErrors(); // Clear validation errors when driver changes
            });
        }

        // Switch change events
        const switches = this.form.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switchElement => {
            switchElement.addEventListener('change', (e) => {
                this.updateSwitchLabel(e.target.id, e.target.checked);
            });
        });

        // Test email button
        const testEmailBtn = this.form.querySelector('button[onclick="testEmail()"]');
        if (testEmailBtn) {
            testEmailBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.testEmail();
            });
        }

        // Input validation
        this.setupInputValidation();
        
        // Add input event listeners to clear validation errors
        this.setupInputClearValidation();
    }

    /**
     * Setup input validation
     */
    setupInputValidation() {
        // Email validation
        const emailFields = ['mailFromAddress'];
        emailFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => {
                    this.validateEmail(field);
        });
}
});

        // Port validation
        const portField = document.getElementById('mailPort');
        if (portField) {
            portField.addEventListener('blur', () => {
                this.validatePort(portField);
            });
        }

        // Host validation
        const hostField = document.getElementById('mailHost');
        if (hostField) {
            hostField.addEventListener('blur', () => {
                this.validateHost(hostField);
            });
        }
    }

    /**
     * Setup input clear validation
     */
    setupInputClearValidation() {
        // Get all form inputs
        const inputs = this.form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.clearFieldError(input);
            });
            input.addEventListener('change', () => {
                this.clearFieldError(input);
            });
        });
    }

    /**
     * Validate email field
     */
    validateEmail(field) {
        const email = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.showFieldError(field, 'รูปแบบอีเมลไม่ถูกต้อง');
            return false;
        } else {
            this.clearFieldError(field);
            return true;
        }
    }

    /**
     * Validate port field
     */
    validatePort(field) {
        const port = parseInt(field.value);
        
        if (field.value && (isNaN(port) || port < 1 || port > 65535)) {
            this.showFieldError(field, 'Port ต้องเป็นตัวเลขระหว่าง 1-65535');
            return false;
        } else {
            this.clearFieldError(field);
            return true;
        }
    }

    /**
     * Validate host field
     */
    validateHost(field) {
        const host = field.value.trim();
        
        if (host && !this.isValidHostname(host)) {
            this.showFieldError(field, 'รูปแบบ Host ไม่ถูกต้อง');
            return false;
        } else {
            this.clearFieldError(field);
            return true;
        }
    }

    /**
     * Check if hostname is valid
     */
    isValidHostname(hostname) {
        const hostnameRegex = /^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/;
        return hostnameRegex.test(hostname);
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * Setup input clear validation
     */
    setupInputClearValidation() {
        const inputs = this.form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.clearFieldError(input);
            });
            input.addEventListener('change', () => {
                this.clearFieldError(input);
            });
        });
    }

    /**
     * Setup password toggle
     */
    setupPasswordToggle() {
        const passwordField = document.getElementById('mailPassword');
        if (passwordField) {
            // Add toggle button
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'btn btn-outline-secondary';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
            toggleBtn.style.position = 'absolute';
            toggleBtn.style.right = '10px';
            toggleBtn.style.top = '50%';
            toggleBtn.style.transform = 'translateY(-50%)';
            toggleBtn.style.border = 'none';
            toggleBtn.style.background = 'transparent';
            toggleBtn.style.zIndex = '10';

            // Make password field container relative
            passwordField.parentNode.style.position = 'relative';
            passwordField.style.paddingRight = '40px';
            passwordField.parentNode.appendChild(toggleBtn);

            // Toggle functionality
            toggleBtn.addEventListener('click', () => {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordField.type = 'password';
                    toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        }
    }

    /**
     * Save settings to server
     */
    async saveSettings() {
        if (this.isLoading) return;

        // Validate form before saving
        const isValid = this.validateForm();
        if (!isValid) {
            this.showError('กรุณาแก้ไขข้อมูลที่ไม่ถูกต้องก่อนบันทึก');
            return;
        }

        try {
            this.isLoading = true;
            this.showLoading(true);

            // Get form data manually to ensure all fields are included
            const mailDriverEl = document.getElementById('mailDriver');
            const mailHostEl = document.getElementById('mailHost');
            const mailPortEl = document.getElementById('mailPort');
            const mailUsernameEl = document.getElementById('mailUsername');
            const mailPasswordEl = document.getElementById('mailPassword');
            const mailEncryptionEl = document.getElementById('mailEncryption');
            const mailFromAddressEl = document.getElementById('mailFromAddress');
            const mailFromNameEl = document.getElementById('mailFromName');
            const mailEnabledEl = document.getElementById('mailEnabled');
            
            
            const data = {
                mailDriver: mailDriverEl ? mailDriverEl.value : 'smtp',
                mailHost: mailHostEl ? mailHostEl.value : 'smtp.gmail.com',
                mailPort: mailPortEl ? parseInt(mailPortEl.value) : 587,
                mailUsername: mailUsernameEl ? mailUsernameEl.value : '',
                mailPassword: mailPasswordEl ? mailPasswordEl.value : '',
                mailEncryption: mailEncryptionEl ? mailEncryptionEl.value : 'tls',
                mailFromAddress: mailFromAddressEl ? mailFromAddressEl.value : 'noreply@example.com',
                mailFromName: mailFromNameEl ? mailFromNameEl.value : 'Laravel Backend',
                mailEnabled: mailEnabledEl ? mailEnabledEl.checked : false
            };

            // Debug: Log the data being sent

            const response = await fetch('/admin/settings/email', {
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
                this.showSuccess(result.message || 'บันทึกการตั้งค่าอีเมลสำเร็จ');
            } else {
                this.showError(result.message || 'ไม่สามารถบันทึกการตั้งค่าอีเมลได้');
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }

        } catch (error) {
            console.error('Error saving email settings:', error);
            this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่าอีเมล');
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }

    /**
     * Test email configuration
     */
    async testEmail() {
        if (this.isTestingEmail) return;

        // Validate form before testing
        if (!this.validateForm()) {
            this.showError('กรุณาแก้ไขข้อมูลที่ไม่ถูกต้องก่อนทดสอบ');
            return;
        }

        try {
            this.isTestingEmail = true;
            this.showTestLoading(true);

            // Get form data manually to ensure all fields are included
            const mailDriverEl = document.getElementById('mailDriver');
            const mailHostEl = document.getElementById('mailHost');
            const mailPortEl = document.getElementById('mailPort');
            const mailUsernameEl = document.getElementById('mailUsername');
            const mailPasswordEl = document.getElementById('mailPassword');
            const mailEncryptionEl = document.getElementById('mailEncryption');
            const mailFromAddressEl = document.getElementById('mailFromAddress');
            const mailFromNameEl = document.getElementById('mailFromName');
            const mailEnabledEl = document.getElementById('mailEnabled');
            
            const data = {
                mailDriver: mailDriverEl ? mailDriverEl.value : 'smtp',
                mailHost: mailHostEl ? mailHostEl.value : 'smtp.gmail.com',
                mailPort: mailPortEl ? parseInt(mailPortEl.value) : 587,
                mailUsername: mailUsernameEl ? mailUsernameEl.value : '',
                mailPassword: mailPasswordEl ? mailPasswordEl.value : '',
                mailEncryption: mailEncryptionEl ? mailEncryptionEl.value : 'tls',
                mailFromAddress: mailFromAddressEl ? mailFromAddressEl.value : 'noreply@example.com',
                mailFromName: mailFromNameEl ? mailFromNameEl.value : 'Laravel Backend',
                mailEnabled: mailEnabledEl ? mailEnabledEl.checked : false
            };

            // Debug: Log the data being sent for test

            const response = await fetch('/admin/settings/email/test', {
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
                this.showSuccess('ทดสอบการส่งอีเมลสำเร็จ! ตรวจสอบอีเมลของคุณ');
            } else {
                this.showError(result.message || 'ไม่สามารถส่งอีเมลทดสอบได้');
            }

        } catch (error) {
            console.error('Error testing email:', error);
            this.showError('เกิดข้อผิดพลาดในการทดสอบการส่งอีเมล');
        } finally {
            this.isTestingEmail = false;
            this.showTestLoading(false);
        }
    }

    /**
     * Validate entire form
     */
    validateForm() {
        let isValid = true;

        // Validate email fields
        const emailFields = ['mailFromAddress'];
        emailFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !this.validateEmail(field)) {
                isValid = false;
            }
        });

        // Validate port
        const portField = document.getElementById('mailPort');
        if (portField && !this.validatePort(portField)) {
            isValid = false;
        }

        // Validate host
        const hostField = document.getElementById('mailHost');
        if (hostField && !this.validateHost(hostField)) {
            isValid = false;
        }

        return isValid;
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
                this.showFieldError(element, errors[field][0]);
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
     * Show test loading state
     */
    showTestLoading(show) {
        const testBtn = this.form.querySelector('button[onclick="testEmail()"]');
        if (testBtn) {
            if (show) {
                testBtn.disabled = true;
                testBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังทดสอบ...';
            } else {
                testBtn.disabled = false;
                testBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>ทดสอบการส่งอีเมล';
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
                timer: 3000,
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
                text: 'คุณต้องการรีเซ็ตการตั้งค่าอีเมลเป็นค่าเริ่มต้นหรือไม่?',
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
            if (confirm('คุณต้องการรีเซ็ตการตั้งค่าอีเมลเป็นค่าเริ่มต้นหรือไม่?')) {
                this.loadSettings();
            }
        }
    }
}

// Global function for test email button
function testEmail() {
    if (window.emailSettings) {
        window.emailSettings.testEmail();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('emailSettingsForm')) {
        window.emailSettings = new EmailSettings();
    } else {
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = EmailSettings;
}