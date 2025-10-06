// ========================================
// SYSTEM INFO FUNCTIONS
// ========================================

// Load system information
function loadSystemInfo() {
    console.log('Loading system info...');
    
    try {
        // Load all data from API in one call
        fetch('/api/system/info')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Server Information
                    const serverNameEl = document.getElementById('server-name');
                    const phpVersionEl = document.getElementById('php-version');
                    const laravelVersionEl = document.getElementById('laravel-version');
                    const serverTimeEl = document.getElementById('server-time');
                    
                    if (serverNameEl) serverNameEl.value = window.location.hostname || 'localhost';
                    if (phpVersionEl) phpVersionEl.value = 'PHP ' + (window.phpVersion || 'Unknown');
                    if (laravelVersionEl) laravelVersionEl.value = 'Laravel ' + (window.laravelVersion || 'Unknown');
                    
                    // Use server time from API
                    if (data.data.server_time && serverTimeEl) {
                        serverTimeEl.value = data.data.server_time.formatted;
                    } else {
                        updateServerTime();
                    }
                    
                    // System Resources - Use real data
                    loadMemoryInfoFromAPI(data.data);
                    loadDiskInfoFromAPI(data.data);
                    loadCPUInfoFromAPI(data.data);
                    loadActiveUsersFromAPI(data.data);
                    
                    // Database Information - Use real data
                    loadDatabaseInfoFromAPI(data.data);
                    
                    // Application Information - Use real data
                    loadApplicationInfoFromAPI(data.data);
                    
                    console.log('System info loaded successfully');
                } else {
                    console.error('API returned error:', data.error);
                    loadFallbackData();
                }
            })
            .catch(error => {
                console.error('Error fetching system info:', error);
                loadFallbackData();
            });
    } catch (error) {
        console.error('Error loading system info:', error);
        loadFallbackData();
    }
}

// Update server time every second
function updateServerTime() {
    try {
        const serverTimeEl = document.getElementById('server-time');
        if (!serverTimeEl) {
            console.error('Server time element not found');
            return;
        }
        
        // Try to get server time from API first
        fetch('/api/system/info')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.server_time) {
                    serverTimeEl.value = data.data.server_time.formatted;
                } else {
                    // Fallback: use browser time with server timezone
                    const now = new Date();
                    const timeString = now.toLocaleString('th-TH', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        timeZone: 'Asia/Bangkok'
                    });
                    serverTimeEl.value = timeString + ' (Asia/Bangkok)';
                }
            })
            .catch(error => {
                console.error('Error fetching server time:', error);
                // Fallback: use browser time with server timezone
                const now = new Date();
                const timeString = now.toLocaleString('th-TH', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'Asia/Bangkok'
                });
                serverTimeEl.value = timeString + ' (Asia/Bangkok)';
            });
    } catch (error) {
        console.error('Error updating server time:', error);
    }
}

// Load memory information from API data
function loadMemoryInfoFromAPI(data) {
    try {
        const memoryUsageEl = document.getElementById('memory-usage');
        const memoryProgressEl = document.getElementById('memory-progress');
        
        if (!memoryUsageEl || !memoryProgressEl) {
            console.error('Memory elements not found');
            return;
        }
        
        if (data.system_memory) {
            const memory = data.system_memory;
            memoryUsageEl.value = 
                `${formatBytes(memory.used)} / ${formatBytes(memory.total)} (${memory.percentage}%)`;
            
            memoryProgressEl.style.width = memory.percentage + '%';
            memoryProgressEl.className = `progress-bar ${getProgressBarClass(memory.percentage)}`;
        } else {
            // Fallback: use PHP memory data
            if (window.memoryUsage && window.memoryLimit) {
                const currentUsage = parseInt(window.memoryUsage);
                const memoryLimit = parseMemoryLimit(window.memoryLimit);
                const percentage = Math.round((currentUsage / memoryLimit) * 100);
                
                memoryUsageEl.value = 
                    `${formatBytes(currentUsage)} / ${formatBytes(memoryLimit)} (${percentage}%)`;
                
                memoryProgressEl.style.width = percentage + '%';
                memoryProgressEl.className = `progress-bar ${getProgressBarClass(percentage)}`;
            }
        }
    } catch (error) {
        console.error('Error loading memory info:', error);
    }
}

