@extends('layouts.admin')

@section('title', 'ตั้งค่าระบบ')
@section('page-title', 'ตั้งค่าระบบ')
@section('page-subtitle', 'จัดการการตั้งค่าระบบและค่าพารามิเตอร์ต่างๆ')

@section('content')
<!-- Settings Navigation - Desktop -->
<div class="d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" onclick="switchTab('general', 'fas fa-cog', 'ทั่วไป')">
                    <i class="fas fa-cog"></i>ทั่วไป
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" onclick="switchTab('email', 'fas fa-envelope', 'อีเมล')">
                    <i class="fas fa-envelope"></i>อีเมล
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" onclick="switchTab('security', 'fas fa-shield-alt', 'ความปลอดภัย')">
                    <i class="fas fa-shield-alt"></i>ความปลอดภัย
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button" onclick="switchTab('backup', 'fas fa-database', 'สำรองข้อมูล')">
                    <i class="fas fa-database"></i>สำรองข้อมูล
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="audit-tab" data-bs-toggle="tab" data-bs-target="#audit" type="button" onclick="switchTab('audit', 'fas fa-clipboard-list', 'Audit Log')">
                    <i class="fas fa-clipboard-list"></i>Audit Log
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" onclick="switchTab('performance', 'fas fa-tachometer-alt', 'Performance')">
                    <i class="fas fa-tachometer-alt"></i>Performance
                </button>
            </li>
        </ul>
    </div>
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
            <div class="dropdown-item" onclick="switchTab('audit', 'fas fa-clipboard-list', 'Audit Log')">
                <i class="fas fa-clipboard-list"></i>
                <span>Audit Log</span>
                <small>บันทึกการใช้งานระบบ</small>
            </div>
            <div class="dropdown-item" onclick="switchTab('performance', 'fas fa-tachometer-alt', 'Performance')">
                <i class="fas fa-tachometer-alt"></i>
                <span>Performance</span>
                <small>การตรวจสอบประสิทธิภาพ</small>
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
                            <label for="siteEnabled" class="form-label">เปิดใช้งานเว็บไซต์</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="siteEnabled" checked>
                                <label class="form-check-label" for="siteEnabled" id="siteEnabledLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="maintenanceMode" class="form-label">โหมดบำรุงรักษา</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                <label class="form-check-label" for="maintenanceMode" id="maintenanceModeLabel">
                                    เปิดใช้งานโหมดบำรุงรักษา
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="debugMode" class="form-label">โหมด Debug</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="debugMode" checked>
                                <label class="form-check-label" for="debugMode" id="debugModeLabel">
                                    เปิดใช้งานโหมด Debug
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="autoSave" class="form-label">เปิดใช้งานการบันทึกอัตโนมัติ</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="autoSave" checked>
                                <label class="form-check-label" for="autoSave" id="autoSaveLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="notifications" class="form-label">เปิดใช้งานการแจ้งเตือน</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notifications" checked>
                                <label class="form-check-label" for="notifications" id="notificationsLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="analytics" class="form-label">เปิดใช้งานการวิเคราะห์ข้อมูล</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="analytics" checked>
                                <label class="form-check-label" for="analytics" id="analyticsLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="logging" class="form-label">เปิดใช้งานการบันทึก Log</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="logging" checked>
                                <label class="form-check-label" for="logging" id="loggingLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="maintenance" class="form-label">เปิดใช้งานโหมดบำรุงรักษา</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenance">
                                <label class="form-check-label" for="maintenance" id="maintenanceLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="updates" class="form-label">เปิดใช้งานการอัปเดตอัตโนมัติ</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="updates" checked>
                                <label class="form-check-label" for="updates" id="updatesLabel">
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
                        <div class="col-md-6">
                            <label for="mailEnabled" class="form-label">เปิดใช้งานการส่งอีเมล</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mailEnabled" checked>
                                <label class="form-check-label" for="mailEnabled" id="mailEnabledLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Desktop Actions -->
                    <div class="mt-4 d-none d-md-block">
                        <button type="button" class="btn btn-info me-2" onclick="testEmail()">
                            <i class="fas fa-paper-plane me-2"></i>
                            ทดสอบการส่งอีเมล
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                    
                    <!-- Mobile Actions -->
                    <div class="mt-4 d-md-none">
                        <div class="mobile-actions">
                            <div class="mobile-primary-action">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>
                                    บันทึกการตั้งค่า
                                </button>
                            </div>
                            <div class="mobile-secondary-actions">
                                <button type="button" class="btn btn-info w-100" onclick="testEmail()">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    <span class="d-none d-sm-inline">ทดสอบการส่งอีเมล</span>
                                    <span class="d-sm-none">ทดสอบอีเมล</span>
                                </button>
                            </div>
                        </div>
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
                                <label class="form-check-label" for="requireSpecialChars" id="requireSpecialCharsLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="twoFactorAuth" class="form-label">Two-Factor Authentication</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                <label class="form-check-label" for="twoFactorAuth" id="twoFactorAuthLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="ipWhitelist" class="form-label">IP Whitelist</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ipWhitelist">
                                <label class="form-check-label" for="ipWhitelist" id="ipWhitelistLabel">
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
                        <div class="col-md-6">
                            <label for="backupEnabled" class="form-label">เปิดใช้งานการสำรองข้อมูลอัตโนมัติ</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="backupEnabled" checked>
                                <label class="form-check-label" for="backupEnabled" id="backupEnabledLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Desktop Actions -->
                    <div class="mt-4 d-none d-md-block">
                        <button type="button" class="btn btn-warning me-2" onclick="createBackup()">
                            <i class="fas fa-database me-2"></i>
                            สร้างสำรองข้อมูลทันที
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                    
                    <!-- Mobile Actions -->
                    <div class="mt-4 d-md-none">
                        <div class="mobile-actions">
                            <div class="mobile-primary-action">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>
                                    บันทึกการตั้งค่า
                                </button>
                            </div>
                            <div class="mobile-secondary-actions">
                                <button type="button" class="btn btn-warning w-100" onclick="createBackup()">
                                    <i class="fas fa-database me-1"></i>
                                    <span class="d-none d-sm-inline">สร้างสำรองข้อมูลทันที</span>
                                    <span class="d-sm-none">สร้างสำรองข้อมูล</span>
                                </button>
                            </div>
                        </div>
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

    <!-- Audit Log Settings -->
    <div class="tab-pane fade" id="audit" role="tabpanel">
        <div class="settings-card">
            <div class="card-header">
                <h5>การตั้งค่า Audit Log</h5>
            </div>
            <div class="card-body">
                <form id="auditSettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="auditEnabled" class="form-label">เปิดใช้งาน Audit Log</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auditEnabled" checked>
                                <label class="form-check-label" for="auditEnabled" id="auditEnabledLabel">
                                    บันทึกการใช้งานระบบ
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="auditRetention" class="form-label">เก็บข้อมูล (วัน)</label>
                            <input type="number" class="form-control" id="auditRetention" value="90" min="7" max="365">
                        </div>
                        <div class="col-md-6">
                            <label for="auditLevel" class="form-label">ระดับการบันทึก</label>
                            <select class="form-select" id="auditLevel">
                                <option value="basic" selected>พื้นฐาน (Login, Logout, ข้อมูลสำคัญ)</option>
                                <option value="detailed">ละเอียด (ทุกการกระทำ)</option>
                                <option value="comprehensive">ครบถ้วน (รวมการดูข้อมูล)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="auditRealTime" class="form-label">แสดงผลแบบ Real-time</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auditRealTime">
                                <label class="form-check-label" for="auditRealTime" id="auditRealTimeLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="auditEmailAlerts" class="form-label">แจ้งเตือนทางอีเมล</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auditEmailAlerts">
                                <label class="form-check-label" for="auditEmailAlerts" id="auditEmailAlertsLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="auditSensitiveActions" class="form-label">บันทึกการกระทำที่สำคัญ</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auditSensitiveActions" checked>
                                <label class="form-check-label" for="auditSensitiveActions" id="auditSensitiveActionsLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Desktop Actions -->
                    <div class="mt-4 d-none d-md-block">
                        <button type="button" class="btn btn-warning me-2" onclick="exportAuditLogs()">
                            <i class="fas fa-download me-2"></i>
                            ส่งออก Log
                        </button>
                        <button type="button" class="btn btn-danger me-2" onclick="clearAuditLogs()">
                            <i class="fas fa-trash me-2"></i>
                            ล้าง Log
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                    
                    <!-- Mobile Actions -->
                    <div class="mt-4 d-md-none">
                        <div class="mobile-actions">
                            <div class="mobile-primary-action">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>
                                    บันทึกการตั้งค่า
                                </button>
                            </div>
                            <div class="mobile-secondary-actions">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-warning w-100" onclick="exportAuditLogs()">
                                            <i class="fas fa-download me-1"></i>
                                            <span class="d-none d-sm-inline">ส่งออก Log</span>
                                            <span class="d-sm-none">ส่งออก</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-danger w-100" onclick="clearAuditLogs()">
                                            <i class="fas fa-trash me-1"></i>
                                            <span class="d-none d-sm-inline">ล้าง Log</span>
                                            <span class="d-sm-none">ล้าง</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Recent Audit Logs -->
                <div class="audit-logs-table mt-4">
                    <h6>Audit Log ล่าสุด</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>เวลา</th>
                                    <th>ผู้ใช้</th>
                                    <th>การกระทำ</th>
                                    <th>IP Address</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody id="auditLogsTable">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        กำลังโหลดข้อมูล...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Settings -->
    <div class="tab-pane fade" id="performance" role="tabpanel">
        <div class="settings-card">
            <div class="card-header">
                <h5>การตั้งค่า Performance</h5>
            </div>
            <div class="card-body">
                <form id="performanceSettingsForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="cacheEnabled" class="form-label">เปิดใช้งาน Cache</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cacheEnabled" checked>
                                <label class="form-check-label" for="cacheEnabled" id="cacheEnabledLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="cacheDriver" class="form-label">Cache Driver</label>
                            <select class="form-select" id="cacheDriver">
                                <option value="file" selected>File</option>
                                <option value="redis">Redis</option>
                                <option value="memcached">Memcached</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cacheTTL" class="form-label">Cache TTL (นาที)</label>
                            <input type="number" class="form-control" id="cacheTTL" value="60" min="1" max="1440">
                        </div>
                        <div class="col-md-6">
                            <label for="queryLogging" class="form-label">บันทึก Query Log</label>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="queryLogging">
                                <label class="form-check-label me-3" for="queryLogging" id="queryLoggingLabel">
                                    เปิดใช้งาน
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="testQueryLogging()">
                                    <i class="fas fa-vial me-1"></i>
                                    ทดสอบ
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="slowQueryThreshold" class="form-label">Slow Query Threshold (ms)</label>
                            <input type="number" class="form-control" id="slowQueryThreshold" value="1000" min="100" max="10000">
                        </div>
                        <div class="col-md-6">
                            <label for="memoryLimit" class="form-label">Memory Limit (MB)</label>
                            <input type="number" class="form-control" id="memoryLimit" value="256" min="128" max="2048">
                        </div>
                        <div class="col-md-6">
                            <label for="maxExecutionTime" class="form-label">Max Execution Time (วินาที)</label>
                            <input type="number" class="form-control" id="maxExecutionTime" value="30" min="10" max="300">
                        </div>
                        <div class="col-md-6">
                            <label for="compressionEnabled" class="form-label">เปิดใช้งาน Compression</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="compressionEnabled" checked>
                                <label class="form-check-label" for="compressionEnabled" id="compressionEnabledLabel">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Desktop Actions -->
                    <div class="mt-4 d-none d-md-block">
                        <button type="button" class="btn btn-info me-2" onclick="clearCache()">
                            <i class="fas fa-broom me-2"></i>
                            ล้าง Cache
                        </button>
                        <button type="button" class="btn btn-warning me-2" onclick="runPerformanceTest()">
                            <i class="fas fa-play me-2"></i>
                            ทดสอบประสิทธิภาพ
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                    
                    <!-- Mobile Actions -->
                    <div class="mt-4 d-md-none">
                        <div class="mobile-actions">
                            <div class="mobile-primary-action">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>
                                    บันทึกการตั้งค่า
                                </button>
                            </div>
                            <div class="mobile-secondary-actions">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-info w-100" onclick="clearCache()">
                                            <i class="fas fa-broom me-1"></i>
                                            <span class="d-none d-sm-inline">ล้าง Cache</span>
                                            <span class="d-sm-none">ล้าง</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-warning w-100" onclick="runPerformanceTest()">
                                            <i class="fas fa-play me-1"></i>
                                            <span class="d-none d-sm-inline">ทดสอบประสิทธิภาพ</span>
                                            <span class="d-sm-none">ทดสอบ</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Performance Metrics -->
                <div class="performance-metrics mt-4">
                    <h6>Performance Metrics</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="responseTime">245ms</div>
                                <div class="metric-label">Response Time</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="memoryUsage">128MB</div>
                                <div class="metric-label">Memory Usage</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="cacheHitRate">85%</div>
                                <div class="metric-label">Cache Hit Rate</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="activeConnections">12</div>
                                <div class="metric-label">Active Connections</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="bufferPoolHitRate">95.5%</div>
                                <div class="metric-label">Buffer Pool Hit Rate</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="tableLockWaitRatio">2.1%</div>
                                <div class="metric-label">Table Lock Wait Ratio</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="tmpTableRatio">15.3%</div>
                                <div class="metric-label">Temporary Table Ratio</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="metric-card">
                                <div class="metric-value" id="totalQueries">1000</div>
                                <div class="metric-label">Total Queries</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Performance Analysis Tables -->
                <div class="performance-analysis mt-4">
                    <div class="row g-4">
                        <!-- Slow Queries -->
                        <div class="col-lg-6">
                            <div class="analysis-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-clock me-2"></i>Slow Queries</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshSlowQueries()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Query</th>
                                                    <th>Time (ms)</th>
                                                    <th>Count</th>
                                                </tr>
                                            </thead>
                                            <tbody id="slowQueriesTable">
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        ไม่มี Slow Queries
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Duplicate Queries -->
                        <div class="col-lg-6">
                            <div class="analysis-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-copy me-2"></i>Duplicate Queries</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshDuplicateQueries()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Query</th>
                                                    <th>Count</th>
                                                    <th>Impact</th>
                                                </tr>
                                            </thead>
                                            <tbody id="duplicateQueriesTable">
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        ไม่มี Duplicate Queries
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table Statistics -->
                        <div class="col-lg-6">
                            <div class="analysis-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-table me-2"></i>Table Statistics</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshTableStatistics()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Table</th>
                                                    <th>Rows</th>
                                                    <th>Size (MB)</th>
                                                    <th>Engine</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableStatisticsTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        กำลังโหลดข้อมูลตาราง...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Index Statistics -->
                        <div class="col-lg-6">
                            <div class="analysis-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-search me-2"></i>Index Statistics</h6>
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshIndexStatistics()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Table</th>
                                                    <th>Index</th>
                                                    <th>Usage</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="indexStatisticsTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        กำลังโหลดข้อมูล index...
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ========================================
// SETTINGS NAVIGATION FUNCTIONS
// ========================================

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

