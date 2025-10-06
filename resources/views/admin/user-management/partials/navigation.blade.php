<!-- User Management Navigation - Desktop -->
<div class="d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs" id="userManagementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" onclick="switchTab('users', 'fas fa-users', 'ผู้ใช้')">
                    <i class="fas fa-users"></i>ผู้ใช้
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" onclick="switchTab('roles', 'fas fa-user-shield', 'บทบาท')">
                    <i class="fas fa-user-shield"></i>บทบาท
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" onclick="switchTab('permissions', 'fas fa-key', 'สิทธิ์')">
                    <i class="fas fa-key"></i>สิทธิ์
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- User Management Navigation - Mobile -->
<div class="d-md-none mb-4">
    <div class="settings-mobile-nav">
        <div class="current-tab-display" onclick="toggleUserManagementDropdown()">
            <div class="tab-info">
                <i class="fas fa-users" id="currentTabIcon"></i>
                <span id="currentTabText">ผู้ใช้</span>
            </div>
            <i class="fas fa-chevron-down dropdown-arrow"></i>
        </div>
        
        <div class="settings-dropdown" id="userManagementDropdown" style="display: none;">
            <div class="dropdown-item active" onclick="switchTab('users', 'fas fa-users', 'ผู้ใช้')">
                <i class="fas fa-users"></i>
                <span>ผู้ใช้</span>
                <small>จัดการข้อมูลผู้ใช้งานระบบ</small>
            </div>
            <div class="dropdown-item" onclick="switchTab('roles', 'fas fa-user-shield', 'บทบาท')">
                <i class="fas fa-user-shield"></i>
                <span>บทบาท</span>
                <small>จัดการบทบาทและสิทธิ์การเข้าถึง</small>
            </div>
            <div class="dropdown-item" onclick="switchTab('permissions', 'fas fa-key', 'สิทธิ์')">
                <i class="fas fa-key"></i>
                <span>สิทธิ์</span>
                <small>จัดการสิทธิ์การเข้าถึงระบบ</small>
            </div>
        </div>
    </div>
</div>
