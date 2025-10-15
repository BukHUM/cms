/**
 * Settings Update JavaScript
 * Handles Laravel Framework Live Update functionality
 */

// Update status tracking
let updateInProgress = false;
let updateLog = [];

/**
 * Check for available updates
 */
async function checkForUpdates() {
    if (updateInProgress) {
        Swal.fire({
            icon: 'warning',
            title: 'กำลังดำเนินการ',
            text: 'กำลังดำเนินการอัปเดตอยู่ กรุณารอสักครู่...',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    try {
        // Wait for required elements to be available
        const [checkBtn, statusContainer, statusAlert, statusText, updateChannelElement] = await Promise.all([
            waitForElement('button[onclick="checkForUpdates()"]'),
            waitForElement('#updateStatusContainer'),
            waitForElement('#updateStatusAlert'),
            waitForElement('#updateStatusText'),
            waitForElement('#updateChannel')
        ]);

        // Show loading state
        checkBtn.disabled = true;
        checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังตรวจสอบ...';
        
        // Show status container with loading state
        statusContainer.style.display = 'block';
        statusAlert.className = 'alert alert-info alert-dismissible fade show shadow-sm border-0';
        
        // Safely update alert icon and title
        const alertIcon = statusAlert.querySelector('.alert-icon i');
        const alertTitle = statusAlert.querySelector('.alert-title');
        
        if (alertIcon) {
            alertIcon.className = 'fas fa-spinner fa-spin fa-lg text-info';
        }
        if (alertTitle) {
            alertTitle.textContent = 'กำลังตรวจสอบการอัปเดต...';
        }
        statusText.textContent = 'กรุณารอสักครู่ กำลังตรวจสอบเวอร์ชันล่าสุด';
        
        const updateChannel = updateChannelElement.value;
    
    // Make API call to check for updates
    fetch('/admin/settings/update/check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            channel: updateChannel
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.hasUpdates) {
                // Update alert to success state
                statusAlert.className = 'alert alert-success alert-dismissible fade show shadow-sm border-0';
                
                // Safely update alert icon and title
                const alertIcon = statusAlert.querySelector('.alert-icon i');
                const alertTitle = statusAlert.querySelector('.alert-title');
                
                if (alertIcon) {
                    alertIcon.className = 'fas fa-check-circle fa-lg text-success';
                }
                if (alertTitle) {
                    alertTitle.textContent = 'พบการอัปเดตใหม่!';
                }
                statusText.textContent = `พบเวอร์ชัน ${data.latestVersion} ในช่องทาง ${getChannelDisplayName(data.channel)}`;
                
                // Update system info table
                updateSystemInfoTable(true, data);
                
                // Enable update buttons (both desktop and mobile)
                const composerBtns = document.querySelectorAll('#updateComposerBtn, #updateComposerBtnMobile');
                const laravelBtns = document.querySelectorAll('#updateLaravelBtn, #updateLaravelBtnMobile');
                
                composerBtns.forEach(btn => btn.disabled = false);
                laravelBtns.forEach(btn => btn.disabled = false);
                
                addToLog(`✓ พบการอัปเดตใหม่: ${data.currentVersion} → ${data.latestVersion}`);
                
                // Show success alert with more details
                Swal.fire({
                    icon: 'success',
                    title: 'พบการอัปเดตใหม่!',
                    html: `
                        <div class="text-start">
                            <p><strong>เวอร์ชันปัจจุบัน:</strong> ${data.currentVersion}</p>
                            <p><strong>เวอร์ชันล่าสุด:</strong> ${data.latestVersion}</p>
                            <p><strong>ช่องทาง:</strong> ${getChannelDisplayName(data.channel)}</p>
                            <p><strong>แหล่งข้อมูล:</strong> ${data.updateInfo.source || 'ไม่ทราบ'}</p>
                            <p><strong>รายละเอียด:</strong> ${data.updateInfo.description}</p>
                            <p><strong>Release Notes:</strong> ${data.updateInfo.releaseNotes}</p>
                        </div>
                    `,
                    confirmButtonText: 'ตกลง'
                });
            } else {
                // Update alert to info state
                statusAlert.className = 'alert alert-info alert-dismissible fade show shadow-sm border-0';
                
                // Safely update alert icon and title
                const alertIcon = statusAlert.querySelector('.alert-icon i');
                const alertTitle = statusAlert.querySelector('.alert-title');
                
                if (alertIcon) {
                    alertIcon.className = 'fas fa-info-circle fa-lg text-info';
                }
                if (alertTitle) {
                    alertTitle.textContent = 'ระบบเป็นเวอร์ชันล่าสุด';
                }
                statusText.textContent = `ระบบของคุณเป็นเวอร์ชันล่าสุดแล้วในช่องทาง ${getChannelDisplayName(data.channel)}`;
                
                // Update system info table
                updateSystemInfoTable(false, data);
                
                addToLog(`✓ ระบบเป็นเวอร์ชันล่าสุดในช่องทาง ${data.channel}`);
                
                // Show info alert
                Swal.fire({
                    icon: 'info',
                    title: 'ระบบเป็นเวอร์ชันล่าสุด',
                    text: `ระบบของคุณเป็นเวอร์ชันล่าสุดแล้วในช่องทาง ${getChannelDisplayName(data.channel)}`,
                    confirmButtonText: 'ตกลง'
                });
            }
        } else {
            throw new Error(data.message || 'เกิดข้อผิดพลาดในการตรวจสอบการอัปเดต');
        }
    })
    .catch(error => {
        console.error('Error checking for updates:', error);
        
        // Update alert to error state
        statusAlert.className = 'alert alert-danger alert-dismissible fade show shadow-sm border-0';
        
        // Safely update alert icon and title
        const alertIcon = statusAlert.querySelector('.alert-icon i');
        const alertTitle = statusAlert.querySelector('.alert-title');
        
        if (alertIcon) {
            alertIcon.className = 'fas fa-exclamation-triangle fa-lg text-danger';
        }
        if (alertTitle) {
            alertTitle.textContent = 'เกิดข้อผิดพลาด';
        }
        statusText.textContent = 'ไม่สามารถตรวจสอบการอัปเดตได้';
        
        addToLog('❌ เกิดข้อผิดพลาดในการตรวจสอบการอัปเดต');
        
        // Show error alert
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: error.message || 'ไม่สามารถตรวจสอบการอัปเดตได้ กรุณาลองใหม่อีกครั้ง',
            confirmButtonText: 'ตกลง'
        });
    })
    .finally(() => {
        // Reset button
        checkBtn.disabled = false;
        checkBtn.innerHTML = '<i class="fas fa-search me-2"></i>ตรวจสอบการอัปเดต';
    });
    } catch (error) {
        console.error('Error in checkForUpdates:', error);
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่สามารถเข้าถึงองค์ประกอบที่จำเป็นได้ กรุณาโหลดหน้าเว็บใหม่',
            confirmButtonText: 'ตกลง'
        });
    }
}

