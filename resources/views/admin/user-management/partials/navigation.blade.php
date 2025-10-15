<!-- User Management Navigation - Desktop -->
<div>
    <div>
        <ul id="userManagementTabs">
            <li>
                <button id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" onclick="switchTab('users', 'fas fa-users', 'ผู้ใช้')">
                    <i class="fas fa-users"></i>ผู้ใช้
                </button>
            </li>
            <li>
                <button id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" onclick="switchTab('roles', 'fas fa-user-shield', 'บทบาท')">
                    <i class="fas fa-user-shield"></i>บทบาท
                </button>
            </li>
            <li>
                <button id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" onclick="switchTab('permissions', 'fas fa-key', 'สิทธิ์')">
                    <i class="fas fa-key"></i>สิทธิ์
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- User Management Navigation - Mobile -->
<div>
    <div>
        <div onclick="toggleUserManagementDropdown()">
            <div>
                <i class="fas fa-users" id="currentTabIcon"></i>
                <span id="currentTabText">ผู้ใช้</span>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
        
        <div id="userManagementDropdown">
            <div onclick="switchTab('users', 'fas fa-users', 'ผู้ใช้')">
                <i class="fas fa-users"></i>
                <span>ผู้ใช้</span>
                <small>จัดการข้อมูลผู้ใช้งานระบบ</small>
            </div>
            <div onclick="switchTab('roles', 'fas fa-user-shield', 'บทบาท')">
                <i class="fas fa-user-shield"></i>
                <span>บทบาท</span>
                <small>จัดการบทบาทและสิทธิ์การเข้าถึง</small>
            </div>
            <div onclick="switchTab('permissions', 'fas fa-key', 'สิทธิ์')">
                <i class="fas fa-key"></i>
                <span>สิทธิ์</span>
                <small>จัดการสิทธิ์การเข้าถึงระบบ</small>
            </div>
        </div>
    </div>
</div>
