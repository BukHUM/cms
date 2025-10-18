@extends('backend.layouts.app')

@section('title', 'การตั้งค่าทั่วไป')
@section('page-title', 'การตั้งค่าทั่วไป')
@section('page-description', 'จัดการการตั้งค่าระบบและค่าคอนฟิกต่างๆ')

@section('content')
<div class="main-content-area">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

                <!-- Status Filter -->
                <select id="status-filter" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">ทุกสถานะ</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>เปิดใช้งาน</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ปิดใช้งาน</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 mt-4">
                <button type="button" 
                        id="apply-filters"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-filter mr-2"></i>
                    กรองข้อมูล
                </button>
                
                <button type="button" 
                        id="clear-filters"
                        class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-times mr-2"></i>
                    ล้างตัวกรอง
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    รายการการตั้งค่า ({{ $settings_generals->total() }} รายการ)
                </h3>
            </div>
        </div>

        @if($settings_generals->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            คีย์
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ค่า
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ประเภท
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            กลุ่ม
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            สถานะ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            การดำเนินการ
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($settings_generals as $settings_general)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="{{ $settings_general->type_icon }} text-gray-400 mr-2"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $settings_general->key }}</div>
                                    @if($settings_general->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($settings_general->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $settings_general->formatted_value }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($settings_general->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $settings_general->group_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settings_general->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $settings_general->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <!-- View Button -->
                                <a href="{{ route('backend.settings-general.show', $settings_general->id) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                   title="ดูรายละเอียด">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <button type="button" 
                                        onclick="openEditModal({{ $settings_general->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-md transition-colors duration-200"
                                        title="แก้ไข">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                
                                <!-- Toggle Status Button -->
                                @if($settings_general->is_active)
                                <button type="button" 
                                        onclick="toggleStatus({{ $settings_general->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-md transition-colors duration-200"
                                        title="ปิดการใช้งาน">
                                    <i class="fas fa-toggle-off text-sm"></i>
                                </button>
                                @else
                                <button type="button" 
                                        onclick="toggleStatus({{ $settings_general->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-md transition-colors duration-200"
                                        title="เปิดการใช้งาน">
                                    <i class="fas fa-toggle-on text-sm"></i>
                                </button>
                                @endif
                                
                                <!-- Reset Button (if has default value) -->
                                @if($settings_general->default_value)
                                <button type="button" 
                                        onclick="resetSetting({{ $settings_general->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 text-purple-600 hover:text-purple-900 hover:bg-purple-50 rounded-md transition-colors duration-200"
                                        title="รีเซ็ตเป็นค่าเริ่มต้น">
                                    <i class="fas fa-undo text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $settings_generals->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-cog text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่พบการตั้งค่า</h3>
            <p class="text-gray-500 mb-4">ยังไม่มีการตั้งค่าในระบบ</p>
        </div>
        @endif
    </div>
</div>

<script>
// Filter functionality
document.getElementById('apply-filters').addEventListener('click', function() {
    const search = document.getElementById('search').value;
    const status = document.getElementById('status-filter').value;
    
    const url = new URL(window.location);
    if (search) url.searchParams.set('search', search);
    if (status) url.searchParams.set('status', status);
    
    window.location.href = url.toString();
});

document.getElementById('clear-filters').addEventListener('click', function() {
    window.location.href = '{{ route('backend.settings-general.index') }}';
});

function toggleStatus(id) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: 'คุณต้องการเปลี่ยนสถานะการตั้งค่านี้หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, เปลี่ยนสถานะ!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('backend.settings-general.toggle-status', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PATCH'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'เปลี่ยนสถานะเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            });
        }
    });
}

function resetSetting(id) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: 'คุณต้องการรีเซ็ตการตั้งค่านี้เป็นค่าเริ่มต้นหรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, รีเซ็ต!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('backend.settings-general.reset', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'รีเซ็ตการตั้งค่าเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการรีเซ็ตการตั้งค่า',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            });
        }
    });
}
</script>
@endsection