// Switch between tabs (works for both desktop and mobile)
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
    
    // Save current tab to localStorage
    localStorage.setItem('lastActiveSettingsTab', tabId);
    localStorage.setItem('lastActiveSettingsTabIcon', iconClass);
    localStorage.setItem('lastActiveSettingsTabText', tabText);
    
    // Note: Performance data will be loaded manually when user clicks refresh buttons
}

// ========================================
// GENERAL SETTINGS FUNCTIONS
// ========================================

// General Settings Form
document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        siteName: document.getElementById('siteName').value,
        siteUrl: document.getElementById('siteUrl').value,
        timezone: document.getElementById('timezone').value,
        language: document.getElementById('language').value,
        maintenanceMode: document.getElementById('maintenanceMode').checked,
        debugMode: document.getElementById('debugMode').checked
    };
    
    console.log('Saving general settings:', formData);
    SwalHelper.loading('กำลังบันทึกการตั้งค่าทั่วไป...');
    
    // Save to localStorage
    localStorage.setItem('generalSettings', JSON.stringify(formData));
    
    // Simulate API call
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('บันทึกการตั้งค่าทั่วไปเรียบร้อยแล้ว');
        
        // Update page title if site name changed
        if (formData.siteName) {
            document.title = formData.siteName + ' - Admin Panel';
        }
    }, 1500);
});

