@extends('backend.layouts.app')

@section('title', 'ข้อมูลผู้ใช้งาน')
@section('page-title', 'ข้อมูลผู้ใช้งาน')
@section('page-description', 'ดูข้อมูลรายละเอียดผู้ใช้งานในระบบ')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- User Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
                    @else
                        <i class="fas fa-user text-white text-2xl"></i>
                    @endif
                </div>
                
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h2>
                    @if($user->full_name)
                        <p class="text-base sm:text-lg text-gray-600 truncate">{{ $user->full_name }}</p>
                    @endif
                    <p class="text-sm sm:text-base text-gray-500 truncate">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap items-center gap-2 mt-2">
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
                        
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                ยืนยันอีเมล์แล้ว
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                ยังไม่ยืนยันอีเมล์
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                    <a href="{{ route('backend.users.edit', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </a>
                    <a href="{{ route('backend.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-user mr-2"></i>
                        ข้อมูลส่วนตัว
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">ชื่อผู้ใช้งาน</label>
                            <p class="text-gray-900 break-words">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">อีเมล์</label>
                            <p class="text-gray-900 break-words">{{ $user->email }}</p>
                        </div>
                        
                        @if($user->first_name)
                            <div>
                                <label class="text-sm font-medium text-gray-500">ชื่อ</label>
                                <p class="text-gray-900">{{ $user->first_name }}</p>
                            </div>
                        @endif
                        
                        @if($user->last_name)
                            <div>
                                <label class="text-sm font-medium text-gray-500">นามสกุล</label>
                                <p class="text-gray-900">{{ $user->last_name }}</p>
                            </div>
                        @endif
                        
                        @if($user->phone)
                            <div>
                                <label class="text-sm font-medium text-gray-500">เบอร์โทรศัพท์</label>
                                <p class="text-gray-900">{{ $user->phone }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">สถานะ</label>
                            <p class="text-gray-900">
                                @if($user->is_active)
                                    <span class="text-green-600">ใช้งาน</span>
                                @else
                                    <span class="text-red-600">ไม่ใช้งาน</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-user-tag mr-2"></i>
                        บทบาทและสิทธิ์
                    </h3>
                </div>
                <div class="p-6">
                    @if($user->roles->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->roles as $role)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $role->display_name }}</h4>
                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                    </div>
                                    @if($role->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ $role->description }}</p>
                                    @endif
                                    
                                    @if($role->permissions->count() > 0)
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-700 mb-2">สิทธิ์การเข้าถึง:</h5>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($role->permissions as $permission)
                                                    <span class="badge badge-secondary">{{ $permission->display_name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-tag text-4xl mb-4"></i>
                            <p>ผู้ใช้งานนี้ยังไม่มีบทบาทที่กำหนด</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
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
                        <label class="text-sm font-medium text-gray-500">สร้างเมื่อ</label>
                        <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">อัปเดตล่าสุด</label>
                        <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($user->last_login_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">เข้าสู่ระบบล่าสุด</label>
                            <p class="text-gray-900">{{ $user->last_login_at->format('d/m/Y H:i') }}</p>
                            @if($user->last_login_ip)
                                <p class="text-sm text-gray-500">IP: {{ $user->last_login_ip }}</p>
                            @endif
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium text-gray-500">เข้าสู่ระบบล่าสุด</label>
                            <p class="text-gray-500">ยังไม่เคยเข้าสู่ระบบ</p>
                        </div>
                    @endif
                    
                    @if($user->email_verified_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">ยืนยันอีเมล์เมื่อ</label>
                            <p class="text-gray-900">{{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
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
                    <a href="{{ route('backend.users.edit', $user) }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-center">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไขข้อมูล
                    </a>
                    
                    @if($user->id !== auth()->id())
                        <button onclick="deleteUser({{ $user->id }})" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <i class="fas fa-trash mr-2"></i>
                            ลบบัญชี
                        </button>
                    @endif
                    
                    @if($user->is_active)
                        <button onclick="toggleUserStatus({{ $user->id }}, false)" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                            <i class="fas fa-user-times mr-2"></i>
                            ปิดใช้งาน
                        </button>
                    @else
                        <button onclick="toggleUserStatus({{ $user->id }}, true)" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-user-check mr-2"></i>
                            เปิดใช้งาน
                        </button>
                    @endif
                </div>
            </div>

            <!-- Security Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-shield-alt mr-2"></i>
                        ความปลอดภัย
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">รหัสผ่าน</span>
                        <span class="text-sm text-gray-900">••••••••</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">2FA</span>
                        <span class="text-sm text-gray-500">ยังไม่ได้เปิดใช้</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Session</span>
                        <span class="text-sm text-green-600">ใช้งานอยู่</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Status Toggle Form -->
<form id="status-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="is_active" id="status-input">
</form>
@endsection

@push('scripts')
<script>
function deleteUser(userId) {
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
            form.action = `/backend/users/${userId}`;
            form.submit();
        }
    });
}

function toggleUserStatus(userId, status) {
    const action = status ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    
    Swal.fire({
        title: `คุณต้องการ${action}ผู้ใช้งานนี้หรือไม่?`,
        text: `ผู้ใช้งานจะ${status ? 'สามารถเข้าสู่ระบบได้' : 'ไม่สามารถเข้าสู่ระบบได้'}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: status ? '#10b981' : '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `ใช่, ${action}`,
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('status-form');
            form.action = `/backend/users/${userId}/toggle-status`;
            form.submit();
        }
    });
}
</script>
@endpush
