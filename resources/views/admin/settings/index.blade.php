@extends('layouts.admin')

@section('title', 'ตั้งค่าระบบ')
@section('page-title', 'ตั้งค่าระบบ')
@section('page-subtitle', 'จัดการการตั้งค่าระบบและค่าพารามิเตอร์ต่างๆ')

@section('content')
<!-- Settings Navigation - Desktop -->
<div class="d-none d-md-block">
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
                <i class="fas fa-cog"></i>ทั่วไป
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button">
                <i class="fas fa-envelope"></i>อีเมล
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button">
                <i class="fas fa-shield-alt"></i>ความปลอดภัย
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button">
                <i class="fas fa-database"></i>สำรองข้อมูล
            </button>
        </li>
    </ul>
</div>

<!-- Settings Navigation - Mobile -->
<div class="d-md-none mb-4">
    <div class="settings-mobile-nav">
        <div class="current-tab-display" onclick="toggleSettingsDropdown()">
            <div class="tab-info">
                <i class="fas fa-cog" id="currentTabIcon"></i>
                <span id="currentTabText">ทั่วไป</span>
            </div>
            <i class="fas fa-chevron-down dropdown-arrow"></i>
        </div>
        
        <div class="settings-dropdown" id="settingsDropdown" style="display: none;">
            <div class="dropdown-item" onclick="switchTab('general', 'fas fa-cog', 'ทั่วไป')">
                <i class="fas fa-cog"></i>
                <span>ทั่วไป</span>
                <small>การตั้งค่าพื้นฐานของระบบ</small>
            </div>
            <div class="dropdown-item" onclick="switchTab('email', 'fas fa-envelope', 'อีเมล')">
                <i class="fas fa-envelope"></i>
                <span>อีเมล</span>
                <small>การตั้งค่าการส่งอีเมล</small>
            </div>
            <div class="dropdown-item" onclick="switchTab('security', 'fas fa-shield-alt', 'ความปลอดภัย')">
                <i class="fas fa-shield-alt"></i>
                <span>ความปลอดภัย</span>
                <small>การตั้งค่าความปลอดภัยระบบ</small>
            </div>
            <div class="dropdown-item" onclick="switchTab('backup', 'fas fa-database', 'สำรองข้อมูล')">
                <i class="fas fa-database"></i>
                <span>สำรองข้อมูล</span>
                <small>การตั้งค่าการสำรองข้อมูล</small>
            </div>
        </div>
    </div>
</div>

