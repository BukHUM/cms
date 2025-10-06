// ========================================
// UTILITY FUNCTIONS
// ========================================

// Form validation functions
function setupFormValidation() {
    // Email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const email = this.value;
            if (email && !isValidEmail(email)) {
                this.classList.add('is-invalid');
                showFieldError(this, 'รูปแบบอีเมลไม่ถูกต้อง');
            } else {
                this.classList.remove('is-invalid');
                hideFieldError(this);
            }
        });
    });
    
    // URL validation
    const urlInputs = document.querySelectorAll('input[type="url"]');
    urlInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const url = this.value;
            if (url && !isValidUrl(url)) {
                this.classList.add('is-invalid');
                showFieldError(this, 'รูปแบบ URL ไม่ถูกต้อง');
            } else {
                this.classList.remove('is-invalid');
                hideFieldError(this);
            }
        });
    });
    
    // Number validation
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = parseInt(this.value);
            const min = parseInt(this.min) || 0;
            const max = parseInt(this.max) || Infinity;
            
            if (value < min || value > max) {
                this.classList.add('is-invalid');
                showFieldError(this, `ค่าต้องอยู่ระหว่าง ${min} - ${max}`);
            } else {
                this.classList.remove('is-invalid');
                hideFieldError(this);
            }
        });
    });
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// URL validation helper
function isValidUrl(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

// Show field error
function showFieldError(input, message) {
    hideFieldError(input);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
}

// Hide field error
function hideFieldError(input) {
    const errorDiv = input.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// ========================================
// SWITCH STYLING FUNCTIONS
// ========================================

// Setup Query Logging Switch Styling
function setupQueryLoggingSwitch() {
    // Setup all switches across all tabs
    const switches = [
        // General Settings
        { id: 'siteEnabled', labelId: 'siteEnabledLabel' },
        { id: 'maintenanceMode', labelId: 'maintenanceModeLabel' },
        { id: 'debugMode', labelId: 'debugModeLabel' },
        { id: 'autoSave', labelId: 'autoSaveLabel' },
        { id: 'notifications', labelId: 'notificationsLabel' },
        { id: 'analytics', labelId: 'analyticsLabel' },
        { id: 'logging', labelId: 'loggingLabel' },
        { id: 'maintenance', labelId: 'maintenanceLabel' },
        { id: 'updates', labelId: 'updatesLabel' },
        
        // Email Settings
        { id: 'mailEnabled', labelId: 'mailEnabledLabel' },
        
        // Security Settings
        { id: 'requireSpecialChars', labelId: 'requireSpecialCharsLabel' },
        { id: 'twoFactorAuth', labelId: 'twoFactorAuthLabel' },
        { id: 'ipWhitelist', labelId: 'ipWhitelistLabel' },
        
        // Backup Settings
        { id: 'backupEnabled', labelId: 'backupEnabledLabel' },
        
        // Audit Log Settings
        { id: 'auditEnabled', labelId: 'auditEnabledLabel' },
        { id: 'auditRealTime', labelId: 'auditRealTimeLabel' },
        { id: 'auditEmailAlerts', labelId: 'auditEmailAlertsLabel' },
        { id: 'auditSensitiveActions', labelId: 'auditSensitiveActionsLabel' },
        
        // Performance Settings
        { id: 'cacheEnabled', labelId: 'cacheEnabledLabel' },
        { id: 'queryLogging', labelId: 'queryLoggingLabel' },
        { id: 'compressionEnabled', labelId: 'compressionEnabledLabel' }
    ];
    
    switches.forEach(switchConfig => {
        const switchElement = document.getElementById(switchConfig.id);
        if (switchElement) {
            // Update switch styling on change
            switchElement.addEventListener('change', function() {
                updateSwitchStyle(switchConfig.id, switchConfig.labelId);
            });
            
            // Initial styling
            updateSwitchStyle(switchConfig.id, switchConfig.labelId);
        }
    });
}

// Update Switch Style (Generic function for all switches)
function updateSwitchStyle(switchId, labelId) {
    const switchElement = document.getElementById(switchId);
    const label = document.getElementById(labelId);
    
    if (switchElement && label) {
        if (switchElement.checked) {
            label.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>เปิดใช้งาน';
            label.classList.add('text-success', 'fw-bold');
            label.classList.remove('text-danger', 'text-muted');
        } else {
            label.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>ปิดใช้งาน';
            label.classList.add('text-danger');
            label.classList.remove('text-success', 'fw-bold', 'text-muted');
        }
    }
}

// ========================================
// LOAD SAVED SETTINGS
// ========================================

// Load saved settings from localStorage
function loadSavedSettings() {
    // Show loading indicator
    console.log('Loading saved settings...');
    
    // Load all settings
    loadGeneralSettings();
    loadEmailSettings();
    loadSecuritySettings();
    loadBackupSettings();
    loadAuditSettings();
    loadPerformanceSettings();
    
    console.log('All settings loaded from localStorage');
}

// Auto-save functionality
function setupAutoSave() {
    const forms = document.querySelectorAll('form[id$="SettingsForm"]');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Debounce auto-save
                clearTimeout(input.autoSaveTimeout);
                input.autoSaveTimeout = setTimeout(() => {
                    console.log('Auto-saving...');
                    // You can implement auto-save logic here
                }, 2000);
            });
        });
    });
}

// ========================================
// INITIALIZATION
// ========================================

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Load saved settings
    loadSavedSettings();
    
    // Setup form validation
    setupFormValidation();
    
    // Load last active tab
    loadLastActiveTab();
    
    // Load audit logs on page load
    loadAuditLogs();
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const mobileNav = document.querySelector('.settings-mobile-nav');
        const dropdown = document.getElementById('settingsDropdown');
        
        if (mobileNav && !mobileNav.contains(e.target) && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
            document.querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const dropdown = document.getElementById('settingsDropdown');
        if (window.innerWidth >= 768 && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
            document.querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
        }
    });
    
    // Auto-save functionality (optional)
    setupAutoSave();
    
    // Setup Query Logging Switch Styling
    setupQueryLoggingSwitch();
});
