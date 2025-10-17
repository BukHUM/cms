@extends('backend.layouts.app')

@section('title', 'จัดการสิทธิ์การเข้าถึง')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="page-title">จัดการสิทธิ์การเข้าถึง</h1>
            <p class="page-description">จัดการสิทธิ์การเข้าถึงระบบ</p>
        </div>
        <div>
            <a href="{{ route('backend.permissions.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> เพิ่มสิทธิ์ใหม่
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.permissions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label for="search" class="form-label">ค้นหา</label>
                    <input type="text" class="form-input" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="ชื่อสิทธิ์, ชื่อแสดง, คำอธิบาย">
                </div>
                <div>
                    <label for="group" class="form-label">กลุ่ม</label>
                    <select class="form-input" id="group" name="group">
                        <option value="">ทั้งหมด</option>
                        @foreach($groups as $group)
                            <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                                {{ $group }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="form-label">สถานะ</label>
                    <select class="form-input" id="status" name="status">
                        <option value="">ทั้งหมด</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>เปิดใช้งาน</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ปิดใช้งาน</option>
                    </select>
                </div>
                <div>
                    <label for="sort_by" class="form-label">เรียงตาม</label>
                    <select class="form-input" id="sort_by" name="sort_by">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>ชื่อสิทธิ์</option>
                        <option value="display_name" {{ request('sort_by') == 'display_name' ? 'selected' : '' }}>ชื่อแสดง</option>
                        <option value="group" {{ request('sort_by') == 'group' ? 'selected' : '' }}>กลุ่ม</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>วันที่สร้าง</option>
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="form-label">ลำดับ</label>
                    <select class="form-input" id="sort_order" name="sort_order">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>น้อยไปมาก</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>มากไปน้อย</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-search mr-2"></i> ค้นหา
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form id="bulkForm" method="POST" action="{{ route('backend.permissions.bulk-action') }}">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h3 class="card-title">
                        รายการสิทธิ์การเข้าถึง ({{ $permissions->total() }} รายการ)
                    </h3>
                    <div class="flex gap-2">
                        <select name="action" class="form-input" style="width: auto;" required>
                            <option value="">เลือกการดำเนินการ</option>
                            <option value="activate">เปิดใช้งาน</option>
                            <option value="deactivate">ปิดใช้งาน</option>
                            <option value="delete">ลบ</option>
                        </select>
                        <button type="submit" class="btn-secondary" onclick="return confirmBulkAction()">
                            <i class="fas fa-check mr-2"></i> ดำเนินการ
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($permissions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="table-header">
                                <tr>
                                    <th class="table-cell w-12">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th class="table-cell">ชื่อสิทธิ์</th>
                                    <th class="table-cell">ชื่อแสดง</th>
                                    <th class="table-cell">กลุ่ม</th>
                                    <th class="table-cell">คำอธิบาย</th>
                                    <th class="table-cell">สถานะ</th>
                                    <th class="table-cell">วันที่สร้าง</th>
                                    <th class="table-cell w-40">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr class="table-row">
                                        <td class="table-cell">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                   class="form-check-input permission-checkbox">
                                        </td>
                                        <td class="table-cell">
                                            <code class="text-primary font-mono">{{ $permission->name }}</code>
                                        </td>
                                        <td class="table-cell font-medium">{{ $permission->display_name }}</td>
                                        <td class="table-cell">
                                            <span class="badge badge-secondary">{{ $permission->group }}</span>
                                        </td>
                                        <td class="table-cell">
                                            <span class="text-gray-600" title="{{ $permission->description }}">
                                                {{ Str::limit($permission->description, 50) }}
                                            </span>
                                        </td>
                                        <td class="table-cell">
                                            @if($permission->is_active)
                                                <span class="badge badge-success">เปิดใช้งาน</span>
                                            @else
                                                <span class="badge badge-secondary">ปิดใช้งาน</span>
                                            @endif
                                        </td>
                                        <td class="table-cell">
                                            <span class="text-sm text-gray-500">
                                                {{ $permission->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="table-cell">
                                            <div class="flex gap-1">
                                                <a href="{{ route('backend.permissions.show', $permission) }}" 
                                                   class="btn-sm bg-blue-100 text-blue-700 hover:bg-blue-200" title="ดูรายละเอียด">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('backend.permissions.edit', $permission) }}" 
                                                   class="btn-sm bg-yellow-100 text-yellow-700 hover:bg-yellow-200" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('backend.permissions.toggle-status', $permission) }}" 
                                                      class="inline" onsubmit="return confirmToggleStatus('{{ $permission->name }}')">
                                                    @csrf
                                                    <button type="submit" class="btn-sm {{ $permission->is_active ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}" 
                                                            title="{{ $permission->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                                                        <i class="fas fa-{{ $permission->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('backend.permissions.destroy', $permission) }}" 
                                                      class="inline" onsubmit="return confirmDelete('{{ $permission->name }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-sm bg-red-100 text-red-700 hover:bg-red-200" title="ลบ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mb-4">
                            <i class="fas fa-shield-alt text-6xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่พบข้อมูลสิทธิ์การเข้าถึง</h3>
                        <p class="text-gray-500 mb-6">ลองปรับเปลี่ยนเงื่อนไขการค้นหาหรือเพิ่มสิทธิ์ใหม่</p>
                        <a href="{{ route('backend.permissions.create') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> เพิ่มสิทธิ์ใหม่
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <!-- Pagination -->
    @if($permissions->hasPages())
        <div class="flex justify-center mt-6">
            {{ $permissions->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Individual checkbox change
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === permissionCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
        });
    });
});

function confirmBulkAction() {
    const checkedBoxes = document.querySelectorAll('.permission-checkbox:checked');
    const action = document.querySelector('select[name="action"]').value;
    
    if (checkedBoxes.length === 0) {
        Swal.fire({
            title: 'คำเตือน',
            text: 'กรุณาเลือกสิทธิ์ที่ต้องการดำเนินการ',
            icon: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return false;
    }
    
    if (!action) {
        Swal.fire({
            title: 'คำเตือน',
            text: 'กรุณาเลือกการดำเนินการ',
            icon: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return false;
    }
    
    const actionText = {
        'activate': 'เปิดใช้งาน',
        'deactivate': 'ปิดใช้งาน',
        'delete': 'ลบ'
    }[action];
    
    return Swal.fire({
        title: 'ยืนยันการดำเนินการ',
        text: `คุณต้องการ${actionText}สิทธิ์ที่เลือก ${checkedBoxes.length} รายการใช่หรือไม่?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        return result.isConfirmed;
    });
}

function confirmToggleStatus(permissionName) {
    return Swal.fire({
        title: 'ยืนยันการเปลี่ยนสถานะ',
        text: `คุณต้องการเปลี่ยนสถานะของสิทธิ์ "${permissionName}" ใช่หรือไม่?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        return result.isConfirmed;
    });
}

function confirmDelete(permissionName) {
    return Swal.fire({
        title: 'ยืนยันการลบ',
        text: `คุณต้องการลบสิทธิ์ "${permissionName}" ใช่หรือไม่?\n\nการดำเนินการนี้ไม่สามารถยกเลิกได้`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        return result.isConfirmed;
    });
}
</script>
@endpush
@endsection
