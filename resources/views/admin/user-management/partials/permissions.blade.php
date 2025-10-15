<!-- Permissions Tab -->
<div id="permissions">
    <!-- Action Buttons -->
    <div>
        <div>
            <div>
                <div>
                    @can('permissions.create')
                    <button data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        <i fas fa-plus"></i>
                        เพิ่มสิทธิ์ใหม่
                    </button>
                    @endcan
                </div>
                <div>
                    <div>
                        <input type="text" placeholder="ค้นหาสิทธิ์..." id="permissionsSearchInput">
                        <button type="button">
                            <i fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Table -->
    <div>
        <table id="permissionsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อสิทธิ์</th>
                    <th>Slug</th>
                    <th>กลุ่ม</th>
                    <th>การกระทำ</th>
                    <th>ทรัพยากร</th>
                    <th>สถานะ</th>
                    <th>จำนวนบทบาท</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>
                        <div>
                            <div fw-bold">{{ $permission->name }}</div>
                            @if($permission->description)
                            <small text-muted">{{ $permission->description }}</small>
                            @endif
                        </div>
                    </td>
                    <td><code>{{ $permission->slug }}</code></td>
                    <td>
                        <span group-badge">{{ $permission->group }}</span>
                    </td>
                    <td>
                        @if($permission->action)
                        <span action-badge">{{ $permission->action }}</span>
                        @else
                        <span text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($permission->resource)
                        <span resource-badge">{{ $permission->resource }}</span>
                        @else
                        <span text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span status-badge {{ $permission->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $permission->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                        </span>
                    </td>
                    <td>
                        <span badge bg-primary">{{ $permission->roles->count() }}</span>
                    </td>
                    <td>
                        <div btn-group" role="group">
                            @can('permissions.update')
                            <button btn btn-sm btn-outline-primary" onclick="editPermission({{ $permission->id }})">
                                <i fas fa-edit"></i>
                            </button>
                            @endcan
                            @can('permissions.view')
                            <button btn btn-sm btn-outline-info" onclick="viewPermission({{ $permission->id }})">
                                <i fas fa-eye"></i>
                            </button>
                            @endcan
                            @can('permissions.delete')
                            @if($permission->slug !== 'super-admin')
                            <button btn btn-sm btn-outline-danger" onclick="deletePermission({{ $permission->id }})">
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
                        <i fas fa-key fa-2x mb-2"></i><br>
                        ไม่พบข้อมูลสิทธิ์
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($permissions->hasPages())
    <div d-flex justify-content-center mt-3">
        {{ $permissions->links() }}
    </div>
    @endif
</div>

<!-- Add Permission Modal -->
@can('permissions.create')
<div modal fade" id="addPermissionModal" tabindex="-1">
    <div modal-dialog modal-lg">
        <div modal-content">
            <div modal-header">
                <h5 modal-title">เพิ่มสิทธิ์ใหม่</h5>
                <button type="button" btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPermissionForm">
                <div modal-body">
                    <div row g-3">
                        <div col-md-6">
                            <label for="permissionName" form-label">ชื่อสิทธิ์ *</label>
                            <input type="text" form-control" id="permissionName" required>
                        </div>
                        <div col-md-6">
                            <label for="permissionSlug" form-label">Slug *</label>
                            <input type="text" form-control" id="permissionSlug" required>
                        </div>
                        <div col-12">
                            <label for="permissionDescription" form-label">คำอธิบาย</label>
                            <textarea form-control" id="permissionDescription" rows="3"></textarea>
                        </div>
                        <div col-md-4">
                            <label for="permissionGroup" form-label">กลุ่ม *</label>
                            <select form-select" id="permissionGroup" required>
                                <option value="">เลือกกลุ่ม</option>
                                <option value="users">ผู้ใช้</option>
                                <option value="roles">บทบาท</option>
                                <option value="permissions">สิทธิ์</option>
                                <option value="settings">การตั้งค่า</option>
                                <option value="system">ระบบ</option>
                                <option value="general">ทั่วไป</option>
                            </select>
                        </div>
                        <div col-md-4">
                            <label for="permissionAction" form-label">การกระทำ</label>
                            <select form-select" id="permissionAction">
                                <option value="">เลือกการกระทำ</option>
                                <option value="create">สร้าง</option>
                                <option value="read">อ่าน</option>
                                <option value="update">แก้ไข</option>
                                <option value="delete">ลบ</option>
                                <option value="view">ดู</option>
                                <option value="export">ส่งออก</option>
                                <option value="import">นำเข้า</option>
                            </select>
                        </div>
                        <div col-md-4">
                            <label for="permissionResource" form-label">ทรัพยากร</label>
                            <input type="text" form-control" id="permissionResource" placeholder="เช่น user, role, setting">
                        </div>
                        <div col-md-6">
                            <label for="permissionSortOrder" form-label">ลำดับการแสดงผล</label>
                            <input type="number" form-control" id="permissionSortOrder" value="0" min="0">
                        </div>
                        <div col-md-6">
                            <div form-check mt-4">
                                <input form-check-input" type="checkbox" id="permissionIsActive" checked>
                                <label form-check-label" for="permissionIsActive">
                                    ใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div modal-footer">
                    <button type="button" btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" btn btn-primary">เพิ่มสิทธิ์</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
