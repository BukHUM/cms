@extends('backend.layouts.app')

@section('title', 'จัดการผู้ใช้งาน')
@section('page-title', 'จัดการผู้ใช้งาน')
@section('page-description', 'จัดการข้อมูลผู้ใช้งานในระบบ')

@section('content')
<div class="space-y-6">
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-2">
        <a href="{{ route('backend.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
            <i class="fas fa-plus mr-2"></i>
            เพิ่มผู้ใช้งานใหม่
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

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-users mr-2"></i>
                รายการผู้ใช้งาน ({{ $users->total() }} รายการ)
            </h3>
        </div>
        
        <div class="p-0">
            <!-- Desktop Table View -->
            <div class="hidden lg:block table-container">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">รูปภาพ</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">ชื่อผู้ใช้งาน</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">อีเมล์</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">บทบาท</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">สถานะ</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">เข้าสู่ระบบล่าสุด</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user text-white"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($user->full_name)
                                            <div class="text-sm text-gray-500">{{ $user->full_name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $role->display_name }}</span>
                                        @empty
                                            <span class="text-gray-400 text-sm">ไม่มีบทบาท</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
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
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    @if($user->last_login_at)
                                        <div class="text-sm text-gray-900">
                                            {{ $user->last_login_at->format('d/m/Y H:i') }}
                                        </div>
                                        @if($user->last_login_ip)
                                            <div class="text-xs text-gray-500">{{ $user->last_login_ip }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">ยังไม่เคยเข้าสู่ระบบ</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('backend.users.show', $user) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50" 
                                           title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.users.edit', $user) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 p-1 rounded hover:bg-yellow-50" 
                                           title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button onclick="deleteUser({{ $user->id }})" 
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
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <div>ไม่พบข้อมูลผู้ใช้งาน</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden">
                @forelse($users as $user)
                    <div class="border-b border-gray-200 p-4 last:border-b-0">
                        <div class="flex items-start space-x-3">
                            <!-- Avatar -->
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-white"></i>
                                @endif
                            </div>
                            
                            <!-- User Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h3 class="font-medium text-gray-900 truncate">{{ $user->name }}</h3>
                                        @if($user->full_name)
                                            <p class="text-sm text-gray-500 truncate">{{ $user->full_name }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('backend.users.show', $user) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50" 
                                           title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.users.edit', $user) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 p-2 rounded-full hover:bg-yellow-50" 
                                           title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button onclick="deleteUser({{ $user->id }})" 
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50" 
                                                    title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope w-4 mr-2"></i>
                                        <span class="truncate">{{ $user->email }}</span>
                                    </div>
                                    
                                    @if($user->phone)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-phone w-4 mr-2"></i>
                                            <span>{{ $user->phone }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($user->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $role->display_name }}</span>
                                            @empty
                                                <span class="text-gray-400 text-xs">ไม่มีบทบาท</span>
                                            @endforelse
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
                                    
                                    @if($user->last_login_at)
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            เข้าสู่ระบบล่าสุด: {{ $user->last_login_at->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <div>ไม่พบข้อมูลผู้ใช้งาน</div>
                    </div>
                @endforelse
            </div>
        </div>
        
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 text-center sm:text-left">
                        แสดง {{ $users->firstItem() }} ถึง {{ $users->lastItem() }} 
                        จาก {{ $users->total() }} รายการ
                    </div>
                    <div class="flex justify-center">
                        {{ $users->links() }}
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

    // Role filter
    const roleFilter = document.getElementById('role-filter');
    if (roleFilter) {
        roleFilter.addEventListener('change', function() {
            const role = this.value;
            const url = new URL(window.location);
            if (role) {
                url.searchParams.set('role', role);
            } else {
                url.searchParams.delete('role');
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

    // Delete user function
    window.deleteUser = function(userId) {
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
    };
})();
</script>
@endpush
