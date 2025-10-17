<!-- Permissions Tab -->
<div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
    <!-- Action Buttons -->
    <div class="settings-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="action-buttons">
                            @can('permissions.create')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                <i class="fas fa-plus me-2"></i>
                                เพิ่มสิทธิ์ใหม่
                            </button>
                            @endcan
                        </div>
                        <div class="search-container">
                            <div class="input-group" style="min-width: 250px;">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="ค้นหาสิทธิ์..." id="permissionsSearchInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Table -->
    <div class="settings-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-key me-2"></i>
                    รายชื่อสิทธิ์
                    <span class="badge bg-primary ms-2">{{ $permissions->count() }}</span>
                </h6>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="permissionsTable">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>ชื่อสิทธิ์</th>
                            <th>Slug</th>
                            <th>กลุ่ม</th>
                            <th>การกระทำ</th>
                            <th>ทรัพยากร</th>
                            <th>สถานะ</th>
                            <th>จำนวนบทบาท</th>
                            <th width="120">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $permission->name }}</div>
                                    @if($permission->description)
                                    <small class="text-muted">{{ $permission->description }}</small>
                                    @endif
                                </div>
                            </td>
                            <td><code class="text-muted">{{ $permission->slug }}</code></td>
                            <td>
                                <span class="badge bg-secondary">{{ $permission->group }}</span>
                            </td>
                            <td>
                                @if($permission->action)
                                <span class="badge bg-info">{{ $permission->action }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($permission->resource)
                                <span class="badge bg-warning">{{ $permission->resource }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $permission->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $permission->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $permission->roles->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('permissions.update')
                                    <button class="btn btn-outline-primary btn-sm" onclick="editPermission({{ $permission->id }})" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('permissions.view')
                                    <button class="btn btn-outline-info btn-sm" onclick="viewPermission({{ $permission->id }})" title="ดูรายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @endcan
                                    @can('permissions.delete')
                                    @if($permission->slug !== 'super-admin')
                                    <button class="btn btn-outline-danger btn-sm" onclick="deletePermission({{ $permission->id }})" title="ลบ">
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
                                    <i class="fas fa-key fa-3x mb-3"></i>
                                    <h5>ไม่พบข้อมูลสิทธิ์</h5>
                                    <p>ยังไม่มีสิทธิ์ในระบบ</p>
                                    @can('permissions.create')
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                        <i class="fas fa-plus me-2"></i>เพิ่มสิทธิ์แรก
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
    @if($permissions->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $permissions->links() }}
    </div>
    @endif
</div>

<!-- Add Permission Modal -->
@can('permissions.create')
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPermissionModalLabel">เพิ่มสิทธิ์ใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPermissionForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="permissionName" class="form-label">ชื่อสิทธิ์ *</label>
                            <input type="text" class="form-control" id="permissionName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="permissionSlug" class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="permissionSlug" required>
                        </div>
                        <div class="col-12">
                            <label for="permissionDescription" class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" id="permissionDescription" rows="3"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="permissionGroup" class="form-label">กลุ่ม *</label>
                            <select class="form-select" id="permissionGroup" required>
                                <option value="">เลือกกลุ่ม</option>
                                <option value="users">ผู้ใช้</option>
                                <option value="roles">บทบาท</option>
                                <option value="permissions">สิทธิ์</option>
                                <option value="settings">การตั้งค่า</option>
                                <option value="system">ระบบ</option>
                                <option value="general">ทั่วไป</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="permissionAction" class="form-label">การกระทำ</label>
                            <select class="form-select" id="permissionAction">
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
                        <div class="col-md-4">
                            <label for="permissionResource" class="form-label">ทรัพยากร</label>
                            <input type="text" class="form-control" id="permissionResource" placeholder="เช่น user, role, setting">
                        </div>
                        <div class="col-md-6">
                            <label for="permissionSortOrder" class="form-label">ลำดับการแสดงผล</label>
                            <input type="number" class="form-control" id="permissionSortOrder" value="0" min="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="permissionIsActive" checked>
                                <label class="form-check-label" for="permissionIsActive">
                                    ใช้งาน
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เพิ่มสิทธิ์</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
