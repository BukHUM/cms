@extends('backend.layouts.app')

@section('title', 'รายละเอียดการตั้งค่า')
@section('page-title', 'รายละเอียดการตั้งค่า')
@section('page-description', 'ดูข้อมูลการตั้งค่าระบบ')

@section('content')
<div class="main-content-area">

    <!-- Setting Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="{{ $setting->type_icon }} text-white text-2xl"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $setting->key }}</h2>
                    @if($setting->description)
                        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 mt-1">{{ $setting->description }}</p>
                    @endif
                    
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        @if($setting->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                เปิดใช้งาน
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                <i class="fas fa-times-circle mr-1"></i>
                                ปิดใช้งาน
                            </span>
                        @endif
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                            {{ ucfirst($setting->group_name) }}
                        </span>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->type_color }}">
                            <i class="{{ $setting->type_icon }} mr-1"></i>
                            {{ ucfirst($setting->type) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                    <button onclick="toggleStatus({{ $setting->id }}, {{ $setting->is_active ? 'false' : 'true' }})" 
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="fas fa-toggle-{{ $setting->is_active ? 'on' : 'off' }} mr-2"></i>
                        {{ $setting->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
                    </button>
                    <button onclick="openEditModal({{ $setting->id }})" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </button>
                    <a href="{{ route('backend.settings-general.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 w-full sm:w-auto text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Setting Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-info-circle mr-2"></i>
                    ข้อมูลพื้นฐาน
                </h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">คีย์</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded border dark:border-gray-600">{{ $setting->key }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ค่า</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($setting->type === 'boolean')
                                @if($setting->value)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <i class="fas fa-check mr-1"></i>
                                        เปิด ({{ $setting->value }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        <i class="fas fa-times mr-1"></i>
                                        ปิด ({{ $setting->value }})
                                    </span>
                                @endif
                            @elseif($setting->type === 'json')
                                <pre class="bg-gray-50 dark:bg-gray-700 p-3 rounded border dark:border-gray-600 text-xs overflow-x-auto text-gray-900 dark:text-white">{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</pre>
                            @elseif($setting->type === 'file' && in_array($setting->key, ['site_logo', 'site_favicon']) && $setting->value)
                                <div class="space-y-3">
                                    <span class="font-mono bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded border dark:border-gray-600 block text-gray-900 dark:text-white">{{ $setting->value }}</span>
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <img src="{{ asset('storage/' . str_replace('\\', '/', $setting->value)) }}" 
                                             alt="{{ $setting->key }}" 
                                             class="w-16 h-16 object-cover rounded border dark:border-gray-600"
                                             onerror="this.style.display='none'">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ basename(str_replace('\\', '/', $setting->value)) }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $setting->value }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="font-mono bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded border dark:border-gray-600 block text-gray-900 dark:text-white">{{ $setting->value }}</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ประเภท</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $setting->type_color }}">
                                <i class="{{ $setting->type_icon }} mr-1"></i>
                                {{ ucfirst($setting->type) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">กลุ่ม</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                {{ ucfirst($setting->group_name) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-2"></i>
                    ข้อมูลเพิ่มเติม
                </h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">สถานะ</dt>
                        <dd class="mt-1">
                            @if($setting->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    เปิดใช้งาน
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    ปิดใช้งาน
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ประเภทการตั้งค่า</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                <i class="fas fa-user-cog mr-1"></i>
                                กำหนดเอง
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">สร้างเมื่อ</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $setting->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อัปเดตล่าสุด</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $setting->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Description -->
    @if($setting->description)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-align-left mr-2"></i>
                    คำอธิบาย
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $setting->description }}</p>
            </div>
        </div>
    @endif

    <!-- Toggle Status Form -->
    <form id="toggle-status-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

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
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="remove_file_flag" name="remove_file" value="0">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Key (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-key mr-1"></i>
                                    คีย์ <span class="text-red-500">*</span>
                                </label>
                                <div id="edit_key_display" class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 font-mono">
                                    <!-- Key will be populated here -->
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่สามารถแก้ไขได้</p>
                            </div>

                            <!-- Value -->
                            <div>
                                <label for="edit_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    ค่า <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Text Input (default) -->
                                <input type="text"
                                       id="edit_value"
                                       name="value"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('value') border-red-500 @enderror"
                                       placeholder="ค่าของการตั้งค่า">
                                
                                <!-- File Upload Section (for file types) -->
                                <div id="file_upload_section" class="hidden">
                                    <div class="mt-2">
                                        <input type="file" 
                                               id="edit_file" 
                                               name="file" 
                                               accept="image/jpeg,image/png,image/gif,image/x-icon,image/vnd.microsoft.icon"
                                               class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-200">
                                        <p class="text-xs text-gray-500 mt-1">รองรับไฟล์: JPG, PNG, GIF, ICO (ขนาดไม่เกิน 2MB)</p>
                                    </div>
                                    
                                    <!-- Current File Preview -->
                                    <div id="current_file_preview" class="mt-3 hidden">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-image mr-1"></i>
                                            ไฟล์ปัจจุบัน
                                        </label>
                                        <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <img id="current_file_image" src="" alt="Current file" class="w-12 h-12 object-cover rounded">
                                            <div class="flex-1">
                                                <p id="current_file_name" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                                                <p id="current_file_path" class="text-xs text-gray-500 dark:text-gray-400"></p>
                                            </div>
                                            <button type="button" id="remove_current_file" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- New File Preview -->
                                    <div id="new_file_preview" class="mt-3 hidden">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            ไฟล์ใหม่
                                        </label>
                                        <div class="flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900 rounded-md">
                                            <img id="new_file_image" src="" alt="New file" class="w-12 h-12 object-cover rounded">
                                            <div class="flex-1">
                                                <p id="new_file_name" class="text-sm font-medium text-green-900 dark:text-green-100"></p>
                                                <p id="new_file_size" class="text-xs text-green-600 dark:text-green-300"></p>
                                            </div>
                                            <button type="button" id="remove_new_file" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="value_error" class="text-red-600 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Type (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-tag mr-1"></i>
                                    ประเภท <span class="text-red-500">*</span>
                                </label>
                                <div id="edit_type_display" class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                    <!-- Type will be populated here -->
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่สามารถแก้ไขได้</p>
                            </div>

                            <!-- Group Name (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-folder mr-1"></i>
                                    กลุ่ม <span class="text-red-500">*</span>
                                </label>
                                <div id="edit_group_display" class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                                    <!-- Group will be populated here -->
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่สามารถแก้ไขได้</p>
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
@endsection

@push('scripts')
<script>
    // Toggle status function
    function toggleStatus(settingId, currentStatus) {
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
                form.action = `/backend/settings-general/${settingId}/toggle-status`;
                form.submit();
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
                // Reset form and file upload sections
                resetFileUploadSections();
                
                // Check if this is a file type setting
                const isFileType = ['site_logo', 'site_favicon'].includes(data.key);
                
                if (isFileType) {
                    // Show file upload section
                    document.getElementById('file_upload_section').classList.remove('hidden');
                    document.getElementById('edit_value').classList.add('hidden');
                    document.getElementById('edit_value').removeAttribute('required');
                    document.getElementById('edit_value').value = ''; // Clear value
                    document.getElementById('remove_file_flag').value = '0'; // reset remove flag
                    
                    // Show current file if exists
                    if (data.value && data.value !== '') {
                        showCurrentFilePreview(data.value);
                    }
                } else {
                    // Show text input section
                    document.getElementById('file_upload_section').classList.add('hidden');
                    document.getElementById('edit_value').classList.remove('hidden');
                    document.getElementById('edit_value').setAttribute('required', 'required');
                    document.getElementById('edit_value').value = data.value;
                }
                
                // Populate other form fields
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_is_active').checked = data.is_active;

                // Update read-only displays
                document.getElementById('edit_key_display').textContent = data.key;
                document.getElementById('edit_type_display').textContent = data.type.charAt(0).toUpperCase() + data.type.slice(1);
                document.getElementById('edit_group_display').textContent = data.group_name.charAt(0).toUpperCase() + data.group_name.slice(1);
                
                // Update current values display
                document.getElementById('current_type').textContent = data.type.charAt(0).toUpperCase() + data.type.slice(1);
                document.getElementById('current_group').textContent = data.group_name.charAt(0).toUpperCase() + data.group_name.slice(1);
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
        
        // Reset file upload sections
        resetFileUploadSections();
        
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
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    location.reload();
                });
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
});

// File Upload Functions (Cross-platform compatible)
let selectedFile = null;

function normalizeStoragePath(path) {
    if (!path) return '';
    // Convert Windows backslashes to forward slashes for cross-platform compatibility
    let normalizedPath = String(path).replace(/\\/g, '/');
    // Remove any leading "/" and leading "storage/" to avoid double prefixing
    normalizedPath = normalizedPath.replace(/^\/+/, '').replace(/^storage\//, '');
    return normalizedPath;
}

function showCurrentFilePreview(filePath) {
    const preview = document.getElementById('current_file_preview');
    const image = document.getElementById('current_file_image');
    const name = document.getElementById('current_file_name');
    const path = document.getElementById('current_file_path');
    
    // Set image source with normalized path
    if (filePath.startsWith('http')) {
        image.src = filePath;
    } else {
        const normalized = normalizeStoragePath(filePath);
        image.src = `/storage/settings/${normalized}`;
    }
    
    // Extract filename from path (cross-platform compatible)
    const fileName = filePath.split(/[/\\]/).pop();
    name.textContent = fileName;
    path.textContent = filePath;
    
    // Show preview
    preview.classList.remove('hidden');
}

function showNewFilePreview(file) {
    const preview = document.getElementById('new_file_preview');
    const image = document.getElementById('new_file_image');
    const name = document.getElementById('new_file_name');
    const size = document.getElementById('new_file_size');
    
    // Create file URL for preview
    const fileURL = URL.createObjectURL(file);
    image.src = fileURL;
    
    // Set file info
    name.textContent = file.name;
    size.textContent = formatFileSize(file.size);
    
    // Store selected file
    selectedFile = file;
    
    // Show preview
    preview.classList.remove('hidden');
}

function removeNewFile() {
    selectedFile = null;
    document.getElementById('new_file_preview').classList.add('hidden');
    document.getElementById('edit_file').value = '';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function resetFileUploadSections() {
    // Hide all file upload sections
    document.getElementById('file_upload_section').classList.add('hidden');
    document.getElementById('current_file_preview').classList.add('hidden');
    document.getElementById('new_file_preview').classList.add('hidden');
    
    // Reset text input
    document.getElementById('edit_value').classList.remove('hidden');
    document.getElementById('edit_value').setAttribute('required', 'required');
    document.getElementById('edit_value').value = '';
    
    // Reset file input
    const fileInput = document.getElementById('edit_file');
    if (fileInput) fileInput.value = '';
    
    // Reset selected file
    selectedFile = null;
    
    // Reset remove flag
    const removeFlag = document.getElementById('remove_file_flag');
    if (removeFlag) removeFlag.value = '0';
}

// File Upload Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const editFileInput = document.getElementById('edit_file');
    const removeNewFileBtn = document.getElementById('remove_new_file');
    const removeCurrentFileBtn = document.getElementById('remove_current_file');
    const editForm = document.getElementById('editForm');
    
    if (editFileInput) {
        editFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/x-icon', 'image/vnd.microsoft.icon'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        title: 'ไฟล์ไม่ถูกต้อง!',
                        text: 'กรุณาเลือกไฟล์รูปภาพ (JPG, PNG, GIF, ICO) เท่านั้น',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                    this.value = '';
                    return;
                }
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        title: 'ไฟล์ใหญ่เกินไป!',
                        text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2MB',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                    this.value = '';
                    return;
                }
                
                showNewFilePreview(file);
            }
        });
    }
    
    if (removeNewFileBtn) {
        removeNewFileBtn.addEventListener('click', function() {
            removeNewFile();
        });
    }
    
    if (removeCurrentFileBtn) {
        removeCurrentFileBtn.addEventListener('click', function() {
            document.getElementById('current_file_preview').classList.add('hidden');
            // Set a flag to indicate current file should be removed
            const removeFlag = document.getElementById('remove_file_flag');
            if (removeFlag) removeFlag.value = '1';
            document.getElementById('edit_value').value = '';
            // Clear current value display as visual feedback
            const currentValueEl = document.getElementById('current_value');
            if (currentValueEl) currentValueEl.textContent = '';
        });
    }
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if this is a file type setting
            const isFileType = document.getElementById('file_upload_section').classList.contains('hidden') === false;
            
            // For file type settings, temporarily remove required attribute
            let wasRequired = false;
            if (isFileType) {
                const valueInput = document.getElementById('edit_value');
                wasRequired = valueInput.hasAttribute('required');
                valueInput.removeAttribute('required');
            }

            const formData = new FormData(this);
            const url = this.action;
            
            if (isFileType) {
                // For file type settings, check if new file is selected
                if (selectedFile) {
                    // File will be uploaded via FormData
                    // No need to set value here, backend will handle it
                } else {
                    // Check remove flag; if set, clear value
                    const removeFlag = document.getElementById('remove_file_flag')?.value === '1';
                    if (removeFlag) {
                        formData.set('value', '');
                    } else {
                        const currentValue = document.getElementById('current_value').textContent || '';
                        formData.set('value', currentValue);
                    }
                }
                
                // Restore required attribute if it was there
                const valueInput = document.getElementById('edit_value');
                if (wasRequired) {
                    valueInput.setAttribute('required', 'required');
                }
            } else {
                // For text type settings, validate required field
                const valueInput = document.getElementById('edit_value');
                if (!valueInput.value.trim()) {
                    Swal.fire({
                        title: 'ข้อมูลไม่ครบถ้วน!',
                        text: 'กรุณากรอกค่าการตั้งค่า',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                    return;
                }
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                
                return response.json();
            })
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
    }
});
</script>
@endpush
