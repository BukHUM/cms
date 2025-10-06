<!-- Settings Navigation - Desktop -->
<div class="d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" onclick="switchTab('general', 'fas fa-cog', 'ทั่วไป')">
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
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="system-info-tab" data-bs-toggle="tab" data-bs-target="#system-info" type="button" onclick="switchTab('system-info', 'fas fa-server', 'ข้อมูลระบบ')">
                    <i class="fas fa-server"></i>ข้อมูลระบบ
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
            <div class="dropdown-item" onclick="switchTab('system-info', 'fas fa-server', 'ข้อมูลระบบ')">
                <i class="fas fa-server"></i>
                <span>ข้อมูลระบบ</span>
                <small>สถานะและข้อมูลระบบ</small>
            </div>
        </div>
    </div>
</div>
