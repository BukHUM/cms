<!-- Roles Tab -->
<div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="action-buttons">
                    @can('roles.create')
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>
                        เพิ่มบทบาทใหม่
                    </button>
                    @endcan
                </div>
                <div class="search-container">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="ค้นหาบทบาท..." id="rolesSearchInput">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="table-responsive">
        <table class="table table-bordered" id="rolesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อบทบาท</th>
                    <th>Slug</th>
                    <th>คำอธิบาย</th>
                    <th>สี</th>
                    <th>สถานะ</th>
                    <th>จำนวนสิทธิ์</th>
                    <th>จำนวนผู้ใช้</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2"></div>
                            <div>
                                <div class="fw-bold">{{ $role->name }}</div>
                                <small class="text-muted">ลำดับ: {{ $role->sort_order }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $role->slug }}</code></td>
                    <td>{{ $role->description ?: '-' }}</td>
                    <td>
                        <span class="role-badge">
                            {{ $role->color }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $role->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $role->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $role->permissions->count() }}</span>
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $role->users->count() }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            @can('roles.update')
                            <button class="btn btn-sm btn-outline-primary" onclick="editRole({{ $role->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endcan
                            @can('roles.view')
                            <button class="btn btn-sm btn-outline-info" onclick="viewRole({{ $role->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            @endcan
                            @can('roles.permissions')
                            <button class="btn btn-sm btn-outline-warning" onclick="managePermissions({{ $role->id }})">
                                <i class="fas fa-key"></i>
                            </button>
                            @endcan
                            @can('roles.delete')
                            @if($role->slug !== 'super-admin')
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRole({{ $role->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-user-shield fa-2x mb-2"></i><br>
                        ไม่พบข้อมูลบทบาท
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($roles->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $roles->links() }}
    </div>
    @endif
</div>

<!-- Add Role Modal -->
@can('roles.create')
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มบทบาทใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addRoleForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="roleName" class="form-label">ชื่อบทบาท *</label>
                            <input type="text" class="form-control" id="roleName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="roleSlug" class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="roleSlug" required>
                        </div>
                        <div class="col-12">
                            <label for="roleDescription" class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" id="roleDescription" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="roleColor" class="form-label">สี *</label>
                            <input type="color" class="form-control form-control-color" id="roleColor" value="#6c757d" required>
                        </div>
                        <div class="col-md-6">
                            <label for="roleSortOrder" class="form-label">ลำดับการแสดงผล</label>
                            <input type="number" class="form-control" id="roleSortOrder" value="0" min="0">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="roleIsActive" checked>
                                <label class="form-check-label" for="roleIsActive">
                                    ใช้งาน
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">สิทธิ์</label>
                            <div id="permissionsContainer">
                                @foreach(\App\Models\Permission::active()->ordered()->get()->groupBy('group') as $group => $permissions)
                                <div class="permission-group">
                                    <h6>{{ $group }}</h6>
                                    @foreach($permissions as $permission)
                                    <div class="permission-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                        <label for="perm_{{ $permission->id }}" class="form-check-label">
                                            {{ $permission->name }}
                                            @if($permission->description)
                                            <small class="text-muted d-block">{{ $permission->description }}</small>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เพิ่มบทบาท</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Manage Permissions Modal -->
@can('roles.permissions')
<div class="modal fade" id="managePermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">จัดการสิทธิ์บทบาท</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="managePermissionsForm">
                <div class="modal-body">
                    <div id="roleInfo" class="mb-3"></div>
                    <div id="permissionsManageContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกสิทธิ์</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