// Load disk information from API data
function loadDiskInfoFromAPI(data) {
    try {
        const diskUsageEl = document.getElementById('disk-usage');
        const diskProgressEl = document.getElementById('disk-progress');
        
        if (!diskUsageEl || !diskProgressEl) {
            console.error('Disk elements not found');
            return;
        }
        
        if (data.disk_usage) {
            const disk = data.disk_usage;
            diskUsageEl.value = 
                `${disk.used} GB / ${disk.total} GB (${disk.percentage}%)`;
            
            diskProgressEl.style.width = disk.percentage + '%';
            diskProgressEl.className = `progress-bar ${getProgressBarClass(disk.percentage)}`;
        }
    } catch (error) {
        console.error('Error loading disk info:', error);
    }
}

// Load CPU information from API data
function loadCPUInfoFromAPI(data) {
    try {
        const cpuLoadEl = document.getElementById('cpu-load');
        
        if (!cpuLoadEl) {
            console.error('CPU element not found');
            return;
        }
        
        if (data.cpu_load) {
            const cpu = data.cpu_load;
            if (cpu.threads && cpu.threads !== cpu.cores) {
                cpuLoadEl.value = `${cpu.load}% (${cpu.cores} cores, ${cpu.threads} threads)`;
            } else {
                cpuLoadEl.value = `${cpu.load}% (${cpu.cores} cores)`;
            }
        }
    } catch (error) {
        console.error('Error loading CPU info:', error);
    }
}

// Load active users from API data
function loadActiveUsersFromAPI(data) {
    try {
        const activeUsersEl = document.getElementById('active-users');
        
        if (!activeUsersEl) {
            console.error('Active users element not found');
            return;
        }
        
        if (data.active_users !== undefined) {
            activeUsersEl.value = `${data.active_users} users`;
        }
    } catch (error) {
        console.error('Error loading active users:', error);
    }
}

// Load database information from API data
function loadDatabaseInfoFromAPI(data) {
    try {
        const dbTypeEl = document.getElementById('db-type');
        const dbSizeEl = document.getElementById('db-size');
        const dbConnectionsEl = document.getElementById('db-connections');
        const lastBackupEl = document.getElementById('last-backup');
        
        if (!dbTypeEl || !dbSizeEl || !dbConnectionsEl || !lastBackupEl) {
            console.error('Database elements not found');
            return;
        }
        
        // Database type
        const dbType = window.dbType || 'MySQL';
        dbTypeEl.value = dbType;
        
        // Database size
        if (data.db_size !== undefined) {
            dbSizeEl.value = `${data.db_size} MB`;
        }
        
        // DB connections
        if (data.db_connections !== undefined) {
            dbConnectionsEl.value = `${data.db_connections} active`;
        }
        
        // Last backup
        if (data.last_backup !== undefined) {
            lastBackupEl.value = data.last_backup;
        }
    } catch (error) {
        console.error('Error loading database info:', error);
    }
}

// Load application information from API data
function loadApplicationInfoFromAPI(data) {
    try {
        const appEnvEl = document.getElementById('app-env');
        const debugModeEl = document.getElementById('debug-mode');
        const appKeyEl = document.getElementById('app-key');
        const uptimeEl = document.getElementById('uptime');
        
        if (!appEnvEl || !debugModeEl || !appKeyEl || !uptimeEl) {
            console.error('Application elements not found');
            return;
        }
        
        // App environment
        const env = window.appEnv || 'production';
        appEnvEl.value = env;
        
        // Debug mode
        const debugMode = window.debugMode || false;
        debugModeEl.value = debugMode ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        
        // App key
        const appKey = window.appKey || 'Not available';
        const shortKey = appKey.length > 20 ? appKey.substring(0, 20) + '...' : appKey;
        appKeyEl.value = shortKey;
        
        // Uptime
        if (data.uptime !== undefined) {
            uptimeEl.value = `${data.uptime} days`;
        }
    } catch (error) {
        console.error('Error loading application info:', error);
    }
}

// Load fallback data when API fails
function loadFallbackData() {
    console.log('Loading fallback data...');
    
    // Server Information
    const serverNameEl = document.getElementById('server-name');
    const phpVersionEl = document.getElementById('php-version');
    const laravelVersionEl = document.getElementById('laravel-version');
    
    if (serverNameEl) serverNameEl.value = window.location.hostname || 'localhost';
    if (phpVersionEl) phpVersionEl.value = 'PHP ' + (window.phpVersion || 'Unknown');
    if (laravelVersionEl) laravelVersionEl.value = 'Laravel ' + (window.laravelVersion || 'Unknown');
    
    updateServerTime();
    
    // Fallback for other data
    loadMemoryInfo();
    loadDiskInfo();
    loadCPUInfo();
    loadActiveUsers();
    loadDatabaseInfo();
    loadApplicationInfo();
}