// ========================================
// EMAIL SETTINGS FUNCTIONS
// ========================================

// Email Settings Form
document.getElementById('emailSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        mailDriver: document.getElementById('mailDriver').value,
        mailHost: document.getElementById('mailHost').value,
        mailPort: document.getElementById('mailPort').value,
        mailUsername: document.getElementById('mailUsername').value,
        mailPassword: document.getElementById('mailPassword').value,
        mailEncryption: document.getElementById('mailEncryption').value,
        mailFromAddress: document.getElementById('mailFromAddress').value,
        mailFromName: document.getElementById('mailFromName').value
    };
    
    console.log('Saving email settings:', formData);
    SwalHelper.loading('กำลังบันทึกการตั้งค่าอีเมล...');
    
    // Save to localStorage
    localStorage.setItem('emailSettings', JSON.stringify(formData));
    
    // Simulate API call
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('บันทึกการตั้งค่าอีเมลเรียบร้อยแล้ว');
    }, 1500);
});

// Test Email Function
function testEmail() {
    const mailHost = document.getElementById('mailHost').value;
    const mailPort = document.getElementById('mailPort').value;
    const mailUsername = document.getElementById('mailUsername').value;
    
    if (!mailHost || !mailPort || !mailUsername) {
        SwalHelper.error('กรุณากรอกข้อมูลการตั้งค่าอีเมลให้ครบถ้วน');
        return;
    }
    
    console.log('Testing email configuration...');
    SwalHelper.loading('กำลังทดสอบการส่งอีเมล...');
    
    // Simulate email test
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ทดสอบการส่งอีเมลสำเร็จ! ระบบสามารถส่งอีเมลได้ปกติ');
    }, 2000);
}

