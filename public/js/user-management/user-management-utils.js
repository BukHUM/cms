// User Management Utilities
window.UserManagementUtils = {
    // Format date
    formatDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('th-TH', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    },

    // Format datetime
    formatDateTime: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('th-TH', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    // Generate slug from text
    generateSlug: function(text) {
        return text
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    },

    // Validate email
    validateEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    // Validate phone
    validatePhone: function(phone) {
        const re = /^[0-9\-\+\(\)\s]+$/;
        return re.test(phone) && phone.length >= 8;
    },

    // Show loading
    showLoading: function(message = 'กำลังประมวลผล...') {
        SwalHelper.loading(message);
    },

    // Hide loading
    hideLoading: function() {
        SwalHelper.close();
    },

    // Show success message
    showSuccess: function(message) {
        SwalHelper.success(message);
    },

    // Show error message
    showError: function(message) {
        SwalHelper.error(message);
    },

    // Show confirmation dialog
    showConfirm: function(message, callback) {
        SwalHelper.confirmDelete(message, callback);
    },

    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Copy to clipboard
    copyToClipboard: function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                this.showSuccess('คัดลอกเรียบร้อยแล้ว');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            this.showSuccess('คัดลอกเรียบร้อยแล้ว');
        }
    },

    // Get CSRF token
    getCSRFToken: function() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },

    // Make API request
    apiRequest: function(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            }
        };

        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        };

        return fetch(url, mergedOptions);
    },

    // Handle API response
    handleApiResponse: function(response) {
        return response.json().then(data => {
            if (data.success) {
                return data;
            } else {
                throw new Error(data.message || 'เกิดข้อผิดพลาด');
            }
        });
    }
};

// Global functions for backward compatibility
window.formatDate = UserManagementUtils.formatDate;
window.formatDateTime = UserManagementUtils.formatDateTime;
window.generateSlug = UserManagementUtils.generateSlug;
window.validateEmail = UserManagementUtils.validateEmail;
window.validatePhone = UserManagementUtils.validatePhone;
