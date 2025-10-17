@extends('backend.layouts.app')

@section('title', 'การตั้งค่าทั่วไป')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                <i class="fas fa-cog mr-2"></i>
                การตั้งค่าทั่วไป
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                จัดการการตั้งค่าระบบและค่าคอนฟิกต่างๆ
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('backend.settings.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i>
                เพิ่มการตั้งค่า
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="search" 
                           placeholder="ค้นหาการตั้งค่า..." 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 pl-10"
                           value="{{ request('search') }}">
                </div>

                <!-- Group Filter -->
                <select id="group-filter" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">ทุกกลุ่ม</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                            {{ ucfirst($group) }}
                        </option>
                    @endforeach
                </select>

                <!-- Type Filter -->
                <select id="type-filter" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">ทุกประเภท</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select id="status-filter" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">ทุกสถานะ</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Settings List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-list mr-2"></i>
                รายการการตั้งค่า ({{ $settings->total() }} รายการ)
            </h3>
        </div>
        <div class="p-0">
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold text-left">คีย์</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold text-left">ค่า</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold text-left">ประเภท</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold text-left">กลุ่ม</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold text-left">สถานะ</th>
                            <th class="px-4 py-2 border-b border-gray-200 font-semibold text-left">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $setting)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="font-medium text-gray-900">{{ $setting->key }}</div>
                                    @if($setting->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($setting->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        @if($setting->type === 'boolean')
                                            @if($setting->value)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    เปิด
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i>
                                                    ปิด
                                                </span>
                                            @endif
                                        @else
                                            {{ Str::limit($setting->value, 30) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->type_color }}">
                                        <i class="{{ $setting->type_icon }} mr-1"></i>
                                        {{ ucfirst($setting->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->group_color }}">
                                        {{ ucfirst($setting->group) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    @if($setting->is_public)
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
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('backend.settings.show', $setting) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50" 
                                           title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.settings.edit', $setting) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 p-1 rounded hover:bg-yellow-50" 
                                           title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($setting->canBeDeleted())
                                            <button onclick="deleteSetting({{ $setting->id }})" 
                                                    class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50" 
                                                    title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                        @if(!$setting->isSystemSetting())
                                            <button onclick="toggleStatus({{ $setting->id }}, {{ $setting->is_public ? 'false' : 'true' }})" 
                                                    class="text-gray-600 hover:text-gray-800 p-1 rounded hover:bg-gray-50" 
                                                    title="{{ $setting->is_public ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                                                <i class="fas fa-toggle-{{ $setting->is_public ? 'on text-green-500' : 'off text-red-500' }}"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 border-b border-gray-200 text-center py-8 text-gray-500">
                                    <i class="fas fa-cog text-4xl mb-4"></i>
                                    <div>ไม่พบข้อมูลการตั้งค่า</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden">
                @forelse($settings as $setting)
                    <div class="border-b border-gray-200 p-4 last:border-b-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="{{ $setting->type_icon }} text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h3 class="font-medium text-gray-900 truncate">{{ $setting->key }}</h3>
                                        @if($setting->description)
                                            <p class="text-sm text-gray-500 truncate">{{ Str::limit($setting->description, 40) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('backend.settings.show', $setting) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50" 
                                           title="ดูข้อมูล">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.settings.edit', $setting) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 p-2 rounded-full hover:bg-yellow-50" 
                                           title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($setting->canBeDeleted())
                                            <button onclick="deleteSetting({{ $setting->id }})" 
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50" 
                                                    title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-tag w-4 mr-2"></i>
                                        <span>ค่า: 
                                            @if($setting->type === 'boolean')
                                                {{ $setting->value ? 'เปิด' : 'ปิด' }}
                                            @else
                                                {{ Str::limit($setting->value, 20) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-wrap gap-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->group_color }}">
                                                {{ ucfirst($setting->group) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->type_color }}">
                                                <i class="{{ $setting->type_icon }} mr-1"></i>
                                                {{ ucfirst($setting->type) }}
                                            </span>
                                        </div>
                                        @if($setting->is_public)
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
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-cog text-4xl mb-4"></i>
                        <div>ไม่พบข้อมูลการตั้งค่า</div>
                    </div>
                @endforelse
            </div>
        </div>
        
        @if($settings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700 text-center sm:text-left">
                        แสดง {{ $settings->firstItem() }} ถึง {{ $settings->lastItem() }} 
                        จาก {{ $settings->total() }} รายการ
                    </div>
                    <div class="flex justify-center">
                        {{ $settings->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Toggle Status Form -->
    <form id="toggle-status-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
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

    // Group filter
    const groupFilter = document.getElementById('group-filter');
    if (groupFilter) {
        groupFilter.addEventListener('change', function() {
            const group = this.value;
            const url = new URL(window.location);
            if (group) {
                url.searchParams.set('group', group);
            } else {
                url.searchParams.delete('group');
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

    // Delete setting function
    window.deleteSetting = function(settingId) {
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
                form.action = `/backend/settings/${settingId}`;
                form.submit();
            }
        });
    };

    // Toggle status function
    window.toggleStatus = function(settingId, currentStatus) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: `คุณต้องการ${currentStatus ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}การตั้งค่านี้หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, เปลี่ยนสถานะ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('toggle-status-form');
                form.action = `/backend/settings/${settingId}/toggle-status`;
                form.submit();
            }
        });
    };
})();
</script>
@endpush
