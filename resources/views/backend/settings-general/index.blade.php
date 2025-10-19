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
                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 hover:bg-green-200 hover:text-green-700 rounded-md transition-colors duration-200"
                                        title="ปิดการใช้งาน">
                                    <i class="fas fa-toggle-off text-sm"></i>
                                </button>
                                @else
                                <button type="button" 
                                        onclick="toggleStatus({{ $settings_general->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 rounded-md transition-colors duration-200"
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

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-edit mr-2"></i>
                แก้ไขการตั้งค่า
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="mt-4">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Key -->
                        <div>
                            <label for="edit_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-key mr-1"></i>
                                คีย์ <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="edit_key"
                                   name="key"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('key') border-red-500 @enderror"
                                   placeholder="เช่น: site_name, email_from"
                                   required>
                            <div id="key_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Value -->
                        <div>
                            <label for="edit_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-edit mr-1"></i>
                                ค่า <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="edit_value"
                                   name="value"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('value') border-red-500 @enderror"
                                   placeholder="ค่าของการตั้งค่า"
                                   required>
                            <div id="value_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="edit_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-tag mr-1"></i>
                                ประเภท <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_type"
                                    name="type"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">เลือกประเภท</option>
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="float">Float</option>
                                <option value="boolean">Boolean</option>
                                <option value="email">Email</option>
                                <option value="url">URL</option>
                                <option value="json">JSON</option>
                            </select>
                            <div id="type_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Group Name -->
                        <div>
                            <label for="edit_group_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-folder mr-1"></i>
                                กลุ่ม <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_group_name"
                                    name="group_name"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('group_name') border-red-500 @enderror"
                                    required>
                                <option value="">เลือกกลุ่ม</option>
                                <option value="general">General</option>
                                <option value="email">Email</option>
                                <option value="security">Security</option>
                                <option value="performance">Performance</option>
                                <option value="system">System</option>
                            </select>
                            <div id="group_name_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                คำอธิบาย
                            </label>
                            <textarea id="edit_description"
                                      name="description"
                                      rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror"
                                      placeholder="คำอธิบายการตั้งค่านี้"></textarea>
                            <div id="description_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-toggle-on mr-1"></i>
                                สถานะ
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="edit_is_active"
                                       name="is_active"
                                       value="1"
                                       class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700">
                                <label for="edit_is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    เปิดใช้งาน
                                </label>
                            </div>
                        </div>

                        <!-- Current Value Display -->
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md p-3">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                ค่าปัจจุบัน
                            </h4>
                            <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
                                <div>
                                    <span class="font-medium">ประเภท:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 ml-1" id="current_type">
                                        -
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">กลุ่ม:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 ml-1" id="current_group">
                                        -
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">ค่า:</span>
                                    <span class="ml-1 font-mono text-xs bg-white dark:bg-gray-800 px-2 py-1 rounded border dark:border-gray-600" id="current_value">
                                        -
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t dark:border-gray-700">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
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
            const url = `{{ route('backend.settings-general.toggle-status', ':id') }}`.replace(':id', id);
            
            fetch(url, {
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
                } else if (data.success === false) {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
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

// Edit Modal functionality
function openEditModal(id) {
    // Fetch setting data
    fetch(`{{ route('backend.settings-general.show', ':id') }}`.replace(':id', id), {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Populate form fields
        document.getElementById('edit_key').value = data.key;
        document.getElementById('edit_value').value = data.value;
        document.getElementById('edit_type').value = data.type;
        document.getElementById('edit_group_name').value = data.group_name;
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_is_active').checked = data.is_active;

        // Update current values display
        document.getElementById('current_type').textContent = data.type.charAt(0).toUpperCase() + data.type.slice(1);
        document.getElementById('current_group').textContent = data.group_name;
        document.getElementById('current_value').textContent = data.type === 'boolean' ? (data.value ? 'true' : 'false') : data.value;

        // Update form action
        document.getElementById('editForm').action = `{{ route('backend.settings-general.update', ':id') }}`.replace(':id', id);

        // Show modal
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการโหลดข้อมูล',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';

    // Clear form
    document.getElementById('editForm').reset();

    // Clear errors
    const errorElements = document.querySelectorAll('[id$="_error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}

// Form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const url = this.action;

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            location.reload();
        } else {
            // Display validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorElement = document.getElementById(field + '_error');
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.remove('hidden');
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'เกิดข้อผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        });
    });
});

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
        closeEditModal();
    }
});

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