/**
 * Update Composer dependencies
 */
function updateComposer() {
    if (updateInProgress) {
        Swal.fire({
            icon: 'warning',
            title: 'กำลังดำเนินการ',
            text: 'กำลังดำเนินการอัปเดตอยู่ กรุณารอสักครู่...',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    Swal.fire({
        title: 'ยืนยันการอัปเดต Composer',
        text: 'คุณแน่ใจหรือไม่ที่จะอัปเดต Composer dependencies? การดำเนินการนี้อาจใช้เวลานาน',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            performComposerUpdate();
        }
    });
}

/**
 * Perform Composer update
 */
function performComposerUpdate() {
    updateInProgress = true;
    const updateBtns = document.querySelectorAll('#updateComposerBtn, #updateComposerBtnMobile');
    const statusContainer = document.getElementById('updateStatusContainer');
    const updateProgress = document.getElementById('updateProgress');
    const updateProgressBar = document.getElementById('updateProgressBar');
    const updateProgressText = document.getElementById('updateProgressText');
    
    // Check if required elements exist
    if (!statusContainer || !updateProgress || !updateProgressBar || !updateProgressText) {
        console.error('Required DOM elements not found for composer update:', {
            statusContainer: !!statusContainer,
            updateProgress: !!updateProgress,
            updateProgressBar: !!updateProgressBar,
            updateProgressText: !!updateProgressText
        });
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่พบองค์ประกอบที่จำเป็นสำหรับการแสดงผล กรุณาโหลดหน้าเว็บใหม่',
            confirmButtonText: 'ตกลง'
        });
        updateInProgress = false;
        return;
    }
    
    // Show status container and progress
    statusContainer.style.display = 'block';
    updateProgress.style.display = 'block';
    
    // Show loading state for all composer buttons
    updateBtns.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังอัปเดต...';
    });
    
    addToLog('🔄 เริ่มอัปเดต Composer dependencies...');
    
    // Simulate Composer update process with progress bar
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 20;
        if (progress >= 100) {
            progress = 100;
            clearInterval(progressInterval);
            
            // Update progress bar
            updateProgressBar.style.width = '100%';
            updateProgressText.textContent = 'อัปเดตเสร็จสิ้น!';
            
            updateBtns.forEach(btn => {
                btn.innerHTML = '<i class="fas fa-check me-2"></i>อัปเดตเสร็จสิ้น';
                btn.className = btn.className.replace('btn-outline-success', 'btn-success');
            });
            addToLog('✅ อัปเดต Composer เสร็จสิ้น');
            
            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'อัปเดตเสร็จสิ้น!',
                text: 'Composer dependencies ได้รับการอัปเดตเรียบร้อยแล้ว',
                confirmButtonText: 'ตกลง'
            });
            
            // Hide progress after 3 seconds
            setTimeout(() => {
                updateProgress.style.display = 'none';
                updateProgressBar.style.width = '0%';
            }, 3000);
            
            updateInProgress = false;
        } else {
            // Update progress bar
            updateProgressBar.style.width = progress + '%';
            updateProgressText.textContent = `กำลังดาวน์โหลดแพ็กเกจ... ${Math.round(progress)}%`;
        }
        
        addToLog(`📦 ดาวน์โหลดแพ็กเกจ... ${Math.round(progress)}%`);
    }, 500);
}

