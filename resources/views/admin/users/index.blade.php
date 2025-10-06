@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้')
@section('page-title', 'จัดการผู้ใช้')
@section('page-subtitle', 'จัดการข้อมูลผู้ใช้และสิทธิ์การเข้าถึง')

@section('content')
<!-- Action Buttons - Desktop -->
<div class="row mb-4 d-none d-md-flex">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="action-buttons">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-2"></i>
                    เพิ่มผู้ใช้ใหม่
                </button>
                <button class="btn btn-success" onclick="exportUsers()">
                    <i class="fas fa-download me-2"></i>
                    ส่งออกข้อมูล
                </button>
            </div>
            <div class="search-container">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="ค้นหาผู้ใช้..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons - Mobile -->
<div class="row mb-4 d-md-none">
    <div class="col-12">
        <div class="mobile-actions">
            <!-- Primary Action -->
            <div class="mobile-primary-action">
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-2"></i>
                    เพิ่มผู้ใช้ใหม่
                </button>
            </div>
            
            <!-- Secondary Actions -->
            <div class="mobile-secondary-actions">
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-success w-100" onclick="exportUsers()">
                            <i class="fas fa-download me-1"></i>
                            <span class="d-none d-sm-inline">ส่งออกข้อมูล</span>
                            <span class="d-sm-none">ส่งออก</span>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-info w-100" onclick="toggleMobileSearch()">
                            <i class="fas fa-search me-1"></i>
                            <span class="d-none d-sm-inline">ค้นหา</span>
                            <span class="d-sm-none">ค้นหา</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Search -->
            <div class="mobile-search-container" id="mobileSearchContainer" style="display: none;">
                <div class="input-group mt-2">
                    <input type="text" class="form-control" placeholder="ค้นหาผู้ใช้..." id="mobileSearchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table - Desktop -->
<div class="card shadow d-none d-md-block">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">รายชื่อผู้ใช้</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อีเมล</th>
                        <th>สถานะ</th>
                        <th>วันที่สมัคร</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://placehold.co/40" class="rounded-circle me-3" alt="User">
                                <div>
                                    <div class="fw-bold">John Doe</div>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                        </td>
                        <td>john.doe@example.com</td>
                        <td><span class="badge bg-success">ใช้งาน</span></td>
                        <td>2024-01-15</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(1)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="viewUser(1)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(1)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://placehold.co/40" class="rounded-circle me-3" alt="User">
                                <div>
                                    <div class="fw-bold">Jane Smith</div>
                                    <small class="text-muted">User</small>
                                </div>
                            </div>
                        </td>
                        <td>jane.smith@example.com</td>
                        <td><span class="badge bg-success">ใช้งาน</span></td>
                        <td>2024-01-20</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(2)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="viewUser(2)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(2)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://placehold.co/40" class="rounded-circle me-3" alt="User">
                                <div>
                                    <div class="fw-bold">Bob Johnson</div>
                                    <small class="text-muted">User</small>
                                </div>
                            </div>
                        </td>
                        <td>bob.johnson@example.com</td>
                        <td><span class="badge bg-warning">รอการยืนยัน</span></td>
                        <td>2024-01-25</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(3)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="viewUser(3)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(3)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Users Cards - Mobile -->
