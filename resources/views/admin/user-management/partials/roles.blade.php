<!-- Roles Tab -->
<div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
    <!-- Action Buttons -->
    <div class="settings-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="action-buttons">
                            @can('roles.create')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                <i class="fas fa-plus me-2"></i>
                                เพิ่มบทบาทใหม่
                            </button>
                            @endcan
                        </div>
                        <div class="search-container">
                            <div class="input-group" style="min-width: 250px;">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="ค้นหาบทบาท..." id="rolesSearchInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="settings-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>
                    รายชื่อบทบาท
                    <span class="badge bg-primary ms-2">{{ $roles->count() }}</span>
                </h6>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="rolesTable">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>ชื่อบทบาท</th>
                            <th>Slug</th>
                            <th>คำอธิบาย</th>
                            <th>สี</th>
                            <th>สถานะ</th>
                            <th>จำนวนสิทธิ์</th>
                            <th>จำนวนผู้ใช้</th>
                            <th width="120">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; background-color: {{ $role->color }}20; color: {{ $role->color }};">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $role->name }}</div>
                                        <small class="text-muted">ลำดับ: {{ $role->sort_order }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-muted">{{ $role->slug }}</code></td>
                            <td>{{ $role->description ?: '-' }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $role->color }}; color: white;">
                                    {{ $role->color }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $role->is_active ? 'bg-success' : 'bg-secondary' }}">
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
                                    <button class="btn btn-outline-primary btn-sm" onclick="editRole({{ $role->id }})" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('roles.view')
                                    <button class="btn btn-outline-info btn-sm" onclick="viewRole({{ $role->id }})" title="ดูรายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @endcan
                                    @can('roles.permissions')
                                    <button class="btn btn-outline-warning btn-sm" onclick="managePermissions({{ $role->id }})" title="จัดการสิทธิ์">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    @endcan
                                    @can('roles.delete')
                                    @if($role->slug !== 'super-admin')
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteRole({{ $role->id }})" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-shield fa-3x mb-3"></i>
                                    <h5>ไม่พบข้อมูลบทบาท</h5>
                                    <p>ยังไม่มีบทบาทในระบบ</p>
                                    @can('roles.create')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                        <i class="fas fa-plus me-2"></i>เพิ่มบทบาทแรก
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
    @if($roles->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $roles->links() }}
    </div>
    @endif
</div>

<!-- Add Role Modal -->
@can('roles.create')
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">เพิ่มบทบาทใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <div class="permission-group mb-3">
                                    <h6 class="text-primary">{{ $group }}</h6>
                                    @foreach($permissions as $permission)
                                    <div class="permission-item form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
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
<div class="modal fade" id="managePermissionsModal" tabindex="-1" aria-labelledby="managePermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="managePermissionsModalLabel">จัดการสิทธิ์บทบาท</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
