@extends('backend.layouts.app')

@section('title', 'รายละเอียดบทบาท')
@section('page-title', 'รายละเอียดบทบาท')
@section('page-description', 'ดูข้อมูลรายละเอียดบทบาทในระบบ')

@section('content')
<div class="space-y-6">
    <!-- Role Header -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="w-20 h-20 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-tag text-white text-2xl"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $role->display_name }}</h2>
                    <p class="text-base sm:text-lg text-gray-600 truncate">{{ $role->name }}</p>
                    @if($role->description)
                        <p class="text-sm sm:text-base text-gray-500 mt-1">{{ $role->description }}</p>
                    @endif
                    
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if($role->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                ใช้งาน
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                ไม่ใช้งาน
                            </span>
                        @endif
                        
                        @if($role->is_system)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-cog mr-1"></i>
                                บทบาทระบบ
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-user mr-1"></i>
                                บทบาทกำหนดเอง
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                    <a href="{{ route('backend.roles.edit', $role) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </a>
                    <a href="{{ route('backend.roles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Role Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    ข้อมูลบทบาท
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <span class="font-medium text-gray-700">ชื่อบทบาท:</span>
                    <span class="text-gray-900 block sm:inline">{{ $role->display_name }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">ชื่อระบบ:</span>
                    <span class="text-gray-900 block sm:inline">{{ $role->name }}</span>
                </div>
                @if($role->description)
                    <div>
                        <span class="font-medium text-gray-700">คำอธิบาย:</span>
                        <span class="text-gray-900 block sm:inline">{{ $role->description }}</span>
                    </div>
                @endif
                <div>
                    <span class="font-medium text-gray-700">สถานะ:</span>
                    @if($role->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            ใช้งาน
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            ไม่ใช้งาน
                        </span>
                    @endif
                </div>
                <div>
                    <span class="font-medium text-gray-700">ประเภท:</span>
                    @if($role->is_system)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-cog mr-1"></i>
                            บทบาทระบบ
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-user mr-1"></i>
                            บทบาทกำหนดเอง
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-key mr-2"></i>
                    สิทธิ์การเข้าถึง
                </h3>
            </div>
            <div class="p-6">
                @if($role->permissions->count() > 0)
                    <div class="space-y-2">
                        @foreach($role->permissions as $permission)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $permission->display_name }}</span>
                                    @if($permission->description)
                                        <p class="text-sm text-gray-500">{{ $permission->description }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-key text-4xl mb-4"></i>
                        <div>ไม่มีสิทธิ์การเข้าถึง</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-users mr-2"></i>
                    ผู้ใช้งาน
                </h3>
            </div>
            <div class="p-6">
                @if($role->users->count() > 0)
                    <div class="space-y-2">
                        @foreach($role->users->take(5) as $user)
                            <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-white text-sm"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        ใช้งาน
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        ไม่ใช้งาน
                                    </span>
                                @endif
                            </div>
                        @endforeach
                        @if($role->users->count() > 5)
                            <div class="text-center pt-2">
                                <span class="text-sm text-gray-500">และอีก {{ $role->users->count() - 5 }} คน</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <div>ไม่มีผู้ใช้งาน</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-bolt mr-2"></i>
                การดำเนินการด่วน
            </h3>
        </div>
        <div class="p-6 space-y-2">
            <a href="{{ route('backend.roles.edit', $role) }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-center">
                <i class="fas fa-edit mr-2"></i>
                แก้ไขข้อมูล
            </a>
            
            @if($role->canBeDeleted())
                <button onclick="deleteRole({{ $role->id }})" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-trash mr-2"></i>
                    ลบบทบาท
                </button>
            @endif
            
            @if(!$role->is_system)
                @if($role->is_active)
                    <button onclick="toggleRoleStatus({{ $role->id }}, false)" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                        <i class="fas fa-user-times mr-2"></i>
                        ปิดใช้งาน
                    </button>
                @else
                    <button onclick="toggleRoleStatus({{ $role->id }}, true)" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-user-check mr-2"></i>
                        เปิดใช้งาน
                    </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-info-circle mr-2"></i>
                ข้อมูลบัญชี
            </h3>
        </div>
        <div class="p-6 space-y-3">
            <div>
                <span class="font-medium text-gray-700">สร้างเมื่อ:</span>
                <span class="text-gray-900 block sm:inline">{{ $role->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">อัปเดตล่าสุด:</span>
                <span class="text-gray-900 block sm:inline">{{ $role->updated_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">ผู้ใช้งาน:</span>
                <span class="text-gray-900 block sm:inline">{{ $role->users->count() }} คน</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">สิทธิ์:</span>
                <span class="text-gray-900 block sm:inline">{{ $role->permissions->count() }} รายการ</span>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// Delete role function
function deleteRole(roleId) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณจะไม่สามารถย้อนกลับได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/backend/roles/${roleId}`;
            form.submit();
        }
    });
}

// Toggle role status function
function toggleRoleStatus(roleId, status) {
    const action = status ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    const icon = status ? 'success' : 'warning';
    
    Swal.fire({
        title: `คุณแน่ใจหรือไม่?`,
        text: `คุณต้องการ${action}บทบาทนี้`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: status ? '#10b981' : '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `ใช่, ${action}!`,
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/backend/roles/${roleId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถอัปเดตสถานะได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            });
        }
    });
}
</script>
@endpush