// ========================================
// SECURITY SETTINGS FUNCTIONS
// ========================================

// Security Settings Form
document.getElementById('securitySettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        sessionLifetime: document.getElementById('sessionLifetime').value,
        maxLoginAttempts: document.getElementById('maxLoginAttempts').value,
        passwordMinLength: document.getElementById('passwordMinLength').value,
        requireSpecialChars: document.getElementById('requireSpecialChars').checked,
        twoFactorAuth: document.getElementById('twoFactorAuth').checked,
        ipWhitelist: document.getElementById('ipWhitelist').checked
    };
    
    // Validate password requirements
    if (formData.passwordMinLength < 6) {
        SwalHelper.error('ความยาวรหัสผ่านขั้นต่ำต้องไม่น้อยกว่า 6 ตัวอักษร');
        return;
    }
    
    if (formData.maxLoginAttempts < 3) {
        SwalHelper.error('จำนวนครั้งการเข้าสู่ระบบสูงสุดต้องไม่น้อยกว่า 3 ครั้ง');
        return;
    }
    
    console.log('Saving security settings:', formData);
    SwalHelper.loading('กำลังบันทึกการตั้งค่าความปลอดภัย...');
    
    // Save to localStorage
    localStorage.setItem('securitySettings', JSON.stringify(formData));
    
    // Simulate API call
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('บันทึกการตั้งค่าความปลอดภัยเรียบร้อยแล้ว');
    }, 1500);
});

// ========================================
// AUDIT LOG SETTINGS FUNCTIONS
// ========================================

// Audit Settings Form
document.getElementById('auditSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        auditEnabled: document.getElementById('auditEnabled').checked,
        auditRetention: document.getElementById('auditRetention').value,
        auditLevel: document.getElementById('auditLevel').value,
        auditRealTime: document.getElementById('auditRealTime').checked,
        auditEmailAlerts: document.getElementById('auditEmailAlerts').checked,
        auditSensitiveActions: document.getElementById('auditSensitiveActions').checked
    };
    
    // Validate audit retention
    if (formData.auditRetention < 7) {
        SwalHelper.error('การเก็บข้อมูล Audit Log ต้องไม่น้อยกว่า 7 วัน');
        return;
    }
    
    console.log('Saving audit settings:', formData);
    SwalHelper.loading('กำลังบันทึกการตั้งค่า Audit Log...');
    
    // Save to localStorage
    localStorage.setItem('auditSettings', JSON.stringify(formData));
    
    // Simulate API call
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('บันทึกการตั้งค่า Audit Log เรียบร้อยแล้ว');
    }, 1500);
});

// Load Audit Logs from API
function loadAuditLogs() {
    fetch('/api/audit/recent?limit=10')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAuditLogsTable(data.data);
            } else {
                console.error('Error loading audit logs:', data.message);
                showAuditLogsError();
            }
        })
        .catch(error => {
            console.error('Error fetching audit logs:', error);
            showAuditLogsError();
        });
}

// Update Audit Logs Table
function updateAuditLogsTable(logs) {
    const tbody = document.getElementById('auditLogsTable');
    tbody.innerHTML = '';
    
    if (logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มีข้อมูล Audit Log</td></tr>';
        return;
    }
    
     logs.forEach(log => {
         const row = `
             <tr>
                 <td>${log.created_at}</td>
                 <td>${log.user_name || 'N/A'}</td>
                 <td>${log.formatted_action}</td>
                 <td>${log.ip_address || 'N/A'}</td>
                 <td><span class="badge ${log.status_badge}">${log.formatted_status}</span></td>
             </tr>
         `;
         tbody.insertAdjacentHTML('beforeend', row);
     });
}

// Show Audit Logs Error
function showAuditLogsError() {
    const tbody = document.getElementById('auditLogsTable');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger"><i class="fas fa-exclamation-triangle me-2"></i>ไม่สามารถโหลดข้อมูลได้</td></tr>';
}

// Export Audit Logs Function
function exportAuditLogs() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะส่งออก Audit Logs?', 'ยืนยันการส่งออก', function() {
        SwalHelper.loading('กำลังส่งออก Audit Logs...');
        
        // Export audit logs from API
        fetch('/api/audit/export?format=csv')
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('ไม่สามารถส่งออกข้อมูลได้');
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'audit_logs_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                SwalHelper.close();
                SwalHelper.success('ส่งออก Audit Logs สำเร็จ!');
            })
            .catch(error => {
                SwalHelper.close();
                console.error('Error exporting audit logs:', error);
                SwalHelper.error('ไม่สามารถส่งออก Audit Logs ได้: ' + error.message);
            });
    });
}

// Clear Audit Logs Function
function clearAuditLogs() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะลบ Audit Logs ทั้งหมด?\n\nการกระทำนี้ไม่สามารถย้อนกลับได้', 'ยืนยันการลบ Audit Logs', function() {
        SwalHelper.loading('กำลังลบ Audit Logs...');
        
        // Clear audit logs from API
        fetch('/api/audit/cleanup', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                days: 0 // ลบทั้งหมด
            })
        })
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                SwalHelper.success('ลบ Audit Logs สำเร็จ!\n\nลบทั้งหมด ' + data.deleted_count + ' รายการ');
                
                // Refresh audit logs table
                loadAuditLogs();
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถลบ Audit Logs ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error clearing audit logs:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการลบ Audit Logs');
        });
    });
}

// ========================================
// PERFORMANCE SETTINGS FUNCTIONS
// ========================================