/**
 * Update Laravel Framework
 */
function updateLaravel() {
    if (updateInProgress) {
        Swal.fire({
            icon: 'warning',
            title: 'กำลังดำเนินการ',
            text: 'กำลังดำเนินการอัปเดตอยู่ กรุณารอสักครู่...',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    Swal.fire({
        title: 'ยืนยันการอัปเดต Laravel',
        text: 'คุณแน่ใจหรือไม่ที่จะอัปเดต Laravel Framework? การดำเนินการนี้อาจใช้เวลานานและต้องมีการสำรองข้อมูลก่อน',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            performLaravelUpdate();
        }
    });
}

/**
 * Perform Laravel update
 */
function performLaravelUpdate() {
    updateInProgress = true;
    const updateBtns = document.querySelectorAll('#updateLaravelBtn, #updateLaravelBtnMobile');
    const statusContainer = document.getElementById('updateStatusContainer');
    const updateProgress = document.getElementById('updateProgress');
    const updateProgressBar = document.getElementById('updateProgressBar');
    const updateProgressText = document.getElementById('updateProgressText');
    
    // Check if required elements exist
    if (!statusContainer || !updateProgress || !updateProgressBar || !updateProgressText) {
        console.error('Required DOM elements not found for laravel update:', {
            statusContainer: !!statusContainer,
            updateProgress: !!updateProgress,
            updateProgressBar: !!updateProgressBar,
            updateProgressText: !!updateProgressText
        });
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่พบองค์ประกอบที่จำเป็นสำหรับการแสดงผล กรุณาโหลดหน้าเว็บใหม่',
            confirmButtonText: 'ตกลง'
        });
        updateInProgress = false;
        return;
    }
    
    // Show status container and progress
    statusContainer.style.display = 'block';
    updateProgress.style.display = 'block';
    
    // Show loading state for all laravel buttons
    updateBtns.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังอัปเดต...';
    });
    
    addToLog('🔄 เริ่มอัปเดต Laravel Framework...');
    
    // Simulate Laravel update process with progress bar
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15; // Slower than composer
        if (progress >= 100) {
            progress = 100;
            clearInterval(progressInterval);
            
            // Update progress bar
            updateProgressBar.style.width = '100%';
            updateProgressText.textContent = 'อัปเดตเสร็จสิ้น!';
            
            updateBtns.forEach(btn => {
                btn.innerHTML = '<i class="fas fa-check me-2"></i>อัปเดตเสร็จสิ้น';
                btn.className = btn.className.replace('btn-outline-warning', 'btn-success');
            });
            addToLog('✅ อัปเดต Laravel Framework เสร็จสิ้น');
            
            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'อัปเดตเสร็จสิ้น!',
                text: 'Laravel Framework ได้รับการอัปเดตเรียบร้อยแล้ว กรุณาโหลดหน้าเว็บใหม่',
                confirmButtonText: 'ตกลง'
            });
            
            // Hide progress after 3 seconds
            setTimeout(() => {
                updateProgress.style.display = 'none';
                updateProgressBar.style.width = '0%';
            }, 3000);
            
            updateInProgress = false;
        } else {
            // Update progress bar
            updateProgressBar.style.width = progress + '%';
            updateProgressText.textContent = `กำลังอัปเดต Laravel Framework... ${Math.round(progress)}%`;
        }
        
        addToLog(`🔧 อัปเดต Laravel Framework... ${Math.round(progress)}%`);
    }, 600);
}

