/**
 * System Info JavaScript
 * แสดงข้อมูลระบบและทรัพยากรเซิร์ฟเวอร์
 */

class SystemInfo {
    constructor() {
        this.isRefreshing = false;
        this.isExporting = false;
        this.refreshInterval = null;
        this.systemData = {};
        
        this.init();
    }

    /**
     * Initialize System Info
     */
    init() {
        this.loadSystemInfo();
        this.bindEvents();
        this.setupAutoRefresh();
        this.startRealTimeUpdates();
    }

    /**
     * Load system information
     */
    async loadSystemInfo() {
        try {
            this.showRefreshing(true);
            
            const response = await fetch('/admin/settings/system-info', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.systemData = result.data;
                this.populateSystemInfo();
                this.updateProgressBars();
                // No auto success message for system info
            } else {
                this.showError(result.message || 'ไม่สามารถโหลดข้อมูลระบบได้');
            }

        } catch (error) {
            console.error('Error loading system info:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดข้อมูลระบบ');
        } finally {
            this.showRefreshing(false);
        }
    }

    /**
     * Populate system information
     */
    populateSystemInfo() {
        // Server Information
        this.setValue('server-name', this.systemData.serverName || 'Unknown');
        this.setValue('php-version', this.systemData.phpVersion || 'Unknown');
        this.setValue('laravel-version', this.systemData.laravelVersion || 'Unknown');
        this.setValue('server-time', this.systemData.serverTime || 'Unknown');

        // System Resources
        this.setValue('memory-usage', this.systemData.memoryUsage || 'Unknown');
        this.setValue('disk-usage', this.systemData.diskUsage || 'Unknown');
        this.setValue('cpu-load', this.systemData.cpuLoad || 'Unknown');
        this.setValue('active-users', this.systemData.activeUsers || 'Unknown');

        // Database Information
        this.setValue('db-type', this.systemData.dbType || 'Unknown');
        this.setValue('db-size', this.systemData.dbSize || 'Unknown');
        this.setValue('db-connections', this.systemData.dbConnections || 'Unknown');
        this.setValue('last-backup', this.systemData.lastBackup || 'Unknown');

        // Application Information
        this.setValue('app-env', this.systemData.appEnv || 'Unknown');
        this.setValue('debug-mode', this.systemData.debugMode ? 'Enabled' : 'Disabled');
        this.setValue('app-key', this.systemData.appKey || 'Unknown');
        this.setValue('uptime', this.systemData.uptime || 'Unknown');
    }

    /**
     * Set input value
     */
    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.value = value;
        }
    }

    /**
     * Update progress bars
     */
    updateProgressBars() {
        // Memory usage progress bar
        const memoryProgress = document.getElementById('memory-progress');
        if (memoryProgress && this.systemData.memoryUsagePercent) {
            memoryProgress.style.width = this.systemData.memoryUsagePercent + '%';
            memoryProgress.className = `progress-bar ${this.getProgressBarClass(this.systemData.memoryUsagePercent)}`;
        }

        // Disk usage progress bar
        const diskProgress = document.getElementById('disk-progress');
        if (diskProgress && this.systemData.diskUsagePercent) {
            diskProgress.style.width = this.systemData.diskUsagePercent + '%';
            diskProgress.className = `progress-bar ${this.getProgressBarClass(this.systemData.diskUsagePercent)}`;
        }
    }

    /**
     * Get progress bar class based on percentage
     */
    getProgressBarClass(percentage) {
        if (percentage >= 90) return 'bg-danger';
        if (percentage >= 70) return 'bg-warning';
        if (percentage >= 50) return 'bg-info';
        return 'bg-success';
    }

    /**
     * Bind events
     */
    bindEvents() {
        // Refresh button
        const refreshBtn = document.querySelector('button[onclick="refreshSystemInfo()"]');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.refreshSystemInfo();
            });
        }

        // Export button
        const exportBtn = document.querySelector('button[onclick="exportSystemInfo()"]');
        if (exportBtn) {
            exportBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.exportSystemInfo();
            });
        }

        // Show logs button
        const logsBtn = document.querySelector('button[onclick="showSystemLogs()"]');
        if (logsBtn) {
            logsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showSystemLogs();
            });
        }
    }

    /**
     * Setup auto refresh
     */
    setupAutoRefresh() {
        // Auto refresh every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.loadSystemInfo();
        }, 30000);
    }

    /**
     * Start real-time updates
     */
    startRealTimeUpdates() {
        // Update server time every second
        setInterval(() => {
            const now = new Date();
            const timeString = now.toLocaleString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            this.setValue('server-time', timeString);
        }, 1000);

        // Update uptime every minute
        setInterval(() => {
            if (this.systemData.startTime) {
                const uptime = this.calculateUptime(this.systemData.startTime);
                this.setValue('uptime', uptime);
            }
        }, 60000);
    }

    /**
     * Calculate uptime
     */
    calculateUptime(startTime) {
        const start = new Date(startTime);
        const now = new Date();
        const diff = now - start;

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        if (days > 0) {
            return `${days} วัน ${hours} ชั่วโมง ${minutes} นาที`;
        } else if (hours > 0) {
            return `${hours} ชั่วโมง ${minutes} นาที`;
        } else {
            return `${minutes} นาที`;
        }
    }

    /**
     * Refresh system info
     */
    async refreshSystemInfo() {
        if (this.isRefreshing) return;

        await this.loadSystemInfo();
        this.showSuccess('รีเฟรชข้อมูลระบบสำเร็จ');
    }

    /**
     * Export system info
     */
    async exportSystemInfo() {
        if (this.isExporting) return;

        try {
            this.isExporting = true;
            this.showExportLoading(true);

            const response = await fetch('/admin/settings/system-info/export', {
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
                this.showSuccess(result.message || 'ส่งออกข้อมูลระบบสำเร็จ');
            } else {
                this.showError(result.message || 'ไม่สามารถส่งออกข้อมูลระบบได้');
            }

        } catch (error) {
            console.error('Error exporting system info:', error);
            this.showError('เกิดข้อผิดพลาดในการส่งออกข้อมูลระบบ');
        } finally {
            this.isExporting = false;
            this.showExportLoading(false);
        }
    }






    /**
     * Show refreshing state
     */
    showRefreshing(show) {
        const refreshBtn = document.querySelector('button[onclick="refreshSystemInfo()"]');
        if (refreshBtn) {
            if (show) {
                refreshBtn.disabled = true;
                refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังรีเฟรช...';
            } else {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>รีเฟรชข้อมูล';
            }
        }
    }

    /**
     * Show export loading state
     */
    showExportLoading(show) {
        const exportBtn = document.querySelector('button[onclick="exportSystemInfo()"]');
        if (exportBtn) {
            if (show) {
                exportBtn.disabled = true;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังส่งออก...';
            } else {
                exportBtn.disabled = false;
                exportBtn.innerHTML = '<i class="fas fa-download me-2"></i>ส่งออกข้อมูล';
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
     * Cleanup on page unload
     */
    cleanup() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
    }
}

// Global functions for buttons
function refreshSystemInfo() {
    if (window.systemInfo) {
        window.systemInfo.refreshSystemInfo();
    }
}

function exportSystemInfo() {
    if (window.systemInfo) {
        window.systemInfo.exportSystemInfo();
    }
}



// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('server-name')) {
        window.systemInfo = new SystemInfo();
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.systemInfo) {
        window.systemInfo.cleanup();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SystemInfo;
}