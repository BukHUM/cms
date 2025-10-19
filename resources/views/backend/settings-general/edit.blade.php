@extends('backend.layouts.app')

@section('title', 'แก้ไขการตั้งค่า')
@section('page-title', 'แก้ไขการตั้งค่า')
@section('page-description', 'แก้ไขข้อมูลการตั้งค่าระบบ')

@section('content')
<div class="main-content-area">
    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: block;">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-edit mr-2"></i>
                    แก้ไขการตั้งค่า: {{ $setting->key }}
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <form action="{{ route('backend.settings-general.update', $setting->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Key -->
                            <div>
                                <label for="key" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-key mr-1"></i>
                                    คีย์ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="key" 
                                       name="key" 
                                       value="{{ old('key', $setting->key) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('key') border-red-500 @enderror"
                                       placeholder="เช่น: site_name, email_from"
                                       required>
                                @error('key')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Value -->
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    ค่า <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="value" 
                                       name="value" 
                                       value="{{ old('value', $setting->value) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror"
                                       placeholder="ค่าของการตั้งค่า"
                                       required>
                                @error('value')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-tag mr-1"></i>
                                    ประเภท <span class="text-red-500">*</span>
                                </label>
                                <select id="type" 
                                        name="type" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                                        required>
                                    <option value="">เลือกประเภท</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('type', $setting->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Group Name -->
                            <div>
                                <label for="group_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-folder mr-1"></i>
                                    กลุ่ม <span class="text-red-500">*</span>
                                </label>
                                <select id="group_name" 
                                        name="group_name" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('group_name') border-red-500 @enderror"
                                        required>
                                    <option value="">เลือกกลุ่ม</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}" {{ old('group_name', $setting->group_name) == $group ? 'selected' : '' }}>
                                            {{ ucfirst($group) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    คำอธิบาย
                                </label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                          placeholder="คำอธิบายการตั้งค่านี้">{{ old('description', $setting->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-toggle-on mr-1"></i>
                                    สถานะ
                                </label>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $setting->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">
                                        เปิดใช้งาน
                                    </label>
                                </div>
                            </div>

                            <!-- Current Value Display -->
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    ค่าปัจจุบัน
                                </h4>
                                <div class="text-xs text-gray-700 space-y-1">
                                    <div>
                                        <span class="font-medium">ประเภท:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                            {{ ucfirst($setting->type) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium">กลุ่ม:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                            {{ ucfirst($setting->group_name) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium">ค่า:</span>
                                        <span class="ml-1 font-mono text-xs bg-white px-2 py-1 rounded border">
                                            @if($setting->type === 'boolean')
                                                {{ $setting->value ? 'true' : 'false' }}
                                            @else
                                                {{ $setting->value }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                        <button type="button" 
                                onclick="closeModal()"
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
</div>

<script>
function closeModal() {
    window.location.href = '{{ route('backend.settings-general.index') }}';
}

// Type change handler
const typeSelect = document.getElementById('type');
const valueInput = document.getElementById('value');

if (typeSelect && valueInput) {
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        const currentValue = valueInput.value;
        
        // Update placeholder based on type
        switch(type) {
            case 'boolean':
                valueInput.placeholder = 'true, false, 1, 0, on, off';
                break;
            case 'integer':
                valueInput.placeholder = 'เช่น: 100, 500';
                break;
            case 'float':
                valueInput.placeholder = 'เช่น: 3.14, 99.99';
                break;
            case 'email':
                valueInput.placeholder = 'เช่น: admin@example.com';
                break;
            case 'url':
                valueInput.placeholder = 'เช่น: https://example.com';
                break;
            case 'json':
                valueInput.placeholder = 'เช่น: {"key": "value"}';
                break;
            default:
                valueInput.placeholder = 'ค่าของการตั้งค่า';
        }
        
        // Clear value if it doesn't match the new type
        if (currentValue && !isValidValueForType(currentValue, type)) {
            valueInput.value = '';
        }
    });
}

function isValidValueForType(value, type) {
    switch(type) {
        case 'boolean':
            return ['true', 'false', '1', '0', 'on', 'off'].includes(value.toLowerCase());
        case 'integer':
            return /^\d+$/.test(value);
        case 'float':
            return !isNaN(parseFloat(value)) && isFinite(value);
        case 'email':
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        case 'url':
            try {
                new URL(value);
                return true;
            } catch {
                return false;
            }
        case 'json':
            try {
                JSON.parse(value);
                return true;
            } catch {
                return false;
            }
        default:
            return true;
    }
}
</script>
@endsection
