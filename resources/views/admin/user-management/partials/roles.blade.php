<!-- Roles Tab -->
<div id="roles">
    <!-- Action Buttons -->
    <div>
        <div>
            <div>
                <div>
                    @can('roles.create')
                    <button data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i fas fa-plus"></i>
                        เพิ่มบทบาทใหม่
                    </button>
                    @endcan
                </div>
                <div>
                    <div>
                        <input type="text" placeholder="ค้นหาบทบาท..." id="rolesSearchInput">
                        <button type="button">
                            <i fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div>
        <table id="rolesTable">
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
                        <div d-flex align-items-center">
                            <div me-2"></div>
                            <div>
                                <div fw-bold">{{ $role->name }}</div>
                                <small text-muted">ลำดับ: {{ $role->sort_order }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $role->slug }}</code></td>
                    <td>{{ $role->description ?: '-' }}</td>
                    <td>
                        <span role-badge">
                            {{ $role->color }}
                        </span>
                    </td>
                    <td>
                        <span status-badge {{ $role->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $role->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                        </span>
                    </td>
                    <td>
                        <span badge bg-info">{{ $role->permissions->count() }}</span>
                    </td>
                    <td>
                        <span badge bg-primary">{{ $role->users->count() }}</span>
                    </td>
                    <td>
                        <div btn-group" role="group">
                            @can('roles.update')
                            <button btn btn-sm btn-outline-primary" onclick="editRole({{ $role->id }})">
                                <i fas fa-edit"></i>
                            </button>
                            @endcan
                            @can('roles.view')
                            <button btn btn-sm btn-outline-info" onclick="viewRole({{ $role->id }})">
                                <i fas fa-eye"></i>
                            </button>
                            @endcan
                            @can('roles.permissions')
                            <button btn btn-sm btn-outline-warning" onclick="managePermissions({{ $role->id }})">
                                <i fas fa-key"></i>
                            </button>
                            @endcan
                            @can('roles.delete')
                            @if($role->slug !== 'super-admin')
                            <button btn btn-sm btn-outline-danger" onclick="deleteRole({{ $role->id }})">
                                <i fas fa-trash"></i>
                            </button>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" text-center text-muted py-4">
                        <i fas fa-user-shield fa-2x mb-2"></i><br>
                        ไม่พบข้อมูลบทบาท
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($roles->hasPages())
    <div d-flex justify-content-center mt-3">
        {{ $roles->links() }}
    </div>
    @endif
</div>

<!-- Add Role Modal -->
@can('roles.create')
<div modal fade" id="addRoleModal" tabindex="-1">
    <div modal-dialog modal-lg">
        <div modal-content">
            <div modal-header">
                <h5 modal-title">เพิ่มบทบาทใหม่</h5>
                <button type="button" btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addRoleForm">
                <div modal-body">
                    <div row g-3">
                        <div col-md-6">
                            <label for="roleName" form-label">ชื่อบทบาท *</label>
                            <input type="text" form-control" id="roleName" required>
                        </div>
                        <div col-md-6">
                            <label for="roleSlug" form-label">Slug *</label>
                            <input type="text" form-control" id="roleSlug" required>
                        </div>
                        <div col-12">
                            <label for="roleDescription" form-label">คำอธิบาย</label>
                            <textarea form-control" id="roleDescription" rows="3"></textarea>
                        </div>
                        <div col-md-6">
                            <label for="roleColor" form-label">สี *</label>
                            <input type="color" form-control form-control-color" id="roleColor" value="#6c757d" required>
                        </div>
                        <div col-md-6">
                            <label for="roleSortOrder" form-label">ลำดับการแสดงผล</label>
                            <input type="number" form-control" id="roleSortOrder" value="0" min="0">
                        </div>
                        <div col-12">
                            <div form-check">
                                <input form-check-input" type="checkbox" id="roleIsActive" checked>
                                <label form-check-label" for="roleIsActive">
                                    ใช้งาน
                                </label>
                            </div>
                        </div>
                        <div col-12">
                            <label form-label">สิทธิ์</label>
                            <div id="permissionsContainer">
                                @foreach(\App\Models\Permission::active()->ordered()->get()->groupBy('group') as $group => $permissions)
                                <div permission-group">
                                    <h6>{{ $group }}</h6>
                                    @foreach($permissions as $permission)
                                    <div permission-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                        <label for="perm_{{ $permission->id }}" form-check-label">
                                            {{ $permission->name }}
                                            @if($permission->description)
                                            <small text-muted d-block">{{ $permission->description }}</small>
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
                <div modal-footer">
                    <button type="button" btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" btn btn-primary">เพิ่มบทบาท</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Manage Permissions Modal -->
@can('roles.permissions')
<div modal fade" id="managePermissionsModal" tabindex="-1">
    <div modal-dialog modal-xl">
        <div modal-content">
            <div modal-header">
                <h5 modal-title">จัดการสิทธิ์บทบาท</h5>
                <button type="button" btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="managePermissionsForm">
                <div modal-body">
                    <div id="roleInfo" mb-3"></div>
                    <div id="permissionsManageContainer"></div>
                </div>
                <div modal-footer">
                    <button type="button" btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" btn btn-primary">บันทึกสิทธิ์</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