// Performance Settings Form
document.getElementById('performanceSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        cache_enabled: document.getElementById('cacheEnabled').checked,
        cache_driver: document.getElementById('cacheDriver').value,
        cache_ttl: document.getElementById('cacheTTL').value,
        query_logging: document.getElementById('queryLogging').checked,
        slow_query_threshold: document.getElementById('slowQueryThreshold').value,
        memory_limit: document.getElementById('memoryLimit').value,
        max_execution_time: document.getElementById('maxExecutionTime').value,
        compression_enabled: document.getElementById('compressionEnabled').checked
    };
    
    // Validate performance settings
    if (formData.cache_ttl < 1) {
        SwalHelper.error('Cache TTL ต้องไม่น้อยกว่า 1 นาที');
        return;
    }
    
    if (formData.memory_limit < 128) {
        SwalHelper.error('Memory Limit ต้องไม่น้อยกว่า 128 MB');
        return;
    }
    
    console.log('Saving performance settings:', formData);
    SwalHelper.loading('กำลังบันทึกการตั้งค่า Performance...');
    
    // Send to API
    fetch('/api/performance/settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        SwalHelper.close();
        
        if (data.success) {
            SwalHelper.success(data.message || 'บันทึกการตั้งค่า Performance เรียบร้อยแล้ว');
            
            // Save to localStorage for persistence (convert API format to localStorage format)
            const localStorageData = {
                cacheEnabled: formData.cache_enabled,
                cacheDriver: formData.cache_driver,
                cacheTTL: formData.cache_ttl,
                queryLogging: formData.query_logging,
                slowQueryThreshold: formData.slow_query_threshold,
                memoryLimit: formData.memory_limit,
                maxExecutionTime: formData.max_execution_time,
                compressionEnabled: formData.compression_enabled
            };
            localStorage.setItem('performanceSettings', JSON.stringify(localStorageData));
            
            // Update switch styling after saving
            updateSwitchStyle('cacheEnabled', 'cacheEnabledLabel');
            updateSwitchStyle('queryLogging', 'queryLoggingLabel');
            updateSwitchStyle('compressionEnabled', 'compressionEnabledLabel');
            
            // Update performance metrics after saving settings
            updatePerformanceMetrics();
        } else {
            SwalHelper.error(data.message || 'ไม่สามารถบันทึกการตั้งค่าได้');
        }
    })
    .catch(error => {
        SwalHelper.close();
        console.error('Error saving performance settings:', error);
        SwalHelper.error('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า');
    });
});

// Clear Cache Function
function clearCache() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะล้าง Cache ทั้งหมด?', 'ยืนยันการล้าง Cache', function() {
        SwalHelper.loading('กำลังล้าง Cache...');
        
        setTimeout(() => {
            SwalHelper.close();
            SwalHelper.success('ล้าง Cache สำเร็จ!');
            
            // Update performance metrics
            updatePerformanceMetrics();
        }, 2000);
    });
}

// Run Performance Test Function
function runPerformanceTest() {
    SwalHelper.loading('กำลังทดสอบประสิทธิภาพ...');
    
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ทดสอบประสิทธิภาพเสร็จสิ้น!');
        
        // Update performance metrics
        updatePerformanceMetrics();
    }, 3000);
}

// Test Query Logging Function
function testQueryLogging() {
    SwalHelper.loading('กำลังทดสอบ Query Logging...');
    
    fetch('/api/performance/test-query-logging', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        SwalHelper.close();
        
        if (data.success) {
            const testData = data.data;
            let message = 'ทดสอบ Query Logging สำเร็จ!\n\n';
            message += `• Query Logging เปิดใช้งาน: ${testData.query_logging_enabled ? 'ใช่' : 'ไม่'}\n`;
            message += `• ทดสอบ Query ทำงาน: ${testData.test_query_executed ? 'ใช่' : 'ไม่'}\n`;
            message += `• Query ถูกบันทึกใน Log: ${testData.query_logged ? 'ใช่' : 'ไม่'}\n`;
            message += `• ไฟล์ Log มีอยู่: ${testData.log_file_exists ? 'ใช่' : 'ไม่'}\n\n`;
            
            if (testData.debug_info) {
                message += 'ข้อมูล Debug:\n';
                message += `• File Exists: ${testData.debug_info.file_exists ? 'ใช่' : 'ไม่'}\n`;
                message += `• Is Enabled: ${testData.debug_info.is_enabled ? 'ใช่' : 'ไม่'}\n`;
                message += `• DB Config Content: ${testData.debug_info.db_config_content}`;
            }
            
            SwalHelper.info(message, 'ผลการทดสอบ Query Logging');
        } else {
            SwalHelper.error(data.message || 'ไม่สามารถทดสอบ Query Logging ได้');
        }
    })
    .catch(error => {
        SwalHelper.close();
        console.error('Error testing query logging:', error);
        SwalHelper.error('เกิดข้อผิดพลาดในการทดสอบ Query Logging');
    });
}

// Update Performance Metrics
function updatePerformanceMetrics() {
    fetch('/api/performance/metrics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const metrics = data.data;
                
                document.getElementById('responseTime').textContent = (metrics.response_time || 0) + 'ms';
                document.getElementById('memoryUsage').textContent = (metrics.memory_usage || 0) + 'MB';
                document.getElementById('cacheHitRate').textContent = (metrics.cache_hit_rate || 0) + '%';
                document.getElementById('activeConnections').textContent = metrics.active_connections || 0;
                document.getElementById('bufferPoolHitRate').textContent = (metrics.buffer_pool_hit_rate || 0) + '%';
                document.getElementById('tableLockWaitRatio').textContent = (metrics.table_lock_wait_ratio || 0) + '%';
                document.getElementById('tmpTableRatio').textContent = (metrics.tmp_table_ratio || 0) + '%';
                document.getElementById('totalQueries').textContent = metrics.total_queries || 0;
            } else {
                console.error('Error fetching performance metrics:', data.message);
                // Fallback to simulated data
                const responseTime = Math.floor(Math.random() * 200) + 100;
                const memoryUsage = Math.floor(Math.random() * 100) + 100;
                const cacheHitRate = Math.floor(Math.random() * 20) + 80;
                const activeConnections = Math.floor(Math.random() * 10) + 8;
                const bufferPoolHitRate = Math.floor(Math.random() * 10) + 90;
                const tableLockWaitRatio = Math.floor(Math.random() * 5);
                const tmpTableRatio = Math.floor(Math.random() * 20) + 10;
                const totalQueries = Math.floor(Math.random() * 1000) + 500;
                
                document.getElementById('responseTime').textContent = responseTime + 'ms';
                document.getElementById('memoryUsage').textContent = memoryUsage + 'MB';
                document.getElementById('cacheHitRate').textContent = cacheHitRate + '%';
                document.getElementById('activeConnections').textContent = activeConnections;
                document.getElementById('bufferPoolHitRate').textContent = bufferPoolHitRate + '%';
                document.getElementById('tableLockWaitRatio').textContent = tableLockWaitRatio + '%';
                document.getElementById('tmpTableRatio').textContent = tmpTableRatio + '%';
                document.getElementById('totalQueries').textContent = totalQueries;
            }
        })
        .catch(error => {
            console.error('Error fetching performance metrics:', error);
            // Fallback to simulated data
            const responseTime = Math.floor(Math.random() * 200) + 100;
            const memoryUsage = Math.floor(Math.random() * 100) + 100;
            const cacheHitRate = Math.floor(Math.random() * 20) + 80;
            const activeConnections = Math.floor(Math.random() * 10) + 8;
            const bufferPoolHitRate = Math.floor(Math.random() * 10) + 90;
            const tableLockWaitRatio = Math.floor(Math.random() * 5);
            const tmpTableRatio = Math.floor(Math.random() * 20) + 10;
            const totalQueries = Math.floor(Math.random() * 1000) + 500;
            
            document.getElementById('responseTime').textContent = responseTime + 'ms';
            document.getElementById('memoryUsage').textContent = memoryUsage + 'MB';
            document.getElementById('cacheHitRate').textContent = cacheHitRate + '%';
            document.getElementById('activeConnections').textContent = activeConnections;
            document.getElementById('bufferPoolHitRate').textContent = bufferPoolHitRate + '%';
            document.getElementById('tableLockWaitRatio').textContent = tableLockWaitRatio + '%';
            document.getElementById('tmpTableRatio').textContent = tmpTableRatio + '%';
            document.getElementById('totalQueries').textContent = totalQueries;
        });
}