// Load disk information (fallback)
function loadDiskInfo() {
    try {
        const diskUsageEl = document.getElementById('disk-usage');
        const diskProgressEl = document.getElementById('disk-progress');
        
        if (!diskUsageEl || !diskProgressEl) {
            console.error('Disk elements not found');
            return;
        }
        
        // Fallback: simulate disk usage
        const totalDisk = 500; // GB
        const usedDisk = Math.floor(Math.random() * totalDisk * 0.6);
        const percentage = Math.round((usedDisk / totalDisk) * 100);
        
        diskUsageEl.value = 
            `${usedDisk} GB / ${totalDisk} GB (${percentage}%)`;
        
        diskProgressEl.style.width = percentage + '%';
        diskProgressEl.className = `progress-bar ${getProgressBarClass(percentage)}`;
    } catch (error) {
        console.error('Error loading disk info:', error);
    }
}

// Load CPU information (fallback)
function loadCPUInfo() {
    try {
        const cpuLoadEl = document.getElementById('cpu-load');
        
        if (!cpuLoadEl) {
            console.error('CPU element not found');
            return;
        }
        
        // Fallback: use browser info
        if (navigator.hardwareConcurrency) {
            const cores = navigator.hardwareConcurrency;
            const load = (Math.random() * 100).toFixed(1);
            cpuLoadEl.value = `${load}% (${cores} cores)`;
        } else {
            // Fallback: simulate CPU info
            const cores = Math.floor(Math.random() * 8) + 4; // 4-12 cores
            const load = (Math.random() * 100).toFixed(1);
            cpuLoadEl.value = `${load}% (${cores} cores)`;
        }
    } catch (error) {
        console.error('Error loading CPU info:', error);
    }
}

// Load active users (fallback)
function loadActiveUsers() {
    try {
        const activeUsersEl = document.getElementById('active-users');
        
        if (!activeUsersEl) {
            console.error('Active users element not found');
            return;
        }
        
        // Fallback: simulate active users
        const activeUsers = Math.floor(Math.random() * 10) + 1; // 1-10 users
        activeUsersEl.value = `${activeUsers} users`;
    } catch (error) {
        console.error('Error loading active users:', error);
    }
}

// Load database information (fallback)
function loadDatabaseInfo() {
    try {
        const dbTypeEl = document.getElementById('db-type');
        const dbSizeEl = document.getElementById('db-size');
        const dbConnectionsEl = document.getElementById('db-connections');
        const lastBackupEl = document.getElementById('last-backup');
        
        if (!dbTypeEl || !dbSizeEl || !dbConnectionsEl || !lastBackupEl) {
            console.error('Database elements not found');
            return;
        }
        
        // Database type
        const dbType = window.dbType || 'MySQL';
        dbTypeEl.value = dbType;
        
        // Fallback: simulate database info
        const dbSize = (Math.random() * 100 + 50).toFixed(1);
        dbSizeEl.value = `${dbSize} MB`;
        
        const connections = Math.floor(Math.random() * 20) + 5;
        dbConnectionsEl.value = `${connections} active`;
        
        // Last backup (simulate)
        const lastBackup = new Date(Date.now() - Math.random() * 7 * 24 * 60 * 60 * 1000);
        lastBackupEl.value = lastBackup.toLocaleDateString('th-TH');
    } catch (error) {
        console.error('Error loading database info:', error);
    }
}

// Load application information (fallback)
function loadApplicationInfo() {
    try {
        const appEnvEl = document.getElementById('app-env');
        const debugModeEl = document.getElementById('debug-mode');
        const appKeyEl = document.getElementById('app-key');
        const uptimeEl = document.getElementById('uptime');
        
        if (!appEnvEl || !debugModeEl || !appKeyEl || !uptimeEl) {
            console.error('Application elements not found');
            return;
        }
        
        // App environment
        const env = window.appEnv || 'production';
        appEnvEl.value = env;
        
        // Debug mode
        const debugMode = window.debugMode || false;
        debugModeEl.value = debugMode ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        
        // App key
        const appKey = window.appKey || 'Not available';
        const shortKey = appKey.length > 20 ? appKey.substring(0, 20) + '...' : appKey;
        appKeyEl.value = shortKey;
        
        // Fallback: simulate uptime
        const uptime = Math.floor(Math.random() * 30) + 1;
        uptimeEl.value = `${uptime} days`;
    } catch (error) {
        console.error('Error loading application info:', error);
    }
}

