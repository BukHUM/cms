<!-- Users Tab -->
<div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">

    <!-- Action Buttons and Filters -->
    <div class="settings-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="left-section d-flex gap-3 align-items-center flex-wrap">
                            <div class="action-buttons d-flex gap-2 flex-wrap">
                                @can('users.create')
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus me-1"></i>
                                    เพิ่มผู้ใช้ใหม่
                                </button>
                                @endcan
                                @can('users.view')
                                <button class="btn btn-outline-success btn-sm" onclick="exportUsers()">
                                    <i class="fas fa-download me-1"></i>
                                    ส่งออก
                                </button>
                                @endcan
                            </div>
                            <!-- Search Input -->
                            <div class="search-container">
                                <div class="input-group" style="min-width: 250px;">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="ค้นหาผู้ใช้..." id="usersSearchInput">
                                </div>
                            </div>
                        </div>
                        
                        <div class="right-section d-flex gap-3 align-items-center flex-wrap">
                            <!-- Status Filter -->
                            <div class="filter-dropdown">
                                <select class="form-select form-select-sm" id="statusFilter" onchange="filterByStatus(this.value)">
                                    <option value="">ทุกสถานะ</option>
                                    <option value="active">ใช้งาน</option>
                                    <option value="inactive">ไม่ใช้งาน</option>
                                    <option value="pending">รอการยืนยัน</option>
                                    <option value="suspended">ระงับการใช้งาน</option>
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
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="settings-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    รายชื่อผู้ใช้
                    <span class="badge bg-primary ms-2" id="userCount">{{ $users->count() }}</span>
                </h6>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="selectAllUsers()">
                                <i class="fas fa-check-square me-2"></i>เลือกทั้งหมด
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="clearSelection()">
                                <i class="fas fa-square me-2"></i>ยกเลิกการเลือก
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkStatusUpdate('active')">
                                <i class="fas fa-check me-2"></i>เปลี่ยนเป็นใช้งาน
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkStatusUpdate('inactive')">
                                <i class="fas fa-times me-2"></i>เปลี่ยนเป็นไม่ใช้งาน
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 user-management-table" id="usersTable">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>ผู้ใช้</th>
                            <th class="d-none d-md-table-cell">อีเมล</th>
                            <th class="d-none d-lg-table-cell">บทบาท</th>
                            <th>สถานะ</th>
                            <th class="d-none d-lg-table-cell">วันที่สมัคร</th>
                            <th width="120">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr data-user-id="{{ $user->id }}" data-status="{{ $user->status }}">
                            <td>
                                <input type="checkbox" class="form-check-input" value="{{ $user->id }}" onchange="updateBulkActions()">
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
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        @if($user->phone)
                                        <small class="text-muted d-none d-md-block">
                                            <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                                        </small>
                                        @endif
                                        @if($user->last_login_at)
                                        <small class="text-muted d-none d-lg-block">
                                            <i class="fas fa-clock me-1"></i>เข้าสู่ระบบล่าสุด: {{ $user->last_login_at->diffForHumans() }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                    <span>{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                    <i class="fas fa-check-circle text-success ms-2" title="ยืนยันอีเมลแล้ว"></i>
                                    @else
                                    <i class="fas fa-exclamation-circle text-warning ms-2" title="ยังไม่ยืนยันอีเมล"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary" title="{{ $role->description }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" 
                                            @if($user->status === 'active') class="btn btn-sm btn-success dropdown-toggle"
                                            @elseif($user->status === 'inactive') class="btn btn-sm btn-secondary dropdown-toggle"
                                            @elseif($user->status === 'pending') class="btn btn-sm btn-warning dropdown-toggle"
                                            @elseif($user->status === 'suspended') class="btn btn-sm btn-danger dropdown-toggle"
                                            @endif>
                                        {{ $user->getStatusDisplayName() }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'active')">
                                            <i class="fas fa-check me-2"></i>ใช้งาน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'inactive')">
                                            <i class="fas fa-times me-2"></i>ไม่ใช้งาน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'pending')">
                                            <i class="fas fa-clock me-2"></i>รอการยืนยัน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'suspended')">
                                            <i class="fas fa-ban me-2"></i>ระงับการใช้งาน
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <small class="text-muted">
                                    {{ $user->created_at->format('d/m/Y') }}<br>
                                    <i class="fas fa-clock me-1"></i>{{ $user->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div class="action-buttons" style="display: flex; gap: 4px; align-items: center;">
                                    <!-- Test Button - Always Show -->
                                    <button class="btn-action btn-download" onclick="alert('Test Button - User ID: {{ $user->id }}')" title="ทดสอบ" 
                                            style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #fff3e0; color: #f57c00;"
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.backgroundColor='#ffe0b2';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='#fff3e0';">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @can('users.view')
                                    <button class="btn-action btn-download" onclick="viewUser({{ $user->id }})" title="ดูรายละเอียด" 
                                            style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #fff3e0; color: #f57c00;"
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.backgroundColor='#ffe0b2';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='#fff3e0';">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @endcan
                                    @can('users.update')
                                    <button class="btn-action btn-download" onclick="editUser({{ $user->id }})" title="แก้ไข" 
                                            style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #fff3e0; color: #f57c00;"
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.backgroundColor='#ffe0b2';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='#fff3e0';">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('users.roles')
                                    <button class="btn-action btn-download" onclick="manageUserRoles({{ $user->id }})" title="จัดการบทบาท" 
                                            style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #fff3e0; color: #f57c00;"
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.backgroundColor='#ffe0b2';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='#fff3e0';">
                                        <i class="fas fa-user-shield"></i>
                                    </button>
                                    @endcan
                                    @can('users.delete')
                                    @if($user->id !== auth()->id())
                                    <button class="btn-action btn-delete" onclick="deleteUser({{ $user->id }})" title="ลบผู้ใช้" 
                                            style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #ffebee; color: #d32f2f;"
                                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.backgroundColor='#ffcdd2';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='#ffebee';">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>ไม่พบข้อมูลผู้ใช้</h5>
                                    <p>ยังไม่มีผู้ใช้ในระบบ หรือไม่ตรงกับเงื่อนไขการค้นหา</p>
                                    
                                    <!-- Test Action Buttons -->
                                    <div class="mt-3">
                                        <h6>ทดสอบปุ่ม Action:</h6>
                                        <div class="action-buttons" style="display: flex; gap: 4px; align-items: center; justify-content: center;">
                                            <button class="btn-action btn-download" onclick="alert('Test Button 1')" title="ทดสอบ 1" 
                                                    style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #fff3e0; color: #f57c00;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-action btn-download" onclick="alert('Test Button 2')" title="ทดสอบ 2" 
                                                    style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #fff3e0; color: #f57c00;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-action btn-delete" onclick="alert('Test Button 3')" title="ทดสอบ 3" 
                                                    style="width: 32px; height: 32px; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 14px; background-color: #ffebee; color: #d32f2f;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @can('users.create')
                                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="fas fa-plus me-2"></i>เพิ่มผู้ใช้คนแรก
                                    </button>
                                    @endcan
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
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Add User Modal -->
@can('users.create')
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">
                                        <i class="fas fa-user me-1"></i>ชื่อ *
                                    </label>
                                    <input type="text" class="form-control" id="firstName" required placeholder="กรอกชื่อ">
                                </div>
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">
                                        <i class="fas fa-user me-1"></i>นามสกุล *
                                    </label>
                                    <input type="text" class="form-control" id="lastName" required placeholder="กรอกนามสกุล">
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
                                    <label for="roles" class="form-label">
                                        <i class="fas fa-user-shield me-1"></i>บทบาท *
                                    </label>
                                    <select class="form-select" id="roles" multiple required>
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
                                        <option value="inactive">ไม่ใช้งาน</option>
                                        <option value="pending">รอการยืนยัน</option>
                                        <option value="suspended">ระงับการใช้งาน</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>รหัสผ่าน *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" required placeholder="รหัสผ่านอย่างน้อย 8 ตัวอักษร">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock me-1"></i>ยืนยันรหัสผ่าน *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" required placeholder="ยืนยันรหัสผ่าน">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        ข้อมูลเพิ่มเติม
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">รูปโปรไฟล์</label>
                                        <div class="text-center">
                                            <img id="avatarPreview" src="https://ui-avatars.com/api/?name=User&background=6c757d&color=fff" 
                                                 class="rounded-circle mb-2" width="80" height="80">
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('avatar').click()">
                                                    <i class="fas fa-camera me-1"></i>เลือกรูป
                                                </button>
                                                <input type="file" id="avatar" accept="image/*" class="d-none" onchange="previewAvatar(this)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">หมายเหตุ</label>
                                        <textarea class="form-control" id="notes" rows="3" placeholder="หมายเหตุเพิ่มเติม (ถ้ามี)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>เพิ่มผู้ใช้
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Manage User Roles Modal -->
@can('users.roles')
<div class="modal fade" id="manageUserRolesModal" tabindex="-1" aria-labelledby="manageUserRolesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageUserRolesModalLabel">จัดการบทบาทผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="manageUserRolesForm">
                <div class="modal-body">
                    <div id="userInfo" class="mb-3"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">เลือกบทบาท</label>
                            <div id="rolesContainer"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกบทบาท</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
