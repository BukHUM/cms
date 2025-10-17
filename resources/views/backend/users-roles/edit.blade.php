@extends('backend.layouts.app')

@section('title', 'แก้ไขบทบาท')
@section('page-title', 'แก้ไขบทบาท')
@section('page-description', 'แก้ไขข้อมูลบทบาทในระบบ')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-user-tag mr-2"></i>
                <span class="hidden sm:inline">แก้ไขข้อมูลบทบาท: {{ $role->display_name }}</span>
                <span class="sm:hidden">แก้ไขบทบาท</span>
            </h3>
        </div>
        
        <form action="{{ route('backend.roles.update', $role) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        ข้อมูลพื้นฐาน
                    </h4>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            ชื่อบทบาท <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $role->name) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="กรอกชื่อบทบาท (ภาษาอังกฤษ)"
                               required>
                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">ใช้ภาษาอังกฤษเท่านั้น เช่น admin, user, editor</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">
                            ชื่อแสดง <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="display_name" 
                               name="display_name" 
                               value="{{ old('display_name', $role->display_name) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('display_name') border-red-500 @enderror"
                               placeholder="กรอกชื่อแสดงบทบาท"
                               required>
                        @error('display_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">คำอธิบาย</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="กรอกคำอธิบายบทบาท">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $role->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">เปิดใช้งานบทบาทนี้</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_system" 
                                   value="1"
                                   {{ old('is_system', $role->is_system) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">บทบาทระบบ</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">บทบาทระบบจะไม่สามารถลบได้</p>
                    </div>
                </div>
                
                <!-- Permissions -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">
                        <i class="fas fa-key mr-2"></i>
                        สิทธิ์การเข้าถึง
                    </h4>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกสิทธิ์</label>
                        <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded-md p-3">
                            @forelse($permissions as $permission)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">
                                        {{ $permission->display_name }}
                                        @if($permission->description)
                                            <span class="text-gray-500">- {{ $permission->description }}</span>
                                        @endif
                                    </span>
                                </label>
                            @empty
                                <p class="text-gray-500 text-sm">ไม่มีสิทธิ์ที่ใช้งานได้</p>
                            @endforelse
                        </div>
                        @error('permissions')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    ข้อมูลบัญชี
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
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
            
            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('backend.roles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto order-2 sm:order-1">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto order-1 sm:order-2">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select all permissions
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

// Deselect all permissions
function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Add select all/deselect all buttons
document.addEventListener('DOMContentLoaded', function() {
    const permissionsContainer = document.querySelector('.space-y-2.max-h-96');
    if (permissionsContainer) {
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'flex space-x-2 mb-3';
        buttonContainer.innerHTML = `
            <button type="button" onclick="selectAllPermissions()" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200">
                เลือกทั้งหมด
            </button>
            <button type="button" onclick="deselectAllPermissions()" class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded hover:bg-gray-200">
                ยกเลิกทั้งหมด
            </button>
        `;
        permissionsContainer.parentNode.insertBefore(buttonContainer, permissionsContainer);
    }
});
</script>
@endpush