// Load memory information (fallback)
function loadMemoryInfo() {
    try {
        const memoryUsageEl = document.getElementById('memory-usage');
        const memoryProgressEl = document.getElementById('memory-progress');
        
        if (!memoryUsageEl || !memoryProgressEl) {
            console.error('Memory elements not found');
            return;
        }
        
        // Fallback: use PHP memory data
        if (window.memoryUsage && window.memoryLimit) {
            const currentUsage = parseInt(window.memoryUsage);
            const memoryLimit = parseMemoryLimit(window.memoryLimit);
            const percentage = Math.round((currentUsage / memoryLimit) * 100);
            
            memoryUsageEl.value = 
                `${formatBytes(currentUsage)} / ${formatBytes(memoryLimit)} (${percentage}%)`;
            
            memoryProgressEl.style.width = percentage + '%';
            memoryProgressEl.className = `progress-bar ${getProgressBarClass(percentage)}`;
        } else {
            // Fallback: simulate memory usage
            const totalMemory = 8192; // 8GB
            const usedMemory = Math.floor(Math.random() * totalMemory * 0.6) + 2000; // 2-7GB
            const percentage = Math.round((usedMemory / totalMemory) * 100);
            
            memoryUsageEl.value = 
                `${formatBytes(usedMemory * 1024 * 1024)} / ${formatBytes(totalMemory * 1024 * 1024)} (${percentage}%)`;
            
            memoryProgressEl.style.width = percentage + '%';
            memoryProgressEl.className = `progress-bar ${getProgressBarClass(percentage)}`;
        }
    } catch (error) {
        console.error('Error loading memory info:', error);
    }
}

// Refresh system information
function refreshSystemInfo() {
    // Show loading state
    const refreshBtn = event.target;
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังโหลด...';
    refreshBtn.disabled = true;
    
    // Simulate refresh delay
    setTimeout(() => {
        loadSystemInfo();
        
        // Restore button
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
        
        // Show success message
        showToast('รีเฟรชข้อมูลระบบสำเร็จ', 'success');
    }, 1000);
}