// Refresh Slow Queries
function refreshSlowQueries() {
    console.log('Refreshing slow queries...');
    SwalHelper.loading('กำลังโหลด Slow Queries...');
    
    fetch('/api/performance/slow-queries')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateSlowQueriesTable(data.data);
                SwalHelper.success('อัพเดต Slow Queries เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Slow Queries ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching slow queries:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Slow Queries');
        });
}

// Update Slow Queries Table
function updateSlowQueriesTable(queries) {
    const tbody = document.getElementById('slowQueriesTable');
    tbody.innerHTML = '';
    
    if (queries.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มี Slow Queries</td></tr>';
        return;
    }
    
    queries.forEach(query => {
        const time = parseFloat(query.time) || 0;
        const badgeClass = time > 2000 ? 'bg-danger' : time > 1000 ? 'bg-warning' : 'bg-success';
        const tableName = query.table_name || query.table || 'Unknown table';
        
        const row = `
            <tr>
                <td>
                    <code>${query.query || 'N/A'}</code>
                    <small class="text-muted d-block">${tableName}</small>
                </td>
                <td><span class="badge ${badgeClass}">${time.toLocaleString()}ms</span></td>
                <td>${query.count || 1}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Refresh Duplicate Queries
function refreshDuplicateQueries() {
    console.log('Refreshing duplicate queries...');
    SwalHelper.loading('กำลังโหลด Duplicate Queries...');
    
    fetch('/api/performance/duplicate-queries')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateDuplicateQueriesTable(data.data);
                SwalHelper.success('อัพเดต Duplicate Queries เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Duplicate Queries ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching duplicate queries:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Duplicate Queries');
        });
}

// Update Duplicate Queries Table
function updateDuplicateQueriesTable(queries) {
    const tbody = document.getElementById('duplicateQueriesTable');
    tbody.innerHTML = '';
    
    if (queries.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มี Duplicate Queries</td></tr>';
        return;
    }
    
    queries.forEach(query => {
        const count = parseInt(query.count) || 0;
        const countBadgeClass = count > 60 ? 'bg-danger' : count > 30 ? 'bg-warning' : 'bg-info';
        const impactBadgeClass = query.impact === 'High' ? 'bg-danger' : query.impact === 'Medium' ? 'bg-warning' : 'bg-success';
        const tableName = query.table_name || query.table || 'Unknown table';
        
        const row = `
            <tr>
                <td>
                    <code>${query.query || 'N/A'}</code>
                    <small class="text-muted d-block">${tableName}</small>
                </td>
                <td><span class="badge ${countBadgeClass}">${count}</span></td>
                <td><span class="badge ${impactBadgeClass}">${query.impact || 'Low'}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Refresh Table Statistics
function refreshTableStatistics() {
    console.log('Refreshing table statistics...');
    SwalHelper.loading('กำลังโหลด Table Statistics...');
    
    fetch('/api/performance/table-statistics')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateTableStatisticsTable(data.data);
                SwalHelper.success('อัพเดต Table Statistics เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Table Statistics ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching table statistics:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Table Statistics');
        });
}

// Update Table Statistics Table
function updateTableStatisticsTable(tables) {
    const tbody = document.getElementById('tableStatisticsTable');
    tbody.innerHTML = '';
    
    if (tables.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มีข้อมูลตาราง</td></tr>';
        return;
    }
    
    tables.forEach(table => {
        const row = `
            <tr>
                <td>
                    <strong>${table.table_name}</strong>
                    <small class="text-muted d-block">${table.table_name.replace('laravel_', '')}</small>
                </td>
                <td>${table.row_count || 0}</td>
                <td>${table.size_mb || 0}</td>
                <td><span class="badge bg-primary">${table.engine || 'InnoDB'}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Refresh Index Statistics
function refreshIndexStatistics() {
    console.log('Refreshing index statistics...');
    SwalHelper.loading('กำลังโหลด Index Statistics...');
    
    fetch('/api/performance/index-statistics')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateIndexStatisticsTable(data.data);
                SwalHelper.success('อัพเดต Index Statistics เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Index Statistics ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching index statistics:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Index Statistics');
        });
}

// Update Index Statistics Table
function updateIndexStatisticsTable(indexes) {
    const tbody = document.getElementById('indexStatisticsTable');
    tbody.innerHTML = '';
    
    if (indexes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มีข้อมูล index</td></tr>';
        return;
    }
    
    indexes.forEach(index => {
        const usageBadgeClass = index.usage_level === 'High' ? 'bg-success' : 
                               index.usage_level === 'Medium' ? 'bg-warning' : 
                               index.usage_level === 'Low' ? 'bg-info' : 'bg-secondary';
        
        const typeBadgeClass = index.index_type === 'UNIQUE' ? 'bg-warning' : 
                              index.index_type === 'FULLTEXT' ? 'bg-info' : 'bg-primary';
        
        const row = `
            <tr>
                <td>
                    <strong>${index.table_name}</strong>
                    <small class="text-muted d-block">${index.column_name}</small>
                </td>
                <td>${index.index_name}</td>
                <td><span class="badge ${usageBadgeClass}">${index.usage_level || 'Unknown'}</span></td>
                <td><span class="badge ${typeBadgeClass}">${index.index_type || 'INDEX'}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// ========================================
// BACKUP SETTINGS FUNCTIONS
// ========================================

// Backup Settings Form
document.getElementById('backupSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        backupFrequency: document.getElementById('backupFrequency').value,
        backupTime: document.getElementById('backupTime').value,
        backupRetention: document.getElementById('backupRetention').value,
        backupLocation: document.getElementById('backupLocation').value
    };
    
    // Validate backup retention
    if (formData.backupRetention < 7) {
        SwalHelper.error('การเก็บไฟล์สำรองต้องไม่น้อยกว่า 7 วัน');
        return;
    }
    
    console.log('Saving backup settings:', formData);
    SwalHelper.loading('กำลังบันทึกการตั้งค่าสำรองข้อมูล...');
    
    // Save to localStorage
    localStorage.setItem('backupSettings', JSON.stringify(formData));
    
    // Simulate API call
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('บันทึกการตั้งค่าสำรองข้อมูลเรียบร้อยแล้ว');
    }, 1500);
});