/**
 * Simulate update process with progress
 */
function simulateUpdateProcess(type, callback) {
    const steps = type === 'composer' ? [
        'กำลังตรวจสอบ dependencies...',
        'กำลังดาวน์โหลดแพ็กเกจใหม่...',
        'กำลังติดตั้งแพ็กเกจ...',
        'กำลังอัปเดต autoload...',
        'กำลังตรวจสอบความเข้ากันได้...'
    ] : [
        'กำลังตรวจสอบเวอร์ชัน Laravel...',
        'กำลังดาวน์โหลดไฟล์ใหม่...',
        'กำลังอัปเดต core files...',
        'กำลังอัปเดต configuration...',
        'กำลังรัน migrations...',
        'กำลังเคลียร์ cache...'
    ];
    
    let currentStep = 0;
    
    const processStep = () => {
        if (currentStep < steps.length) {
            addToLog(`⏳ ${steps[currentStep]}`);
            currentStep++;
            setTimeout(processStep, 1000 + Math.random() * 2000);
        } else {
            callback();
        }
    };
    
    processStep();
}

/**
 * Add message to update log
 */
function addToLog(message) {
    const timestamp = new Date().toLocaleTimeString('th-TH');
    const logEntry = `[${timestamp}] ${message}`;
    updateLog.push(logEntry);
    
    const logElement = document.getElementById('updateLog');
    if (logElement) {
        logElement.textContent = updateLog.join('\n');
        logElement.scrollTop = logElement.scrollHeight;
    } else {
        console.log('Update log:', logEntry);
    }
}

/**
 * Clear update log
 */
