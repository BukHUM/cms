@extends('backend.layouts.app')

@section('title', 'รายละเอียดสิทธิ์การเข้าถึง')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="page-title">รายละเอียดสิทธิ์การเข้าถึง</h1>
            <p class="page-description">{{ $permission->display_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('backend.permissions.edit', $permission) }}" class="btn-warning">
                <i class="fas fa-edit mr-2"></i> แก้ไข
            </a>
            <a href="{{ route('backend.permissions.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> กลับ
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Permission Details -->
        <div class="lg:col-span-2">
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">ข้อมูลสิทธิ์การเข้าถึง</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">ชื่อสิทธิ์</label>
                                <div class="mt-1">
                                    <code class="text-primary font-mono bg-blue-50 px-3 py-2 rounded-md">{{ $permission->name }}</code>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">ชื่อแสดง</label>
                                <div class="mt-1 text-lg font-medium">{{ $permission->display_name }}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">กลุ่ม</label>
                                <div class="mt-1">
                                    <span class="badge badge-secondary text-sm">{{ $permission->group }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">สถานะ</label>
                                <div class="mt-1">
                                    @if($permission->is_active)
                                        <span class="badge badge-success">เปิดใช้งาน</span>
                                    @else
                                        <span class="badge badge-secondary">ปิดใช้งาน</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">วันที่สร้าง</label>
                                <div class="mt-1 text-sm text-gray-500">{{ $permission->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">อัปเดตล่าสุด</label>
                                <div class="mt-1 text-sm text-gray-500">{{ $permission->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($permission->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-600">คำอธิบาย</label>
                            <div class="mt-2 text-gray-700">{{ $permission->description }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Roles using this permission -->
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h3 class="card-title">บทบาทที่ใช้สิทธิ์นี้</h3>
                        <span class="badge badge-primary">{{ $roles->count() }} บทบาท</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($roles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead class="table-header">
                                    <tr>
                                        <th class="table-cell">ชื่อบทบาท</th>
                                        <th class="table-cell">ชื่อแสดง</th>
                                        <th class="table-cell">สถานะ</th>
                                        <th class="table-cell">จำนวนผู้ใช้</th>
                                        <th class="table-cell">วันที่สร้าง</th>
                                        <th class="table-cell">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr class="table-row">
                                            <td class="table-cell">
                                                <code class="text-primary font-mono">{{ $role->name }}</code>
                                            </td>
                                            <td class="table-cell font-medium">{{ $role->display_name }}</td>
                                            <td class="table-cell">
                                                @if($role->is_active)
                                                    <span class="badge badge-success">เปิดใช้งาน</span>
                                                @else
                                                    <span class="badge badge-secondary">ปิดใช้งาน</span>
                                                @endif
                                            </td>
                                            <td class="table-cell">
                                                <span class="badge badge-primary">{{ $role->users->count() }}</span>
                                            </td>
                                            <td class="table-cell">
                                                <span class="text-sm text-gray-500">
                                                    {{ $role->created_at->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="table-cell">
                                                <a href="{{ route('backend.roles.show', $role) }}" 
                                                   class="btn-sm bg-blue-100 text-blue-700 hover:bg-blue-200" title="ดูรายละเอียด">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <i class="fas fa-users text-6xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ยังไม่มีบทบาทที่ใช้สิทธิ์นี้</h3>
                            <p class="text-gray-500 mb-6">สิทธิ์นี้ยังไม่ถูกกำหนดให้กับบทบาทใด</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">การดำเนินการ</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('backend.permissions.edit', $permission) }}" class="btn-warning w-full text-center">
                            <i class="fas fa-edit mr-2"></i> แก้ไขสิทธิ์
                        </a>
                        
                        <form method="POST" action="{{ route('backend.permissions.toggle-status', $permission) }}" 
                              onsubmit="return confirmToggleStatus('{{ $permission->name }}')">
                            @csrf
                            <button type="submit" class="w-full {{ $permission->is_active ? 'btn-secondary' : 'btn-success' }}">
                                <i class="fas fa-{{ $permission->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $permission->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
                            </button>
                        </form>
                        
                        @if($permission->roles->count() == 0)
                            <form method="POST" action="{{ route('backend.permissions.destroy', $permission) }}" 
                                  onsubmit="return confirmDelete('{{ $permission->name }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger w-full">
                                    <i class="fas fa-trash mr-2"></i> ลบสิทธิ์
                                </button>
                            </form>
                        @else
                            <button type="button" class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-md cursor-not-allowed" 
                                    title="ไม่สามารถลบได้ เนื่องจากถูกใช้งานโดยบทบาทอื่น">
                                <i class="fas fa-trash mr-2"></i> ลบสิทธิ์
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Permission Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">สถิติการใช้งาน</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $roles->count() }}</div>
                            <div class="text-sm text-gray-600">บทบาท</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $roles->sum('users_count') }}</div>
                            <div class="text-sm text-gray-600">ผู้ใช้ทั้งหมด</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Permissions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">สิทธิ์ในกลุ่มเดียวกัน</h3>
                </div>
                <div class="card-body">
                    @php
                        $relatedPermissions = \App\Models\Permission::where('group', $permission->group)
                            ->where('id', '!=', $permission->id)
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($relatedPermissions->count() > 0)
                        <div class="space-y-2">
                            @foreach($relatedPermissions as $relatedPermission)
                                <a href="{{ route('backend.permissions.show', $relatedPermission) }}" 
                                   class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $relatedPermission->display_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $relatedPermission->name }}</div>
                                        </div>
                                        <span class="badge {{ $relatedPermission->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $relatedPermission->is_active ? 'เปิด' : 'ปิด' }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                        @if(\App\Models\Permission::where('group', $permission->group)->count() > 6)
                            <div class="text-center mt-4">
                                <a href="{{ route('backend.permissions.index', ['group' => $permission->group]) }}" 
                                   class="btn-secondary">
                                    ดูทั้งหมด
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-info-circle text-2xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">ไม่มีสิทธิ์อื่นในกลุ่มนี้</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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