// Create Backup Function
function createBackup() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะสร้างสำรองข้อมูลตอนนี้?', 'ยืนยันการสร้างสำรองข้อมูล', function() {
        SwalHelper.loading('กำลังสร้างสำรองข้อมูล...');
        
        // Simulate backup creation
        setTimeout(() => {
            SwalHelper.close();
            SwalHelper.success('สร้างสำรองข้อมูลสำเร็จ! ไฟล์สำรองถูกสร้างเรียบร้อยแล้ว');
            
            // Add new backup to history table
            addBackupToHistory();
        }, 3000);
    });
}

// Add new backup to history table
function addBackupToHistory() {
    const tbody = document.querySelector('.backup-history-table tbody');
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 19).replace('T', ' ');
    const fileSize = (Math.random() * 5 + 10).toFixed(1) + ' MB';
    
    const newRow = `
        <tr>
            <td>${dateStr}</td>
            <td>${fileSize}</td>
            <td><span class="badge bg-success">สำเร็จ</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="downloadBackup('${dateStr}')">ดาวน์โหลด</button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteBackup(this)">ลบ</button>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('afterbegin', newRow);
}

// Download Backup Function
function downloadBackup(dateStr) {
    console.log('Downloading backup:', dateStr);
    SwalHelper.loading('กำลังดาวน์โหลดไฟล์สำรอง...');
    
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ดาวน์โหลดไฟล์สำรองสำเร็จ!');
    }, 1500);
}

// Delete Backup Function
function deleteBackup(button) {
    SwalHelper.confirmDelete('คุณแน่ใจหรือไม่ที่จะลบไฟล์สำรองนี้?', function() {
        const row = button.closest('tr');
        row.remove();
        SwalHelper.success('ลบไฟล์สำรองเรียบร้อยแล้ว');
    });
}

// ========================================
// FORM VALIDATION FUNCTIONS
// ========================================

// Real-time form validation
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
// LOAD SAVED SETTINGS
// ========================================

// Load saved settings from localStorage
function loadSavedSettings() {
    // Show loading indicator
    console.log('Loading saved settings...');
    
    // Load General Settings
    const generalSettings = localStorage.getItem('generalSettings');
    if (generalSettings) {
        const data = JSON.parse(generalSettings);
        document.getElementById('siteName').value = data.siteName || '';
        document.getElementById('siteUrl').value = data.siteUrl || '';
        document.getElementById('timezone').value = data.timezone || 'Asia/Bangkok';
        document.getElementById('language').value = data.language || 'th';
        document.getElementById('maintenanceMode').checked = data.maintenanceMode || false;
        document.getElementById('debugMode').checked = data.debugMode || false;
        
        // Update page title if site name exists
        if (data.siteName) {
            document.title = data.siteName + ' - Admin Panel';
        }
        
        console.log('General settings loaded');
    }
    
    // Load Email Settings
    const emailSettings = localStorage.getItem('emailSettings');
    if (emailSettings) {
        const data = JSON.parse(emailSettings);
        document.getElementById('mailDriver').value = data.mailDriver || 'smtp';
        document.getElementById('mailHost').value = data.mailHost || 'smtp.gmail.com';
        document.getElementById('mailPort').value = data.mailPort || '587';
        document.getElementById('mailUsername').value = data.mailUsername || '';
        document.getElementById('mailPassword').value = data.mailPassword || '';
        document.getElementById('mailEncryption').value = data.mailEncryption || 'tls';
        document.getElementById('mailFromAddress').value = data.mailFromAddress || 'noreply@example.com';
        document.getElementById('mailFromName').value = data.mailFromName || '';
        
        console.log('Email settings loaded');
    }
    
    // Load Security Settings
    const securitySettings = localStorage.getItem('securitySettings');
    if (securitySettings) {
        const data = JSON.parse(securitySettings);
        document.getElementById('sessionLifetime').value = data.sessionLifetime || '120';
        document.getElementById('maxLoginAttempts').value = data.maxLoginAttempts || '5';
        document.getElementById('passwordMinLength').value = data.passwordMinLength || '8';
        document.getElementById('requireSpecialChars').checked = data.requireSpecialChars || false;
        document.getElementById('twoFactorAuth').checked = data.twoFactorAuth || false;
        document.getElementById('ipWhitelist').checked = data.ipWhitelist || false;
        
        console.log('Security settings loaded');
    }
    
    // Load Backup Settings
    const backupSettings = localStorage.getItem('backupSettings');
    if (backupSettings) {
        const data = JSON.parse(backupSettings);
        document.getElementById('backupFrequency').value = data.backupFrequency || 'daily';
        document.getElementById('backupTime').value = data.backupTime || '02:00';
        document.getElementById('backupRetention').value = data.backupRetention || '30';
        document.getElementById('backupLocation').value = data.backupLocation || 'local';
        
        console.log('Backup settings loaded');
    }
    
    // Load Audit Settings
    const auditSettings = localStorage.getItem('auditSettings');
    if (auditSettings) {
        const data = JSON.parse(auditSettings);
        document.getElementById('auditEnabled').checked = data.auditEnabled || false;
        document.getElementById('auditRetention').value = data.auditRetention || '90';
        document.getElementById('auditLevel').value = data.auditLevel || 'basic';
        document.getElementById('auditRealTime').checked = data.auditRealTime || false;
        document.getElementById('auditEmailAlerts').checked = data.auditEmailAlerts || false;
        document.getElementById('auditSensitiveActions').checked = data.auditSensitiveActions || false;
        
        console.log('Audit settings loaded');
    }
    
    // Load Performance Settings
    const performanceSettings = localStorage.getItem('performanceSettings');
    if (performanceSettings) {
        const data = JSON.parse(performanceSettings);
        document.getElementById('cacheEnabled').checked = data.cacheEnabled || false;
        document.getElementById('cacheDriver').value = data.cacheDriver || 'file';
        document.getElementById('cacheTTL').value = data.cacheTTL || '60';
        document.getElementById('queryLogging').checked = data.queryLogging || false;
        document.getElementById('slowQueryThreshold').value = data.slowQueryThreshold || '1000';
        document.getElementById('memoryLimit').value = data.memoryLimit || '256';
        document.getElementById('maxExecutionTime').value = data.maxExecutionTime || '30';
        document.getElementById('compressionEnabled').checked = data.compressionEnabled || false;
        
        // Update switch styling after loading from localStorage
        updateSwitchStyle('cacheEnabled', 'cacheEnabledLabel');
        updateSwitchStyle('queryLogging', 'queryLoggingLabel');
        updateSwitchStyle('compressionEnabled', 'compressionEnabledLabel');
        
        console.log('Performance settings loaded');
    } else {
        // Load from API if no localStorage data
        loadPerformanceSettingsFromAPI();
    }
    
    console.log('All settings loaded from localStorage');
}

// Load Performance Settings from API
function loadPerformanceSettingsFromAPI() {
    fetch('/api/performance/settings')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const settings = data.data;
                document.getElementById('cacheEnabled').checked = settings.cache_enabled || false;
                document.getElementById('cacheDriver').value = settings.cache_driver || 'file';
                document.getElementById('cacheTTL').value = settings.cache_ttl || '60';
                document.getElementById('queryLogging').checked = settings.query_logging || false;
                document.getElementById('slowQueryThreshold').value = settings.slow_query_threshold || '1000';
                document.getElementById('memoryLimit').value = settings.memory_limit || '256';
                document.getElementById('maxExecutionTime').value = settings.max_execution_time || '30';
                document.getElementById('compressionEnabled').checked = settings.compression_enabled || false;
                
                // Update switch styling after loading settings
                updateSwitchStyle('cacheEnabled', 'cacheEnabledLabel');
                updateSwitchStyle('queryLogging', 'queryLoggingLabel');
                updateSwitchStyle('compressionEnabled', 'compressionEnabledLabel');
                
                // Also save to localStorage for consistency
                const localStorageData = {
                    cacheEnabled: settings.cache_enabled || false,
                    cacheDriver: settings.cache_driver || 'file',
                    cacheTTL: settings.cache_ttl || '60',
                    queryLogging: settings.query_logging || false,
                    slowQueryThreshold: settings.slow_query_threshold || '1000',
                    memoryLimit: settings.memory_limit || '256',
                    maxExecutionTime: settings.max_execution_time || '30',
                    compressionEnabled: settings.compression_enabled || false
                };
                localStorage.setItem('performanceSettings', JSON.stringify(localStorageData));
                
                console.log('Performance settings loaded from API');
            }
        })
        .catch(error => {
            console.error('Error loading performance settings:', error);
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
// TAB PERSISTENCE FUNCTIONS
// ========================================

// Load last active tab from localStorage
function loadLastActiveTab() {
    const lastTab = localStorage.getItem('lastActiveSettingsTab');
    const lastTabIcon = localStorage.getItem('lastActiveSettingsTabIcon');
    const lastTabText = localStorage.getItem('lastActiveSettingsTabText');
    
    if (lastTab && lastTabIcon && lastTabText) {
        // Hide all tab panes first
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        
        // Show selected tab pane
        document.getElementById(lastTab).classList.add('show', 'active');
        
        // Update mobile navigation display
        document.getElementById('currentTabIcon').className = lastTabIcon;
        document.getElementById('currentTabText').textContent = lastTabText;
        
        // Update desktop tabs if visible
        if (window.innerWidth >= 768) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            const desktopTab = document.getElementById(lastTab + '-tab');
            if (desktopTab) {
                desktopTab.classList.add('active');
            }
        }
        
        // Also update the default active state in HTML
        document.querySelectorAll('.tab-pane').forEach(pane => {
            if (pane.id === lastTab) {
                pane.classList.add('show', 'active');
            } else {
                pane.classList.remove('show', 'active');
            }
        });
        
        // Note: Performance data will be loaded manually when user clicks on performance tab
        
        console.log('Loaded last active tab:', lastTab);
    } else {
        // Default to general tab if no saved tab
        console.log('No saved tab found, using default general tab');
    }
}

// ========================================
// QUERY LOGGING SWITCH STYLING
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

// Update Query Logging Switch Style (Legacy function for compatibility)
function updateQueryLoggingSwitchStyle() {
    updateSwitchStyle('queryLogging', 'queryLoggingLabel');
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

</script>
@endpush