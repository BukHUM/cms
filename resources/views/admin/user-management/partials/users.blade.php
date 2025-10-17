<!-- Users Tab -->
<div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">

    <!-- Action Buttons and Filters -->
    <div class="settings-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <!-- Desktop Layout -->
                    <div class="desktop-layout d-none d-md-block">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <!-- Action Buttons -->
                            <div class="action-buttons d-flex gap-2">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus me-1"></i>
                                    เพิ่มผู้ใช้ใหม่
                                </button>
                            </div>
                            
                            <!-- Search and Filters -->
                            <div class="search-filters d-flex gap-3 align-items-center w-100">
                                <!-- Search Input -->
                                <div class="search-container flex-fill">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="ค้นหาผู้ใช้..." id="usersSearchInput">
                                    </div>
                                </div>
                                
                                <!-- Status Filter -->
                                <div class="filter-dropdown">
                                    <select class="form-select form-select-sm" id="statusFilter" onchange="filterByStatus(this.value)">
                                        <option value="">ทุกสถานะ</option>
                                        <option value="active">ใช้งาน</option>
                                        <option value="inactive">ไม่ใช้งาน</option>
                                        <option value="pending">รอการยืนยัน</option>
                                    </select>
                                </div>
                                
                                <!-- Role Filter -->
                                <div class="filter-dropdown">
                                    <select class="form-select form-select-sm" id="roleFilter" onchange="filterByRole(this.value)">
                                        <option value="">ทุกบทบาท</option>
                                        @foreach(\App\Models\Role::active()->ordered()->get() as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Layout -->
                    <div class="mobile-layout d-md-none">
                        <!-- Action Buttons Row -->
                        <div class="action-buttons-row d-flex gap-2 mb-3">
                            <button class="btn btn-primary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-1"></i>
                                เพิ่มผู้ใช้ใหม่
                            </button>
                        </div>
                        
                        <!-- Search and Filters Row -->
                        <div class="search-filters-row">
                            <!-- Search Input -->
                            <div class="search-container mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="ค้นหาผู้ใช้..." id="usersSearchInputMobile">
                                </div>
                            </div>
                            
                            <!-- Filters Row -->
                            <div class="filters-row d-flex gap-2">
                                <!-- Status Filter -->
                                <div class="filter-dropdown flex-fill">
                                    <select class="form-select form-select-sm" id="statusFilterMobile" onchange="filterByStatus(this.value)">
                                        <option value="">ทุกสถานะ</option>
                                        <option value="active">ใช้งาน</option>
                                        <option value="inactive">ไม่ใช้งาน</option>
                                        <option value="pending">รอการยืนยัน</option>
                                    </select>
                                </div>
                                
                                <!-- Role Filter -->
                                <div class="filter-dropdown flex-fill">
                                    <select class="form-select form-select-sm" id="roleFilterMobile" onchange="filterByRole(this.value)">
                                        <option value="">ทุกบทบาท</option>
                                        @foreach(\App\Models\Role::active()->ordered()->get() as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="settings-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">รายการผู้ใช้ (<span id="userCount">{{ $users->count() }}</span>)</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover user-management-table">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>ผู้ใช้</th>
                            <th class="d-none d-md-table-cell">อีเมล</th>
                            <th class="d-none d-lg-table-cell">บทบาท</th>
                            <th>สถานะ</th>
                            <th width="120">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="user-row" data-user-id="{{ $user->id }}" data-status="{{ $user->status }}">
                            <td>
                                <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6c757d&color=fff' }}" 
                                             alt="User" class="rounded-circle" width="40" height="40">
                                        @if($user->status === 'active')
                                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success user-status-indicator active" style="width: 12px; height: 12px;"></span>
                                        @elseif($user->status === 'pending')
                                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning user-status-indicator pending" style="width: 12px; height: 12px;"></span>
                                        @elseif($user->status === 'suspended')
                                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-danger user-status-indicator suspended" style="width: 12px; height: 12px;"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                        @if($user->phone)
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                                        </small>
                                        @endif
                                        @if($user->last_login_at)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-clock me-1"></i>เข้าสู่ระบบล่าสุด: {{ $user->last_login_at->diffForHumans() }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                    <span class="badge bg-success" title="ยืนยันอีเมลแล้ว">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                    <span class="badge bg-primary role-badge" data-role-id="{{ $role->id }}">{{ $role->name }}</span>
                                    @empty
                                    <span class="text-muted">ไม่มีบทบาท</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @if($user->status === 'active')
                                <span class="badge bg-success status-badge">ใช้งาน</span>
                                @elseif($user->status === 'pending')
                                <span class="badge bg-warning status-badge">รอการยืนยัน</span>
                                @elseif($user->status === 'suspended')
                                <span class="badge bg-danger status-badge">ระงับการใช้งาน</span>
                                @else
                                <span class="badge bg-secondary status-badge">ไม่ใช้งาน</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons-inline">
                                    <button class="btn-action btn-view" onclick="viewUser({{ $user->id }})" title="ดูข้อมูล">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <button class="btn-action btn-edit" onclick="editUser({{ $user->id }})" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteUser({{ $user->id }})" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>ไม่พบข้อมูลผู้ใช้</h5>
                                    <p>ยังไม่มีผู้ใช้ในระบบ หรือไม่ตรงกับเงื่อนไขการค้นหา</p>
                                    
                                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="fas fa-plus me-2"></i>เพิ่มผู้ใช้คนแรก
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="pagination-container">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>
                    เพิ่มผู้ใช้ใหม่
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="fullName" class="form-label">
                                        <i class="fas fa-user me-1"></i>ชื่อ-นามสกุล *
                                    </label>
                                    <input type="text" class="form-control" id="fullName" required placeholder="กรอกชื่อ-นามสกุล">
                                    <div class="form-text">กรอกชื่อและนามสกุลในช่องเดียวกัน</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>อีเมล *
                                    </label>
                                    <input type="email" class="form-control" id="email" required placeholder="example@domain.com">
                                    <div class="form-text">อีเมลจะใช้สำหรับเข้าสู่ระบบ</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>เบอร์โทรศัพท์
                                    </label>
                                    <input type="tel" class="form-control" id="phone" placeholder="08x-xxx-xxxx">
                                </div>
                                <div class="col-md-6">
                                    <label for="userRoles" class="form-label">
                                        <i class="fas fa-user-shield me-1"></i>บทบาท *
                                    </label>
                                    <select class="form-select" id="userRoles" multiple required>
                                        @foreach(\App\Models\Role::active()->ordered()->get() as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">เลือกบทบาทที่ต้องการมอบหมาย</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>สถานะ *
                                    </label>
                                    <select class="form-select" id="status" required>
                                        <option value="active">ใช้งาน</option>
                                        <option value="pending">รอการยืนยัน</option>
                                        <option value="inactive">ไม่ใช้งาน</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>รหัสผ่าน *
                                    </label>
                                    <input type="password" class="form-control" id="password" required placeholder="กรอกรหัสผ่าน">
                                    <div class="form-text">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock me-1"></i>ยืนยันรหัสผ่าน *
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation" required placeholder="ยืนยันรหัสผ่าน">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <img id="avatarPreview" src="https://ui-avatars.com/api/?name=ผู้ใช้ใหม่&background=6c757d&color=fff" 
                                         alt="Avatar Preview" class="rounded-circle" width="120" height="120">
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    รูปโปรไฟล์จะถูกสร้างอัตโนมัติจากชื่อ
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">รายละเอียดผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewUserModalBody">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">กำลังโหลด...</span>
                    </div>
                    <p class="mt-2">กำลังโหลดข้อมูลผู้ใช้...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="editUserFromViewBtn">แก้ไขข้อมูล</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">แก้ไขข้อมูลผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body" id="editUserModalBody">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">กำลังโหลด...</span>
                        </div>
                        <p class="mt-2">กำลังโหลดข้อมูลผู้ใช้...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/user-management/filter-functions.js') }}"></script>

<script>
let searchTimeout;
let currentSearchQuery = '';

document.addEventListener('DOMContentLoaded', function() {
    // Add AJAX search functionality to search inputs
    const searchInputs = [
        document.getElementById('usersSearchInput'),
        document.getElementById('usersSearchInputMobile')
    ];
    
    searchInputs.forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // Set new timeout for debounced search
                searchTimeout = setTimeout(() => {
                    performAjaxSearch(query);
                }, 300); // 300ms delay
            });
        }
    });
    
    // Initialize pagination visibility
    updatePaginationVisibility();
});

