@extends('backend.layouts.app')

@section('title', 'จัดการบทบาท')
@section('page-title', 'จัดการบทบาท')
@section('page-description', 'จัดการข้อมูลบทบาทในระบบ')

@section('content')
<div class="space-y-6">
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-2">
        <a href="{{ route('backend.roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
            <i class="fas fa-plus mr-2"></i>
            เพิ่มบทบาทใหม่
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="p-4 rounded-md mb-4 bg-green-100 border border-green-400 text-green-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-md mb-4 bg-red-100 border border-red-400 text-red-700">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Roles Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-user-tag mr-2"></i>
                รายการบทบาท ({{ $roles->total() }} รายการ)
            </h3>
        </div>
        
        <div class="p-0">
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">ชื่อบทบาท</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">คำอธิบาย</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">สิทธิ์</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">ผู้ใช้งาน</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">สถานะ</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">ประเภท</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $role->display_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $role->description ?: 'ไม่มีคำอธิบาย' }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($role->permissions->take(3) as $permission)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">{{ $permission->display_name }}</span>
                                        @empty
                                            <span class="text-gray-400 text-sm">ไม่มีสิทธิ์</span>
                                        @endforelse
                                        @if($role->permissions->count() > 3)
                                            <span class="text-xs text-gray-500">+{{ $role->permissions->count() - 3 }} อื่นๆ</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">
                                        {{ $role->users->count() }} คน
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
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
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    @if($role->is_system)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-cog mr-1"></i>
                                            ระบบ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-user mr-1"></i>
                                            กำหนดเอง
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('backend.roles.show', $role) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50" 
                                           title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.roles.edit', $role) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 p-1 rounded hover:bg-yellow-50" 
                                           title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($role->canBeDeleted())
                                            <button onclick="deleteRole({{ $role->id }})" 
                                                    class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50" 
                                                    title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 border-b border-gray-200 text-center py-8 text-gray-500">
                                    <i class="fas fa-user-tag text-4xl mb-4"></i>
                                    <div>ไม่พบข้อมูลบทบาท</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden">
                @forelse($roles as $role)
                    <div class="border-b border-gray-200 p-4 last:border-b-0">
                        <div class="flex items-start space-x-3">
                            <!-- Role Icon -->
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-tag text-white"></i>
                            </div>
                            
                            <!-- Role Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h3 class="font-medium text-gray-900 truncate">{{ $role->display_name }}</h3>
                                        <p class="text-sm text-gray-500 truncate">{{ $role->name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('backend.roles.show', $role) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50" 
                                           title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.roles.edit', $role) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 p-2 rounded-full hover:bg-yellow-50" 
                                           title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($role->canBeDeleted())
                                            <button onclick="deleteRole({{ $role->id }})" 
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50" 
                                                    title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    @if($role->description)
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-info-circle w-4 mr-2"></i>
                                            <span class="truncate">{{ $role->description }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($role->permissions->take(2) as $permission)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">{{ $permission->display_name }}</span>
                                            @empty
                                                <span class="text-gray-400 text-xs">ไม่มีสิทธิ์</span>
                                            @endforelse
                                            @if($role->permissions->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $role->permissions->count() - 2 }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
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
                                                    ระบบ
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-user mr-1"></i>
                                                    กำหนดเอง
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        ผู้ใช้งาน: {{ $role->users->count() }} คน
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-tag text-4xl mb-4"></i>
                        <div>ไม่พบข้อมูลบทบาท</div>
                    </div>
                @endforelse
            </div>
        </div>
        
        @if($roles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 text-center sm:text-left">
                        แสดง {{ $roles->firstItem() }} ถึง {{ $roles->lastItem() }} 
                        จาก {{ $roles->total() }} รายการ
                    </div>
                    <div class="flex justify-center">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        @endif
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
(function() {
    'use strict';
    
    // Search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const search = this.value;
            const url = new URL(window.location);
            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        });
    }

    // Status filter
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const url = new URL(window.location);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        });
    }

    // Type filter
    const typeFilter = document.getElementById('type-filter');
    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            const type = this.value;
            const url = new URL(window.location);
            if (type) {
                url.searchParams.set('type', type);
            } else {
                url.searchParams.delete('type');
            }
            window.location.href = url.toString();
        });
    }

    // Delete role function
    window.deleteRole = function(roleId) {
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
    };
})();
</script>
@endpush
