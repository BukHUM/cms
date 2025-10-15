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
function checkForUpdates() {
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

    const checkBtn = document.querySelector('button[onclick="checkForUpdates()"]');
    const statusAlert = document.getElementById('updateStatusAlert');
    const statusText = document.getElementById('updateStatusText');
    
    // Show loading state
    checkBtn.disabled = true;
    checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังตรวจสอบ...';
    statusAlert.style.display = 'block';
    statusText.textContent = 'กำลังตรวจสอบการอัปเดต...';
    
    // Simulate API call to check for updates
    setTimeout(() => {
        // Mock response - in real implementation, this would be an AJAX call
        const hasUpdates = Math.random() > 0.5; // Random for demo
        
        if (hasUpdates) {
            statusAlert.className = 'alert alert-success';
            statusText.innerHTML = '<i class="fas fa-check-circle me-2"></i>พบการอัปเดตใหม่!';
            
            // Enable update buttons (both desktop and mobile)
            const composerBtns = document.querySelectorAll('#updateComposerBtn, #updateComposerBtnMobile');
            const laravelBtns = document.querySelectorAll('#updateLaravelBtn, #updateLaravelBtnMobile');
            
            composerBtns.forEach(btn => btn.disabled = false);
            laravelBtns.forEach(btn => btn.disabled = false);
            
            addToLog('✓ พบการอัปเดตใหม่');
            
            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'พบการอัปเดตใหม่!',
                text: 'มีเวอร์ชันใหม่ของ Laravel Framework พร้อมให้อัปเดต',
                confirmButtonText: 'ตกลง'
            });
        } else {
            statusAlert.className = 'alert alert-info';
            statusText.innerHTML = '<i class="fas fa-info-circle me-2"></i>ระบบของคุณเป็นเวอร์ชันล่าสุดแล้ว';
            addToLog('✓ ระบบเป็นเวอร์ชันล่าสุด');
            
            // Show info alert
            Swal.fire({
                icon: 'info',
                title: 'ระบบเป็นเวอร์ชันล่าสุด',
                text: 'ระบบของคุณเป็นเวอร์ชันล่าสุดแล้ว ไม่จำเป็นต้องอัปเดต',
                confirmButtonText: 'ตกลง'
            });
        }
        
        // Reset button
        checkBtn.disabled = false;
        checkBtn.innerHTML = '<i class="fas fa-search me-2"></i>ตรวจสอบการอัปเดต';
    }, 2000);
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
    
    // Show loading state for all composer buttons
    updateBtns.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังอัปเดต...';
    });
    
    addToLog('🔄 เริ่มอัปเดต Composer dependencies...');
    
    // Show progress alert
    Swal.fire({
        title: 'กำลังอัปเดต Composer',
        text: 'กรุณารอสักครู่...',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate Composer update process
    simulateUpdateProcess('composer', () => {
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
        
        updateInProgress = false;
    });
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
    
    // Show loading state for all laravel buttons
    updateBtns.forEach(btn => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังอัปเดต...';
    });
    
    addToLog('🔄 เริ่มอัปเดต Laravel Framework...');
    
    // Show progress alert
    Swal.fire({
        title: 'กำลังอัปเดต Laravel Framework',
        text: 'กรุณารอสักครู่...',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate Laravel update process
    simulateUpdateProcess('laravel', () => {
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
        
        updateInProgress = false;
    });
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
    logElement.textContent = updateLog.join('\n');
    logElement.scrollTop = logElement.scrollHeight;
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
            document.getElementById('updateLog').textContent = 'บันทึกการอัปเดตถูกล้างแล้ว...';
            
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
 * Test SweetAlert2 functionality
 */
function testSweetAlert(type) {
    const messages = {
        success: {
            title: 'สำเร็จ!',
            text: 'การดำเนินการเสร็จสิ้นเรียบร้อยแล้ว',
            icon: 'success'
        },
        warning: {
            title: 'คำเตือน!',
            text: 'กรุณาตรวจสอบข้อมูลก่อนดำเนินการต่อ',
            icon: 'warning'
        },
        info: {
            title: 'ข้อมูล',
            text: 'นี่คือข้อมูลที่คุณควรทราบ',
            icon: 'info'
        },
        error: {
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการดำเนินการ กรุณาลองใหม่อีกครั้ง',
            icon: 'error'
        }
    };
    
    const config = messages[type];
    if (config) {
        Swal.fire(config);
    }
}

/**
 * Initialize update tab
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize system info
    updateSystemInfo();
    
    // Add event listeners for form controls
    initializeFormControls();
    
    // Add clear log button
    addClearLogButton();
});

/**
 * Initialize form controls
 */
function initializeFormControls() {
    // Handle form switch changes
    const switches = document.querySelectorAll('#update input[type="checkbox"]');
    switches.forEach(switchEl => {
        switchEl.addEventListener('change', function() {
            const label = document.getElementById(this.id + 'Label');
            if (label) {
                label.classList.remove('enabled', 'disabled');
                label.classList.add(this.checked ? 'enabled' : 'disabled');
            }
        });
        
        // Set initial state
        const label = document.getElementById(switchEl.id + 'Label');
        if (label) {
            label.classList.remove('enabled', 'disabled');
            label.classList.add(switchEl.checked ? 'enabled' : 'disabled');
        }
    });
    
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
    
    // Simulate API call
    setTimeout(() => {
        addToLog('💾 บันทึกการตั้งค่าอัปเดตเสร็จสิ้น');
        
        // Show success alert
        Swal.fire({
            icon: 'success',
            title: 'บันทึกเสร็จสิ้น!',
            text: 'การตั้งค่าอัปเดตได้รับการบันทึกเรียบร้อยแล้ว',
            confirmButtonText: 'ตกลง'
        });
        
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 1500);
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
