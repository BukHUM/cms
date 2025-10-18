/**
 * Auth JavaScript Module
 * Handles authentication form interactions and security features
 */
class AuthManager {
    constructor() {
        this.elements = this.initializeElements();
        this.initializeEventListeners();
        this.initializeSecurityFeatures();
    }

    initializeElements() {
        return {
            form: document.querySelector('form'),
            emailInput: document.getElementById('email'),
            passwordInput: document.getElementById('password'),
            togglePassword: document.getElementById('togglePassword'),
            passwordIcon: document.getElementById('passwordIcon'),
            alerts: document.querySelectorAll('.bg-red-50, .bg-green-50, .bg-blue-50'),
            rateLimitIndicator: document.getElementById('rate-limit-indicator')
        };
    }

    initializeEventListeners() {
        // Password visibility toggle
        if (this.elements.togglePassword && this.elements.passwordInput && this.elements.passwordIcon) {
            this.elements.togglePassword.addEventListener('click', () => this.togglePasswordVisibility());
        }

        // Form validation
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.validateForm(e));
        }

        // Input validation on blur
        if (this.elements.emailInput) {
            this.elements.emailInput.addEventListener('blur', () => this.validateEmail());
        }

        if (this.elements.passwordInput) {
            this.elements.passwordInput.addEventListener('blur', () => this.validatePassword());
        }
    }

    initializeSecurityFeatures() {
        // Auto-hide alerts
        this.autoHideAlerts();
        
        // Rate limiting indicator
        this.updateRateLimitIndicator();
        
        // Disable form after multiple failed attempts
        this.checkFailedAttempts();
        
        // Clear sensitive data on page unload
        window.addEventListener('beforeunload', () => this.clearSensitiveData());
    }

    togglePasswordVisibility() {
        const type = this.elements.passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        this.elements.passwordInput.setAttribute('type', type);
        
        this.elements.passwordIcon.classList.toggle('fa-eye');
        this.elements.passwordIcon.classList.toggle('fa-eye-slash');
        
        // Auto-hide password after 30 seconds
        if (type === 'text') {
            setTimeout(() => {
                if (this.elements.passwordInput.getAttribute('type') === 'text') {
                    this.togglePasswordVisibility();
                }
            }, 30000);
        }
    }

    validateForm(e) {
        let isValid = true;

        // Email validation
        if (this.elements.emailInput && !this.validateEmail()) {
            isValid = false;
        }

        // Password validation
        if (this.elements.passwordInput && !this.validatePassword()) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            this.showError('กรุณากรอกข้อมูลให้ครบถ้วน');
        }
    }

    validateEmail() {
        const email = this.elements.emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            this.elements.emailInput.classList.add('border-red-500');
            return false;
        } else if (!emailRegex.test(email)) {
            this.elements.emailInput.classList.add('border-red-500');
            this.showError('รูปแบบอีเมล์ไม่ถูกต้อง');
            return false;
        } else {
            this.elements.emailInput.classList.remove('border-red-500');
            return true;
        }
    }

    validatePassword() {
        const password = this.elements.passwordInput.value.trim();
        
        if (!password) {
            this.elements.passwordInput.classList.add('border-red-500');
            return false;
        } else {
            this.elements.passwordInput.classList.remove('border-red-500');
            return true;
        }
    }

    autoHideAlerts() {
        this.elements.alerts.forEach((alert) => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    }

    updateRateLimitIndicator() {
        if (this.elements.rateLimitIndicator) {
            const remaining = parseInt(this.elements.rateLimitIndicator.dataset.remaining);
            const resetTime = parseInt(this.elements.rateLimitIndicator.dataset.reset);
            
            if (remaining <= 2) {
                this.elements.rateLimitIndicator.classList.remove('hidden');
                this.elements.rateLimitIndicator.textContent = 
                    `เหลือ ${remaining} ครั้ง (รีเซ็ตใน ${Math.ceil((resetTime - Date.now()) / 1000)} วินาที)`;
            }
        }
    }

    checkFailedAttempts() {
        const failedAttempts = sessionStorage.getItem('failedAttempts') || 0;
        
        if (failedAttempts >= 3) {
            // Disable form for 5 minutes
            if (this.elements.form) {
                this.elements.form.style.opacity = '0.5';
                this.elements.form.style.pointerEvents = 'none';
                
                setTimeout(() => {
                    this.elements.form.style.opacity = '1';
                    this.elements.form.style.pointerEvents = 'auto';
                    sessionStorage.removeItem('failedAttempts');
                }, 300000); // 5 minutes
            }
        }
    }

    showError(message) {
        // Create error alert
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 rounded-lg auth-transition';
        errorDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">${message}</p>
                </div>
            </div>
        `;
        
        // Insert after form
        if (this.elements.form) {
            this.elements.form.parentNode.insertBefore(errorDiv, this.elements.form.nextSibling);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.transition = 'opacity 0.5s ease-out';
                errorDiv.style.opacity = '0';
                setTimeout(() => errorDiv.remove(), 500);
            }, 5000);
        }
    }

    clearSensitiveData() {
        // Clear password fields
        if (this.elements.passwordInput) {
            this.elements.passwordInput.value = '';
        }
        
        // Clear any sensitive data from memory
        if (this.elements.emailInput) {
            this.elements.emailInput.value = '';
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthManager();
});
