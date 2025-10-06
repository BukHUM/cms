<!-- Users Tab -->
<div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">

    <!-- Action Buttons and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="action-buttons">
                                @can('users.create')
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus me-1"></i>
                                    เพิ่มผู้ใช้ใหม่
                                </button>
                                @endcan
                                @can('users.view')
                                <button class="btn btn-success btn-sm" onclick="exportUsers()">
                                    <i class="fas fa-download me-1"></i>
                                    ส่งออกข้อมูล
                                </button>
                                @endcan
                                <button class="btn btn-info btn-sm" onclick="refreshUsers()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    รีเฟรช
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="flex-grow-1">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" placeholder="ค้นหาผู้ใช้..." id="usersSearchInput">
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter text-primary me-1"></i>สถานะ
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByStatus(''); return false;">
                                            <i class="fas fa-list text-secondary me-2"></i>ทุกสถานะ
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByStatus('active'); return false;">
                                            <i class="fas fa-check-circle text-success me-2"></i>ใช้งาน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByStatus('inactive'); return false;">
                                            <i class="fas fa-times-circle text-danger me-2"></i>ไม่ใช้งาน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByStatus('pending'); return false;">
                                            <i class="fas fa-clock text-warning me-2"></i>รอการยืนยัน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByStatus('suspended'); return false;">
                                            <i class="fas fa-ban text-danger me-2"></i>ระงับการใช้งาน
                                        </a></li>
                                    </ul>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-shield text-primary me-1"></i>บทบาท
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByRole(''); return false;">
                                            <i class="fas fa-list text-secondary me-2"></i>ทุกบทบาท
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        @foreach(\App\Models\Role::active()->ordered()->get() as $role)
                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); filterByRole('{{ $role->id }}'); return false;">
                                            <span class="role-badge me-2" style="background-color: {{ $role->color }}; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem;">{{ $role->name }}</span>
                                            {{ $role->name }}
                                        </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-users me-2"></i>
                รายชื่อผู้ใช้
                <span class="badge bg-primary ms-2" id="userCount">{{ $users->count() }}</span>
            </h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleViewMode()" id="viewModeBtn">
                    <i class="fas fa-th-large"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-hover mb-0" id="usersTable" style="min-width: 800px;">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>ผู้ใช้</th>
                            <th>อีเมล</th>
                            <th>บทบาท</th>
                            <th width="120">สถานะ</th>
                            <th width="120">วันที่สมัคร</th>
                            <th width="150">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="user-row" data-user-id="{{ $user->id }}" data-status="{{ $user->status }}">
                            <td>
                                <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6c757d&color=fff' }}" 
                                             class="rounded-circle" alt="User" style="width: 45px; height: 45px; object-fit: cover;">
                                        @if($user->status === 'active')
                                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success" style="width: 12px; height: 12px;"></span>
                                        @elseif($user->status === 'pending')
                                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning" style="width: 12px; height: 12px;"></span>
                                        @elseif($user->status === 'suspended')
                                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-danger" style="width: 12px; height: 12px;"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <span>{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                    <i class="fas fa-check-circle text-success ms-2" title="ยืนยันอีเมลแล้ว"></i>
                                    @else
                                    <i class="fas fa-exclamation-circle text-warning ms-2" title="ยังไม่ยืนยันอีเมล"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <span class="role-badge" style="background-color: {{ $role->color }};" title="{{ $role->description }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm status-badge {{ $user->getStatusBadgeClass() }} dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        {{ $user->getStatusDisplayName() }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'active')">
                                            <i class="fas fa-check text-success me-2"></i>ใช้งาน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'inactive')">
                                            <i class="fas fa-times text-danger me-2"></i>ไม่ใช้งาน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'pending')">
                                            <i class="fas fa-clock text-warning me-2"></i>รอการยืนยัน
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateUserStatus({{ $user->id }}, 'suspended')">
                                            <i class="fas fa-ban text-danger me-2"></i>ระงับการใช้งาน
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at->format('d/m/Y') }}<br>
                                    <i class="fas fa-clock me-1"></i>{{ $user->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('users.view')
                                    <button class="btn btn-sm btn-outline-info" onclick="viewUser({{ $user->id }})" title="ดูรายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @endcan
                                    @can('users.update')
                                    <button class="btn btn-sm btn-outline-primary" onclick="editUser({{ $user->id }})" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('users.roles')
                                    <button class="btn btn-sm btn-outline-warning" onclick="manageUserRoles({{ $user->id }})" title="จัดการบทบาท">
                                        <i class="fas fa-user-shield"></i>
                                    </button>
                                    @endcan
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('users.view')
                                            <li><a class="dropdown-item" href="#" onclick="viewUser({{ $user->id }})">
                                                <i class="fas fa-eye me-2"></i>ดูรายละเอียด
                                            </a></li>
                                            @endcan
                                            @can('users.update')
                                            <li><a class="dropdown-item" href="#" onclick="editUser({{ $user->id }})">
                                                <i class="fas fa-edit me-2"></i>แก้ไขข้อมูล
                                            </a></li>
                                            @endcan
                                            @can('users.roles')
                                            <li><a class="dropdown-item" href="#" onclick="manageUserRoles({{ $user->id }})">
                                                <i class="fas fa-user-shield me-2"></i>จัดการบทบาท
                                            </a></li>
                                            @endcan
                                            @can('users.delete')
                                            @if($user->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }})">
                                                <i class="fas fa-trash me-2"></i>ลบผู้ใช้
                                            </a></li>
                                            @endif
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                    <h5>ไม่พบข้อมูลผู้ใช้</h5>
                                    <p class="text-muted">ยังไม่มีผู้ใช้ในระบบ หรือไม่ตรงกับเงื่อนไขการค้นหา</p>
                                    @can('users.create')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
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
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Add User Modal -->
@can('users.create')
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    เพิ่มผู้ใช้ใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row">
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
                            <div class="card">
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
                                                 class="rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('avatar').click()">
                                                    <i class="fas fa-camera me-1"></i>เลือกรูป
                                                </button>
                                                <input type="file" id="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
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
<div class="modal fade" id="manageUserRolesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">จัดการบทบาทผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
