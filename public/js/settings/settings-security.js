/**
 * Security Settings JavaScript
 * จัดการการตั้งค่าความปลอดภัยของระบบ
 */

class SecuritySettings {
    constructor() {
        this.form = document.getElementById('securitySettingsForm');
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize Security Settings
     */
    init() {
        if (!this.form) return;

        this.loadSettings();
        this.bindEvents();
        this.setupPasswordStrengthIndicator();
        this.setupSecurityRecommendations();
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
            
            const response = await fetch('/admin/settings/security', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่าความปลอดภัยสำเร็จ'); // Disabled auto success message
                this.updateSecurityScore();
            } else {
                this.showError(result.message || 'ไม่สามารถโหลดการตั้งค่าความปลอดภัยได้');
            }

        } catch (error) {
            console.error('Error loading security settings:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่าความปลอดภัย');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Populate form with settings data
     */
    populateForm(data) {
        // Basic security settings
        this.setValue('sessionLifetime', data.sessionLifetime);
        this.setValue('maxLoginAttempts', data.maxLoginAttempts);
        this.setValue('passwordMinLength', data.passwordMinLength);

        // Boolean settings
        this.setCheckbox('requireSpecialChars', data.requireSpecialChars);
        this.setCheckbox('twoFactorAuth', data.twoFactorAuth);
        this.setCheckbox('ipWhitelist', data.ipWhitelist);

        // Update security recommendations
        this.updateSecurityRecommendations(data);
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
                'requireSpecialChars': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' },
                'twoFactorAuth': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' },
                'ipWhitelist': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' }
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
                this.updateSecurityScore();
            });
        });

        // Input change events
        const inputs = this.form.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updateSecurityScore();
                this.validateSecuritySettings();
            });
        });

        // Password strength indicator
        this.setupPasswordStrengthIndicator();
    }

    /**
     * Setup password strength indicator
     */
    setupPasswordStrengthIndicator() {
        const passwordLengthField = document.getElementById('passwordMinLength');
        if (passwordLengthField) {
            passwordLengthField.addEventListener('input', () => {
                this.updatePasswordStrengthIndicator();
            });
        }
    }

    /**
     * Update password strength indicator
     */
    updatePasswordStrengthIndicator() {
        const passwordLength = parseInt(document.getElementById('passwordMinLength')?.value || 0);
        const requireSpecialChars = document.getElementById('requireSpecialChars')?.checked || false;
        
        let strength = 0;
        let strengthText = '';
        let strengthColor = '';

        // Length scoring
        if (passwordLength >= 12) strength += 3;
        else if (passwordLength >= 8) strength += 2;
        else if (passwordLength >= 6) strength += 1;

        // Special characters bonus
        if (requireSpecialChars) strength += 1;

        // Determine strength level
        if (strength >= 4) {
            strengthText = 'แข็งแกร่งมาก';
            strengthColor = 'success';
        } else if (strength >= 3) {
            strengthText = 'แข็งแกร่ง';
            strengthColor = 'info';
        } else if (strength >= 2) {
            strengthText = 'ปานกลาง';
            strengthColor = 'warning';
        } else {
            strengthText = 'อ่อนแอ';
            strengthColor = 'danger';
        }

        // Update indicator (create if doesn't exist)
        let indicator = document.getElementById('passwordStrengthIndicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'passwordStrengthIndicator';
            indicator.className = 'mt-2';
            passwordLengthField.parentNode.appendChild(indicator);
        }

        indicator.innerHTML = `
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-${strengthColor}" role="progressbar" 
                     style="width: ${(strength / 4) * 100}%"></div>
            </div>
            <small class="text-${strengthColor}">ความแข็งแกร่ง: ${strengthText}</small>
        `;
    }

    /**
     * Setup security recommendations
     */
    setupSecurityRecommendations() {
        // This will be populated when settings are loaded
    }

    /**
     * Update security recommendations based on current settings
     */
    updateSecurityRecommendations(data) {
        const recommendations = [];
        
        // Session lifetime recommendations
        if (data.sessionLifetime < 60) {
            recommendations.push({
                type: 'warning',
                message: 'ระยะเวลา Session สั้นเกินไป อาจทำให้ผู้ใช้ต้องเข้าสู่ระบบบ่อย'
            });
        } else if (data.sessionLifetime > 480) {
            recommendations.push({
                type: 'warning',
                message: 'ระยะเวลา Session ยาวเกินไป อาจเสี่ยงต่อความปลอดภัย'
            });
        }

        // Login attempts recommendations
        if (data.maxLoginAttempts > 10) {
            recommendations.push({
                type: 'danger',
                message: 'จำนวนครั้งการเข้าสู่ระบบสูงเกินไป อาจเสี่ยงต่อการโจมตี Brute Force'
            });
        } else if (data.maxLoginAttempts < 3) {
            recommendations.push({
                type: 'warning',
                message: 'จำนวนครั้งการเข้าสู่ระบบต่ำเกินไป อาจทำให้ผู้ใช้ถูกบล็อกโดยไม่ตั้งใจ'
            });
        }

        // Password recommendations
        if (data.passwordMinLength < 8) {
            recommendations.push({
                type: 'danger',
                message: 'ความยาวรหัสผ่านขั้นต่ำควรเป็น 8 ตัวอักษรขึ้นไป'
            });
        }

        if (!data.requireSpecialChars) {
            recommendations.push({
                type: 'warning',
                message: 'ควรเปิดใช้งานการบังคับใช้อักขระพิเศษในรหัสผ่าน'
            });
        }

        // Two-factor authentication
        if (!data.twoFactorAuth) {
            recommendations.push({
                type: 'info',
                message: 'ควรเปิดใช้งาน Two-Factor Authentication เพื่อความปลอดภัยเพิ่มเติม'
            });
        }

        // IP whitelist
        if (!data.ipWhitelist) {
            recommendations.push({
                type: 'info',
                message: 'ควรพิจารณาเปิดใช้งาน IP Whitelist สำหรับผู้ดูแลระบบ'
            });
        }

        this.displayRecommendations(recommendations);
    }

    /**
     * Display security recommendations
     */
    displayRecommendations(recommendations) {
        let container = document.getElementById('securityRecommendations');
        if (!container) {
            container = document.createElement('div');
            container.id = 'securityRecommendations';
            container.className = 'mt-4';
            this.form.appendChild(container);
        }

        if (recommendations.length === 0) {
            container.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-shield-alt me-2"></i>
                    การตั้งค่าความปลอดภัยของคุณอยู่ในระดับดี
                </div>
            `;
            return;
        }

        let html = '<h6 class="text-primary mb-3"><i class="fas fa-lightbulb me-2"></i>คำแนะนำความปลอดภัย</h6>';
        
        recommendations.forEach(rec => {
            const alertClass = `alert-${rec.type}`;
            const iconClass = rec.type === 'danger' ? 'fas fa-exclamation-triangle' : 
                            rec.type === 'warning' ? 'fas fa-exclamation-circle' : 
                            'fas fa-info-circle';
            
            html += `
                <div class="alert ${alertClass} alert-dismissible fade show">
                    <i class="${iconClass} me-2"></i>
                    ${rec.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Update security score
     */
    updateSecurityScore() {
        const data = this.getFormData();
        let score = 0;
        let maxScore = 100;

        // Session lifetime (20 points)
        if (data.sessionLifetime >= 60 && data.sessionLifetime <= 480) {
            score += 20;
        } else if (data.sessionLifetime >= 30 && data.sessionLifetime <= 720) {
            score += 15;
        } else {
            score += 5;
        }

        // Login attempts (20 points)
        if (data.maxLoginAttempts >= 3 && data.maxLoginAttempts <= 5) {
            score += 20;
        } else if (data.maxLoginAttempts >= 6 && data.maxLoginAttempts <= 10) {
            score += 15;
        } else {
            score += 5;
        }

        // Password length (20 points)
        if (data.passwordMinLength >= 12) {
            score += 20;
        } else if (data.passwordMinLength >= 8) {
            score += 15;
        } else if (data.passwordMinLength >= 6) {
            score += 10;
        } else {
            score += 5;
        }

        // Special characters (20 points)
        if (data.requireSpecialChars) {
            score += 20;
        }

        // Two-factor auth (10 points)
        if (data.twoFactorAuth) {
            score += 10;
        }

        // IP whitelist (10 points)
        if (data.ipWhitelist) {
            score += 10;
        }

        this.displaySecurityScore(score, maxScore);
    }

    /**
     * Display security score
     */
    displaySecurityScore(score, maxScore) {
        let container = document.getElementById('securityScore');
        if (!container) {
            container = document.createElement('div');
            container.id = 'securityScore';
            container.className = 'mt-3';
            this.form.appendChild(container);
        }

        const percentage = (score / maxScore) * 100;
        let scoreClass = 'success';
        let scoreText = 'ดีเยี่ยม';

        if (percentage < 50) {
            scoreClass = 'danger';
            scoreText = 'ต้องปรับปรุง';
        } else if (percentage < 70) {
            scoreClass = 'warning';
            scoreText = 'ปานกลาง';
        } else if (percentage < 90) {
            scoreClass = 'info';
            scoreText = 'ดี';
        }

        container.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-shield-alt me-2"></i>
                        คะแนนความปลอดภัย
                    </h6>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-${scoreClass}" role="progressbar" 
                             style="width: ${percentage}%">
                            ${score}/${maxScore}
                        </div>
                    </div>
                    <small class="text-${scoreClass}">ระดับ: ${scoreText}</small>
                </div>
            </div>
        `;
    }

    /**
     * Get form data
     */
    getFormData() {
        const formData = new FormData(this.form);
        return {
            sessionLifetime: parseInt(formData.get('sessionLifetime') || 120),
            maxLoginAttempts: parseInt(formData.get('maxLoginAttempts') || 5),
            passwordMinLength: parseInt(formData.get('passwordMinLength') || 8),
            requireSpecialChars: formData.has('requireSpecialChars'),
            twoFactorAuth: formData.has('twoFactorAuth'),
            ipWhitelist: formData.has('ipWhitelist')
        };
    }

    /**
     * Validate security settings
     */
    validateSecuritySettings() {
        const data = this.getFormData();
        let isValid = true;

        // Session lifetime validation
        if (data.sessionLifetime < 5 || data.sessionLifetime > 1440) {
            this.showFieldError('sessionLifetime', 'ระยะเวลา Session ต้องอยู่ระหว่าง 5-1440 นาที');
            isValid = false;
        } else {
            this.clearFieldError('sessionLifetime');
        }

        // Login attempts validation
        if (data.maxLoginAttempts < 1 || data.maxLoginAttempts > 20) {
            this.showFieldError('maxLoginAttempts', 'จำนวนครั้งการเข้าสู่ระบบต้องอยู่ระหว่าง 1-20');
            isValid = false;
        } else {
            this.clearFieldError('maxLoginAttempts');
        }

        // Password length validation
        if (data.passwordMinLength < 4 || data.passwordMinLength > 50) {
            this.showFieldError('passwordMinLength', 'ความยาวรหัสผ่านต้องอยู่ระหว่าง 4-50 ตัวอักษร');
            isValid = false;
        } else {
            this.clearFieldError('passwordMinLength');
        }

        return isValid;
    }

    /**
     * Show field error
     */
    showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
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
    }

    /**
     * Clear field error
     */
    clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.remove('is-invalid');
            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    }

    /**
     * Save settings to server
     */
    async saveSettings() {
        if (this.isLoading) return;

        // Validate form before saving
        if (!this.validateSecuritySettings()) {
            this.showError('กรุณาแก้ไขข้อมูลที่ไม่ถูกต้องก่อนบันทึก');
                return;
        }

        try {
            this.isLoading = true;
            this.showLoading(true);

            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData.entries());

            // Convert checkbox values to boolean
            const checkboxes = ['requireSpecialChars', 'twoFactorAuth', 'ipWhitelist'];
            checkboxes.forEach(key => {
                data[key] = formData.has(key);
            });

            const response = await fetch('/admin/settings/security', {
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
                this.showSuccess(result.message || 'บันทึกการตั้งค่าความปลอดภัยสำเร็จ');
                this.updateSecurityScore();
            } else {
                this.showError(result.message || 'ไม่สามารถบันทึกการตั้งค่าความปลอดภัยได้');
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }

        } catch (error) {
            console.error('Error saving security settings:', error);
            this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่าความปลอดภัย');
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
            this.showFieldError(field, errors[field][0]);
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
                text: 'คุณต้องการรีเซ็ตการตั้งค่าความปลอดภัยเป็นค่าเริ่มต้นหรือไม่?',
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
            if (confirm('คุณต้องการรีเซ็ตการตั้งค่าความปลอดภัยเป็นค่าเริ่มต้นหรือไม่?')) {
                this.loadSettings();
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('securitySettingsForm')) {
        window.securitySettings = new SecuritySettings();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SecuritySettings;
}