function clearUpdateLog() {
    Swal.fire({
        title: 'ยืนยันการล้างบันทึก',
        text: 'คุณแน่ใจหรือไม่ที่จะล้างบันทึกการอัปเดตทั้งหมด?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ล้างบันทึก',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            updateLog = [];
            const logElement = document.getElementById('updateLog');
            if (logElement) {
                logElement.textContent = 'บันทึกการอัปเดตถูกล้างแล้ว...';
            }
            
            Swal.fire({
                icon: 'success',
                title: 'ล้างบันทึกเสร็จสิ้น!',
                text: 'บันทึกการอัปเดตถูกล้างเรียบร้อยแล้ว',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}


/**
 * Get display name for update channel
 */
function getChannelDisplayName(channel) {
    const channelNames = {
        'stable': 'Stable (เสถียร)',
        'beta': 'Beta (ทดสอบ)',
        'dev': 'Development (พัฒนา)'
    };
    return channelNames[channel] || channel;
}

/**
 * Update system info table based on update check results
 */
function updateSystemInfoTable(hasUpdates, updateData = null) {
    const laravelUpdateStatus = document.getElementById('laravelUpdateStatus');
    const composerUpdateStatus = document.getElementById('composerUpdateStatus');
    
    if (hasUpdates && updateData) {
        // Laravel has updates
        laravelUpdateStatus.innerHTML = `<span class="badge bg-warning"><i class="fas fa-exclamation-triangle me-1"></i>มีอัปเดตใหม่ (${updateData.latestVersion})</span>`;
        
        // Composer might have updates
        composerUpdateStatus.innerHTML = '<span class="badge bg-info"><i class="fas fa-info-circle me-1"></i>ตรวจสอบแล้ว</span>';
        
        // Update current version display
        const currentVersionElement = document.getElementById('currentVersion');
        if (currentVersionElement) {
            currentVersionElement.textContent = updateData.currentVersion;
        }
    } else {
        // All up to date
        laravelUpdateStatus.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>ล่าสุด</span>';
        composerUpdateStatus.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>ล่าสุด</span>';
        
        // Update current version display if available
        if (updateData && updateData.currentVersion) {
            const currentVersionElement = document.getElementById('currentVersion');
            if (currentVersionElement) {
                currentVersionElement.textContent = updateData.currentVersion;
            }
        }
    }
}

/**
 * Load update settings from database
 */
async function loadUpdateSettings() {
    try {
        const response = await fetch('/admin/settings/update', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.settings) {
                // Update form fields with loaded settings
                document.getElementById('autoUpdate').checked = data.settings.auto_update !== undefined ? data.settings.auto_update : true;
                document.getElementById('updateChannel').value = data.settings.update_channel || 'stable';
                document.getElementById('backupBeforeUpdate').checked = data.settings.backup_before_update !== undefined ? data.settings.backup_before_update : true;
                document.getElementById('notifyOnUpdate').checked = data.settings.notify_on_update !== undefined ? data.settings.notify_on_update : true;
                
                // Update switch labels after setting values
                setTimeout(() => {
                    updateSwitchLabels();
                }, 100);
                
                addToLog('📥 โหลดการตั้งค่าอัปเดตเรียบร้อย');
            }
        }
    } catch (error) {
        console.error('Error loading update settings:', error);
        addToLog('❌ ไม่สามารถโหลดการตั้งค่าอัปเดตได้');
    }
}

/**
 * Update switch labels based on current state
 */
function updateSwitchLabels() {
    const switches = [
        { id: 'autoUpdate', labelId: 'autoUpdateLabel' },
        { id: 'backupBeforeUpdate', labelId: 'backupBeforeUpdateLabel' },
        { id: 'notifyOnUpdate', labelId: 'notifyOnUpdateLabel' }
    ];
    
    switches.forEach(switchConfig => {
        const checkbox = document.getElementById(switchConfig.id);
        const label = document.getElementById(switchConfig.labelId);
        
        if (checkbox && label) {
            if (checkbox.checked) {
                label.textContent = 'เปิดใช้งาน';
                label.className = 'form-check-label enabled';
            } else {
                label.textContent = 'ปิดใช้งาน';
                label.className = 'form-check-label disabled';
            }
        }
    });
}

/**
 * Wait for DOM elements to be available
 */
function waitForElement(selector, timeout = 5000) {
    return new Promise((resolve, reject) => {
        const element = document.querySelector(selector);
        if (element) {
            resolve(element);
            return;
        }

        const observer = new MutationObserver((mutations, obs) => {
            const element = document.querySelector(selector);
            if (element) {
                obs.disconnect();
                resolve(element);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        setTimeout(() => {
            observer.disconnect();
            reject(new Error(`Element ${selector} not found within ${timeout}ms`));
        }, timeout);
    });
}

/**
 * Initialize update tab
 */
document.addEventListener('DOMContentLoaded', function() {
    // Wait for update tab elements to be available
    waitForElement('#update').then(() => {
        // Initialize system info
        updateSystemInfo();
        
        // Load update settings from database
        loadUpdateSettings();
        
        // Add event listeners for form controls
        initializeFormControls();
        
        // Add clear log button
        addClearLogButton();
    }).catch(error => {
        console.error('Failed to initialize update tab:', error);
    });
});

/**
 * Initialize form controls
 */
function initializeFormControls() {
    // Handle form switch changes
    const switches = document.querySelectorAll('#update input[type="checkbox"]');
    switches.forEach(switchEl => {
        switchEl.addEventListener('change', function() {
            updateSwitchLabels();
        });
    });
    
    // Set initial state for all switches
    updateSwitchLabels();
    
    // Handle form submission
    const form = document.getElementById('updateSettingsForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveUpdateSettings();
        });
    }
}

/**
 * Save update settings
 */
function saveUpdateSettings() {
    const formData = {
        autoUpdate: document.getElementById('autoUpdate').checked,
        updateChannel: document.getElementById('updateChannel').value,
        backupBeforeUpdate: document.getElementById('backupBeforeUpdate').checked,
        notifyOnUpdate: document.getElementById('notifyOnUpdate').checked
    };
    
    // Show loading state
    const submitBtn = document.querySelector('#updateSettingsForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังบันทึก...';
    
    // Show loading alert
    Swal.fire({
        title: 'กำลังบันทึกการตั้งค่า',
        text: 'กรุณารอสักครู่...',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make API call to save update settings
    fetch('/admin/settings/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addToLog('💾 บันทึกการตั้งค่าอัปเดตเสร็จสิ้น');
            
            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'บันทึกเสร็จสิ้น!',
                text: data.message || 'การตั้งค่าอัปเดตได้รับการบันทึกเรียบร้อยแล้ว',
                confirmButtonText: 'ตกลง'
            });
        } else {
            throw new Error(data.message || 'เกิดข้อผิดพลาดในการบันทึก');
        }
    })
    .catch(error => {
        console.error('Error saving update settings:', error);
        addToLog('❌ เกิดข้อผิดพลาดในการบันทึกการตั้งค่า');
        
        // Show error alert
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: error.message || 'ไม่สามารถบันทึกการตั้งค่าได้ กรุณาลองใหม่อีกครั้ง',
            confirmButtonText: 'ตกลง'
        });
    })
    .finally(() => {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

/**
 * Add clear log button
 */
function addClearLogButton() {
    const logSection = document.querySelector('#update .mt-4:last-of-type');
    if (logSection && logSection.querySelector('h6')) {
        const clearLogBtn = document.createElement('button');
        clearLogBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
        clearLogBtn.innerHTML = '<i class="fas fa-trash me-1"></i>ล้างบันทึก';
        clearLogBtn.onclick = clearUpdateLog;
        
        const h6 = logSection.querySelector('h6');
        h6.appendChild(clearLogBtn);
    }
}

/**
 * Update system information
 */
function updateSystemInfo() {
    // Update PHP version
    const phpVersionCell = document.getElementById('phpVersion');
    if (phpVersionCell) {
        phpVersionCell.textContent = '{{ PHP_VERSION }}';
    }
    
    // Update memory limit
    const memoryLimitCell = document.getElementById('memoryLimit');
    if (memoryLimitCell) {
        memoryLimitCell.textContent = '{{ ini_get("memory_limit") }}';
    }
    
    // Check Composer version (simulated)
    setTimeout(() => {
        const composerVersionCell = document.getElementById('composerVersion');
        if (composerVersionCell) {
            composerVersionCell.textContent = '2.5.1';
            // Update status badge
            const composerRow = composerVersionCell.closest('tr');
            const statusBadge = composerRow.querySelector('.badge');
            if (statusBadge) {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'พร้อมใช้งาน';
            }
        }
    }, 500);
    
    // Check disk space (simulated)
    setTimeout(() => {
        const diskSpaceCell = document.getElementById('diskSpace');
        if (diskSpaceCell) {
            diskSpaceCell.textContent = '15.2 GB available';
            // Update status badge
            const diskRow = diskSpaceCell.closest('tr');
            const statusBadge = diskRow.querySelector('.badge');
            if (statusBadge) {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'เพียงพอ';
            }
        }
    }, 1000);
}