<div class="tab-content" id="settingsTabContent">
    <!-- General Settings -->
    <div class="tab-pane fade show active" id="general" role="tabpanel">
        <div class="settings-card">
            <div class="card-header">
                <h5>การตั้งค่าทั่วไป</h5>
            </div>
            <div class="card-body">
                <form id="generalSettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="siteName" class="form-label">ชื่อเว็บไซต์</label>
                            <input type="text" class="form-control" id="siteName" value="{{ config('app.name') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="siteUrl" class="form-label">URL เว็บไซต์</label>
                            <input type="url" class="form-control" id="siteUrl" value="{{ config('app.url') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="timezone" class="form-label">เขตเวลา</label>
                            <select class="form-select" id="timezone">
                                <option value="Asia/Bangkok" selected>Asia/Bangkok</option>
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">America/New_York</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="language" class="form-label">ภาษา</label>
                            <select class="form-select" id="language">
                                <option value="th" selected>ไทย</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="maintenanceMode" class="form-label">โหมดบำรุงรักษา</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                <label class="form-check-label" for="maintenanceMode">
                                    เปิดใช้งานโหมดบำรุงรักษา
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="debugMode" class="form-label">โหมด Debug</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="debugMode" checked>
                                <label class="form-check-label" for="debugMode">
                                    เปิดใช้งานโหมด Debug
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Settings -->
    <div class="tab-pane fade" id="email" role="tabpanel">
        <div class="settings-card">
            <div class="card-header">
                <h5>การตั้งค่าอีเมล</h5>
            </div>
            <div class="card-body">
                <form id="emailSettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="mailDriver" class="form-label">Mail Driver</label>
                            <select class="form-select" id="mailDriver">
                                <option value="smtp" selected>SMTP</option>
                                <option value="mailgun">Mailgun</option>
                                <option value="ses">Amazon SES</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="mailHost" class="form-label">Mail Host</label>
                            <input type="text" class="form-control" id="mailHost" value="smtp.gmail.com">
                        </div>
                        <div class="col-md-6">
                            <label for="mailPort" class="form-label">Mail Port</label>
                            <input type="number" class="form-control" id="mailPort" value="587">
                        </div>
                        <div class="col-md-6">
                            <label for="mailUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="mailUsername">
                        </div>
                        <div class="col-md-6">
                            <label for="mailPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="mailPassword">
                        </div>
                        <div class="col-md-6">
                            <label for="mailEncryption" class="form-label">Encryption</label>
                            <select class="form-select" id="mailEncryption">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="mailFromAddress" class="form-label">From Address</label>
                            <input type="email" class="form-control" id="mailFromAddress" value="noreply@example.com">
                        </div>
                        <div class="col-md-6">
                            <label for="mailFromName" class="form-label">From Name</label>
                            <input type="text" class="form-control" id="mailFromName" value="{{ config('app.name') }}">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-info me-2" onclick="testEmail()">
                            <i class="fas fa-paper-plane me-2"></i>
                            ทดสอบการส่งอีเมล
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="tab-pane fade" id="security" role="tabpanel">
        <div class="settings-card">
            <div class="card-header">
                <h5>การตั้งค่าความปลอดภัย</h5>
            </div>
            <div class="card-body">
                <form id="securitySettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sessionLifetime" class="form-label">ระยะเวลา Session (นาที)</label>
                            <input type="number" class="form-control" id="sessionLifetime" value="120">
                        </div>
                        <div class="col-md-6">
                            <label for="maxLoginAttempts" class="form-label">จำนวนครั้งการเข้าสู่ระบบสูงสุด</label>
                            <input type="number" class="form-control" id="maxLoginAttempts" value="5">
                        </div>
                        <div class="col-md-6">
                            <label for="passwordMinLength" class="form-label">ความยาวรหัสผ่านขั้นต่ำ</label>
                            <input type="number" class="form-control" id="passwordMinLength" value="8">
                        </div>
                        <div class="col-md-6">
                            <label for="requireSpecialChars" class="form-label">ต้องมีอักขระพิเศษ</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="requireSpecialChars" checked>
                                <label class="form-check-label" for="requireSpecialChars">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="twoFactorAuth" class="form-label">Two-Factor Authentication</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                <label class="form-check-label" for="twoFactorAuth">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="ipWhitelist" class="form-label">IP Whitelist</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ipWhitelist">
                                <label class="form-check-label" for="ipWhitelist">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Backup Settings -->
    <div class="tab-pane fade" id="backup" role="tabpanel">
        <div class="settings-card">
            <div class="card-header">
                <h5>การตั้งค่าสำรองข้อมูล</h5>
            </div>
            <div class="card-body">
                <form id="backupSettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="backupFrequency" class="form-label">ความถี่การสำรองข้อมูล</label>
                            <select class="form-select" id="backupFrequency">
                                <option value="daily" selected>รายวัน</option>
                                <option value="weekly">รายสัปดาห์</option>
                                <option value="monthly">รายเดือน</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="backupTime" class="form-label">เวลาสำรองข้อมูล</label>
                            <input type="time" class="form-control" id="backupTime" value="02:00">
                        </div>
                        <div class="col-md-6">
                            <label for="backupRetention" class="form-label">เก็บไฟล์สำรอง (วัน)</label>
                            <input type="number" class="form-control" id="backupRetention" value="30">
                        </div>
                        <div class="col-md-6">
                            <label for="backupLocation" class="form-label">ตำแหน่งเก็บไฟล์สำรอง</label>
                            <select class="form-select" id="backupLocation">
                                <option value="local" selected>Local Storage</option>
                                <option value="s3">Amazon S3</option>
                                <option value="google">Google Drive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-warning me-2" onclick="createBackup()">
                            <i class="fas fa-database me-2"></i>
                            สร้างสำรองข้อมูลทันที
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
                
                <!-- Backup History -->
                <div class="backup-history-table">
                    <h6>ประวัติการสำรองข้อมูล</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>ขนาดไฟล์</th>
                                    <th>สถานะ</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-01-25 02:00</td>
                                    <td>15.2 MB</td>
                                    <td><span class="badge bg-success">สำเร็จ</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">ดาวน์โหลด</button>
                                        <button class="btn btn-sm btn-outline-danger">ลบ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2024-01-24 02:00</td>
                                    <td>14.8 MB</td>
                                    <td><span class="badge bg-success">สำเร็จ</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">ดาวน์โหลด</button>
                                        <button class="btn btn-sm btn-outline-danger">ลบ</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Mobile Settings Navigation
function toggleSettingsDropdown() {
    const dropdown = document.getElementById('settingsDropdown');
    const arrow = document.querySelector('.dropdown-arrow');
    
    if (dropdown.style.display === 'none') {
        dropdown.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}

function switchTab(tabId, iconClass, tabText) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('show', 'active');
    });
    
    // Show selected tab pane
    document.getElementById(tabId).classList.add('show', 'active');
    
    // Update mobile navigation display
    document.getElementById('currentTabIcon').className = iconClass;
    document.getElementById('currentTabText').textContent = tabText;
    
    // Close dropdown
    document.getElementById('settingsDropdown').style.display = 'none';
    document.querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
    
    // Update desktop tabs if visible
    if (window.innerWidth >= 768) {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        document.getElementById(tabId + '-tab').classList.add('active');
    }
}

// Form submissions
document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Saving general settings...');
    SwalHelper.success('บันทึกการตั้งค่าทั่วไปเรียบร้อยแล้ว');
});

document.getElementById('emailSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Saving email settings...');
    SwalHelper.success('บันทึกการตั้งค่าอีเมลเรียบร้อยแล้ว');
});

document.getElementById('securitySettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Saving security settings...');
    SwalHelper.success('บันทึกการตั้งค่าความปลอดภัยเรียบร้อยแล้ว');
});

document.getElementById('backupSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Saving backup settings...');
    SwalHelper.success('บันทึกการตั้งค่าสำรองข้อมูลเรียบร้อยแล้ว');
});

// Test email function
function testEmail() {
    console.log('Testing email configuration...');
    SwalHelper.loading('กำลังทดสอบการส่งอีเมล...');
    
    // Simulate email test
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ทดสอบการส่งอีเมลสำเร็จ!');
    }, 2000);
}

// Create backup function
function createBackup() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะสร้างสำรองข้อมูลตอนนี้?', 'ยืนยันการสร้างสำรองข้อมูล', function() {
        SwalHelper.loading('กำลังสร้างสำรองข้อมูล...');
        
        // Simulate backup creation
        setTimeout(() => {
            SwalHelper.close();
            SwalHelper.success('สร้างสำรองข้อมูลสำเร็จ!');
        }, 3000);
    });
}

// Initialize mobile functionality
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endpush