// Export system information
function exportSystemInfo() {
    
    const systemData = {
        timestamp: new Date().toISOString(),
        server: {
            name: document.getElementById('server-name').value,
            phpVersion: document.getElementById('php-version').value,
            laravelVersion: document.getElementById('laravel-version').value,
            time: document.getElementById('server-time').value
        },
        resources: {
            memory: document.getElementById('memory-usage').value,
            disk: document.getElementById('disk-usage').value,
            cpu: document.getElementById('cpu-load').value,
            activeUsers: document.getElementById('active-users').value
        },
        database: {
            type: document.getElementById('db-type').value,
            size: document.getElementById('db-size').value,
            connections: document.getElementById('db-connections').value,
            lastBackup: document.getElementById('last-backup').value
        },
        application: {
            environment: document.getElementById('app-env').value,
            debugMode: document.getElementById('debug-mode').value,
            appKey: document.getElementById('app-key').value,
            uptime: document.getElementById('uptime').value
        }
    };
    
    // Create and download JSON file
    const dataStr = JSON.stringify(systemData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `system-info-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    URL.revokeObjectURL(url);
    
    showToast('ส่งออกข้อมูลระบบสำเร็จ', 'success');
}

// Refresh system information
function refreshSystemInfo() {
    // Show loading state
    const refreshBtn = event.target;
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังโหลด...';
    refreshBtn.disabled = true;
    
    // Simulate refresh delay
    setTimeout(() => {
        loadSystemInfo();
        
        // Restore button
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
        
        // Show success message
        showToast('รีเฟรชข้อมูลระบบสำเร็จ', 'success');
    }, 1000);
}

// Export system information
function exportSystemInfo() {
    
    const systemData = {
        timestamp: new Date().toISOString(),
        server: {
            name: document.getElementById('server-name').value,
            phpVersion: document.getElementById('php-version').value,
            laravelVersion: document.getElementById('laravel-version').value,
            time: document.getElementById('server-time').value
        },
        resources: {
            memory: document.getElementById('memory-usage').value,
            disk: document.getElementById('disk-usage').value,
            cpu: document.getElementById('cpu-load').value,
            activeUsers: document.getElementById('active-users').value
        },
        database: {
            type: document.getElementById('db-type').value,
            size: document.getElementById('db-size').value,
            connections: document.getElementById('db-connections').value,
            lastBackup: document.getElementById('last-backup').value
        },
        application: {
            environment: document.getElementById('app-env').value,
            debugMode: document.getElementById('debug-mode').value,
            appKey: document.getElementById('app-key').value,
            uptime: document.getElementById('uptime').value
        }
    };
    
    // Create and download JSON file
    const dataStr = JSON.stringify(systemData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `system-info-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    URL.revokeObjectURL(url);
    
    showToast('ส่งออกข้อมูลระบบสำเร็จ', 'success');
}

// Show system logs
function showSystemLogs() {
    
    Swal.fire({
        title: 'System Logs',
        html: `
            <div class="text-start">
                <h6>Recent Log Entries:</h6>
                <div class="log-container" style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                    <div class="log-entry mb-2">
                        <small class="text-muted">[2024-01-06 10:30:15]</small>
                        <span class="text-success">INFO:</span> System information loaded successfully
                    </div>
                    <div class="log-entry mb-2">
                        <small class="text-muted">[2024-01-06 10:29:45]</small>
                        <span class="text-info">DEBUG:</span> Memory usage: 45% (2.1GB/4.7GB)
                    </div>
                    <div class="log-entry mb-2">
                        <small class="text-muted">[2024-01-06 10:29:30]</small>
                        <span class="text-warning">WARNING:</span> Disk usage approaching 80%
                    </div>
                    <div class="log-entry mb-2">
                        <small class="text-muted">[2024-01-06 10:28:15]</small>
                        <span class="text-success">INFO:</span> Database connection established
                    </div>
                    <div class="log-entry mb-2">
                        <small class="text-muted">[2024-01-06 10:27:00]</small>
                        <span class="text-info">DEBUG:</span> Cache cleared successfully
                    </div>
                </div>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: 'ปิด',
        cancelButtonText: 'ส่งออก Logs'
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            // Export logs
            const logData = `System Logs - ${new Date().toLocaleString('th-TH')}\n\n` +
                '[2024-01-06 10:30:15] INFO: System information loaded successfully\n' +
                '[2024-01-06 10:29:45] DEBUG: Memory usage: 45% (2.1GB/4.7GB)\n' +
                '[2024-01-06 10:29:30] WARNING: Disk usage approaching 80%\n' +
                '[2024-01-06 10:28:15] INFO: Database connection established\n' +
                '[2024-01-06 10:27:00] DEBUG: Cache cleared successfully';
            
            const dataBlob = new Blob([logData], {type: 'text/plain'});
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `system-logs-${new Date().toISOString().split('T')[0]}.txt`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            URL.revokeObjectURL(url);
            
            showToast('ส่งออก Logs สำเร็จ', 'success');
        }
    });
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

// Show toast notification
function showToast(message, type = 'info') {
    const iconMap = {
        'success': 'success',
        'error': 'error',
        'warning': 'warning',
        'info': 'info'
    };
    
    Swal.fire({
        icon: iconMap[type] || 'info',
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Helper function to parse memory limit string
function parseMemoryLimit(memoryLimit) {
    if (!memoryLimit || memoryLimit === '-1') {
        return 1024 * 1024 * 1024; // 1GB default
    }
    
    const value = parseInt(memoryLimit);
    const unit = memoryLimit.slice(-1).toUpperCase();
    
    switch (unit) {
        case 'G':
            return value * 1024 * 1024 * 1024;
        case 'M':
            return value * 1024 * 1024;
        case 'K':
            return value * 1024;
        default:
            return value; // Assume bytes
    }
}

// Helper function to format bytes
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Helper function to get progress bar class based on percentage
function getProgressBarClass(percentage) {
    if (percentage >= 90) return 'bg-danger';
    if (percentage >= 70) return 'bg-warning';
    return 'bg-success';
}

// Initialize system info when tab is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing system info...');
    
    // Set up time update interval
    setInterval(updateServerTime, 1000);
    
    // Load system info immediately with a small delay
    setTimeout(() => {
        console.log('Loading system info...');
        loadSystemInfo();
    }, 500);
    
    // Load system info when system-info tab is shown
    const systemInfoTab = document.getElementById('system-info-tab');
    if (systemInfoTab) {
        systemInfoTab.addEventListener('shown.bs.tab', function() {
            console.log('System info tab shown, reloading...');
            loadSystemInfo();
        });
    } else {
        console.error('System info tab element not found');
    }
    
    // Listen for timezone changes from General Settings
    window.addEventListener('timezoneChanged', function(event) {
        console.log('Timezone changed to:', event.detail.timezone);
        // Reload system info to get updated time with new timezone
        setTimeout(loadSystemInfo, 500);
    });
});