function performAjaxSearch(query) {
    currentSearchQuery = query;
    
    // Show loading state
    showSearchLoading();
    
    // Prepare search parameters
    const params = new URLSearchParams({
        search: query,
        status: document.getElementById('statusFilter')?.value || document.getElementById('statusFilterMobile')?.value || '',
        role: document.getElementById('roleFilter')?.value || document.getElementById('roleFilterMobile')?.value || '',
        page: 1 // Reset to first page on search
    });
    
    // Make AJAX request
    fetch(`{{ route('admin.users.search') }}?${params}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        updateUserTable(data);
        updateUserCount(data.total);
        updatePaginationVisibility();
    })
    .catch(error => {
        console.error('Search error:', error);
        showSearchError();
    });
}

function showSearchLoading() {
    const tbody = document.querySelector('.user-management-table tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">กำลังค้นหา...</span>
                        </div>
                        <span class="text-muted">กำลังค้นหาข้อมูล...</span>
                    </div>
                </td>
            </tr>
        `;
    }
}

function showSearchError() {
    const tbody = document.querySelector('.user-management-table tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        เกิดข้อผิดพลาดในการค้นหา กรุณาลองใหม่อีกครั้ง
                    </div>
                </td>
            </tr>
        `;
    }
}

function updateUserTable(data) {
    const tbody = document.querySelector('.user-management-table tbody');
    if (!tbody) return;
    
    if (data.users && data.users.length > 0) {
        let html = '';
        data.users.forEach(user => {
            const statusBadge = getStatusBadge(user.status);
            const statusIndicator = getStatusIndicator(user.status);
            const rolesHtml = user.roles && user.roles.length > 0 
                ? user.roles.map(role => `<span class="badge bg-primary role-badge" data-role-id="${role.id}">${role.name}</span>`).join('')
                : '<span class="text-muted">ไม่มีบทบาท</span>';
            
            const lastLogin = user.last_login_at 
                ? `<small class="text-muted d-block"><i class="fas fa-clock me-1"></i>เข้าสู่ระบบล่าสุด: ${user.last_login_human}</small>`
                : '';
            
            const phone = user.phone 
                ? `<small class="text-muted"><i class="fas fa-phone me-1"></i>${user.phone}</small>`
                : '';
            
            const emailVerified = user.email_verified_at 
                ? '<span class="badge bg-success" title="ยืนยันอีเมลแล้ว"><i class="fas fa-check"></i></span>'
                : '';
            
            const actionButtons = user.id !== {{ auth()->id() }} 
                ? `
                    <button class="btn-action btn-view" onclick="viewUser(${user.id})" title="ดูข้อมูล">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" onclick="editUser(${user.id})" title="แก้ไข">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteUser(${user.id})" title="ลบ">
                        <i class="fas fa-trash"></i>
                    </button>
                `
                : `
                    <button class="btn-action btn-view" onclick="viewUser(${user.id})" title="ดูข้อมูล">
                        <i class="fas fa-eye"></i>
                    </button>
                `;
            
            html += `
                <tr class="user-row" data-user-id="${user.id}" data-status="${user.status}">
                    <td>
                        <input type="checkbox" class="form-check-input user-checkbox" value="${user.id}" onchange="updateBulkActions()">
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="${user.avatar_url}" alt="User" class="rounded-circle" width="40" height="40">
                                ${statusIndicator}
                            </div>
                            <div>
                                <div class="fw-medium">${user.name}</div>
                                ${phone}
                                ${lastLogin}
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div class="d-flex align-items-center">
                            <span class="me-2">${user.email}</span>
                            ${emailVerified}
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="d-flex flex-wrap gap-1">
                            ${rolesHtml}
                        </div>
                    </td>
                    <td>
                        ${statusBadge}
                    </td>
                    <td>
                        <div class="action-buttons-inline">
                            ${actionButtons}
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h5>ไม่พบข้อมูลผู้ใช้</h5>
                        <p>ไม่พบผู้ใช้ที่ตรงกับคำค้นหา "${currentSearchQuery}"</p>
                        ${currentSearchQuery ? '<button class="btn btn-outline-primary mt-3" onclick="clearSearch()">ล้างการค้นหา</button>' : ''}
                    </div>
                </td>
            </tr>
        `;
    }
}

function getStatusBadge(status) {
    const badges = {
        'active': '<span class="badge bg-success status-badge">ใช้งาน</span>',
        'pending': '<span class="badge bg-warning status-badge">รอการยืนยัน</span>',
        'suspended': '<span class="badge bg-danger status-badge">ระงับการใช้งาน</span>',
        'inactive': '<span class="badge bg-secondary status-badge">ไม่ใช้งาน</span>'
    };
    return badges[status] || badges['inactive'];
}

function getStatusIndicator(status) {
    const indicators = {
        'active': '<span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success user-status-indicator active" style="width: 12px; height: 12px;"></span>',
        'pending': '<span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning user-status-indicator pending" style="width: 12px; height: 12px;"></span>',
        'suspended': '<span class="position-absolute bottom-0 end-0 badge rounded-pill bg-danger user-status-indicator suspended" style="width: 12px; height: 12px;"></span>'
    };
    return indicators[status] || '';
}

function updateUserCount(total) {
    const countElement = document.getElementById('userCount');
    if (countElement) {
        countElement.textContent = total;
    }
}

function updatePaginationVisibility() {
    const paginationContainer = document.querySelector('.pagination-container');
    if (paginationContainer) {
        const visibleRows = document.querySelectorAll('.user-row:not([style*="display: none"])');
        if (visibleRows.length === 0) {
            paginationContainer.style.display = 'none';
        } else {
            paginationContainer.style.display = 'block';
        }
    }
}

function clearSearch() {
    const searchInputs = [
        document.getElementById('usersSearchInput'),
        document.getElementById('usersSearchInputMobile')
    ];
    
    searchInputs.forEach(input => {
        if (input) {
            input.value = '';
        }
    });
    
    // Reload the page to show all users
    window.location.reload();
}
</script>