<div class="d-md-none">
    <div class="row g-3" id="usersCards">
        <!-- User Card 1 -->
        <div class="col-12">
            <div class="user-card">
                <div class="user-card-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            <img src="https://placehold.co/50" class="rounded-circle" alt="User">
                        </div>
                        <div class="user-details">
                            <div class="user-name">John Doe</div>
                            <div class="user-role">Admin</div>
                            <div class="user-email">john.doe@example.com</div>
                        </div>
                    </div>
                    <div class="user-status">
                        <span class="badge bg-success">ใช้งาน</span>
                    </div>
                </div>
                <div class="user-card-body">
                    <div class="user-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>2024-01-15</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-hashtag"></i>
                            <span>ID: 1</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-footer">
                    <div class="user-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="editUser(1)">
                            <i class="fas fa-edit"></i>
                            <span>แก้ไข</span>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewUser(1)">
                            <i class="fas fa-eye"></i>
                            <span>ดู</span>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewUser(1)">
                                    <i class="fas fa-eye me-2"></i>ดูรายละเอียด
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="editUser(1)">
                                    <i class="fas fa-edit me-2"></i>แก้ไขข้อมูล
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(1)">
                                    <i class="fas fa-trash me-2"></i>ลบผู้ใช้
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Card 2 -->
        <div class="col-12">
            <div class="user-card">
                <div class="user-card-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            <img src="https://placehold.co/50" class="rounded-circle" alt="User">
                        </div>
                        <div class="user-details">
                            <div class="user-name">Jane Smith</div>
                            <div class="user-role">User</div>
                            <div class="user-email">jane.smith@example.com</div>
                        </div>
                    </div>
                    <div class="user-status">
                        <span class="badge bg-success">ใช้งาน</span>
                    </div>
                </div>
                <div class="user-card-body">
                    <div class="user-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>2024-01-20</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-hashtag"></i>
                            <span>ID: 2</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-footer">
                    <div class="user-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="editUser(2)">
                            <i class="fas fa-edit"></i>
                            <span>แก้ไข</span>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewUser(2)">
                            <i class="fas fa-eye"></i>
                            <span>ดู</span>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewUser(2)">
                                    <i class="fas fa-eye me-2"></i>ดูรายละเอียด
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="editUser(2)">
                                    <i class="fas fa-edit me-2"></i>แก้ไขข้อมูล
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(2)">
                                    <i class="fas fa-trash me-2"></i>ลบผู้ใช้
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Card 3 -->
        <div class="col-12">
            <div class="user-card">
                <div class="user-card-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            <img src="https://placehold.co/50" class="rounded-circle" alt="User">
                        </div>
                        <div class="user-details">
                            <div class="user-name">Bob Johnson</div>
                            <div class="user-role">User</div>
                            <div class="user-email">bob.johnson@example.com</div>
                        </div>
                    </div>
                    <div class="user-status">
                        <span class="badge bg-warning">รอการยืนยัน</span>
                    </div>
                </div>
                <div class="user-card-body">
                    <div class="user-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>2024-01-25</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-hashtag"></i>
                            <span>ID: 3</span>
                        </div>
                    </div>
                </div>
                <div class="user-card-footer">
                    <div class="user-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="editUser(3)">
                            <i class="fas fa-edit"></i>
                            <span>แก้ไข</span>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewUser(3)">
                            <i class="fas fa-eye"></i>
                            <span>ดู</span>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewUser(3)">
                                    <i class="fas fa-eye me-2"></i>ดูรายละเอียด
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="editUser(3)">
                                    <i class="fas fa-edit me-2"></i>แก้ไขข้อมูล
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(3)">
                                    <i class="fas fa-trash me-2"></i>ลบผู้ใช้
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มผู้ใช้ใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">ชื่อ *</label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">นามสกุล *</label>
                            <input type="text" class="form-control" id="lastName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">อีเมล *</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control" id="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">บทบาท *</label>
                            <select class="form-select" id="role" required>
                                <option value="">เลือกบทบาท</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                                <option value="moderator">Moderator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">สถานะ *</label>
                            <select class="form-select" id="status" required>
                                <option value="active">ใช้งาน</option>
                                <option value="inactive">ไม่ใช้งาน</option>
                                <option value="pending">รอการยืนยัน</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="password" class="form-label">รหัสผ่าน *</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เพิ่มผู้ใช้</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Mobile search toggle
function toggleMobileSearch() {
    const container = document.getElementById('mobileSearchContainer');
    if (container.style.display === 'none') {
        container.style.display = 'block';
        document.getElementById('mobileSearchInput').focus();
    } else {
        container.style.display = 'none';
    }
}

// Search functionality for desktop
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#usersTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search functionality for mobile
document.getElementById('mobileSearchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const userCards = document.querySelectorAll('.user-card');
    
    userCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            card.closest('.col-12').style.display = '';
        } else {
            card.closest('.col-12').style.display = 'none';
        }
    });
});

// Add user form
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        role: document.getElementById('role').value,
        status: document.getElementById('status').value,
        password: document.getElementById('password').value
    };
    
    // Here you would typically send the data to your backend
    console.log('Adding user:', formData);
    
    // Show success message
    SwalHelper.success('เพิ่มผู้ใช้เรียบร้อยแล้ว');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    modal.hide();
    
    // Reset form
    this.reset();
});

// User actions
function editUser(userId) {
    console.log('Edit user:', userId);
    SwalHelper.info('แก้ไขข้อมูลผู้ใช้ ID: ' + userId);
}

function viewUser(userId) {
    console.log('View user:', userId);
    SwalHelper.info('ดูข้อมูลผู้ใช้ ID: ' + userId);
}

function deleteUser(userId) {
    SwalHelper.confirmDelete('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?', function() {
        console.log('Delete user:', userId);
        SwalHelper.success('ลบผู้ใช้เรียบร้อยแล้ว');
    });
}

function exportUsers() {
    console.log('Exporting users...');
    SwalHelper.loading('กำลังส่งออกข้อมูลผู้ใช้...');
    
    // Simulate export process
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ส่งออกข้อมูลผู้ใช้สำเร็จ!');
    }, 2000);
}

// Initialize mobile functionality
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile search when clicking outside
    document.addEventListener('click', function(e) {
        const mobileSearchContainer = document.getElementById('mobileSearchContainer');
        const searchButton = document.querySelector('[onclick="toggleMobileSearch()"]');
        
        if (mobileSearchContainer && 
            !mobileSearchContainer.contains(e.target) && 
            !searchButton.contains(e.target)) {
            mobileSearchContainer.style.display = 'none';
        }
    });
});
</script>
@endpush
