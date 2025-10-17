@extends('backend.layouts.app')

@section('title', 'เพิ่มการตั้งค่าใหม่')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                <i class="fas fa-plus mr-2"></i>
                เพิ่มการตั้งค่าใหม่
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                สร้างการตั้งค่าใหม่สำหรับระบบ
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('backend.settings.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-arrow-left mr-2"></i>
                กลับ
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('backend.settings.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Key -->
                    <div>
                        <label for="key" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-key mr-1"></i>
                            คีย์ <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="key" 
                               name="key" 
                               value="{{ old('key') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('key') border-red-500 @enderror"
                               placeholder="เช่น: site_name, email_from"
                               required>
                        @error('key')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">ชื่อคีย์สำหรับการตั้งค่า (ต้องไม่ซ้ำ)</p>
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
                               value="{{ old('value') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('value') border-red-500 @enderror"
                               placeholder="ค่าของการตั้งค่า"
                               required>
                        @error('value')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">ค่าของการตั้งค่า</p>
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
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">ประเภทของข้อมูล</p>
                    </div>

                    <!-- Group -->
                    <div>
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-folder mr-1"></i>
                            กลุ่ม <span class="text-red-500">*</span>
                        </label>
                        <select id="group" 
                                name="group" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('group') border-red-500 @enderror"
                                required>
                            <option value="">เลือกกลุ่ม</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ old('group') == $group ? 'selected' : '' }}>
                                    {{ ucfirst($group) }}
                                </option>
                            @endforeach
                        </select>
                        @error('group')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">กลุ่มการจัดหมวดหมู่</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            คำอธิบาย
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="คำอธิบายการตั้งค่านี้">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">คำอธิบายการใช้งานการตั้งค่านี้</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-1"></i>
                            สถานะ
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_public" 
                                   name="is_public" 
                                   value="1"
                                   {{ old('is_public', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_public" class="ml-2 block text-sm font-medium text-gray-700">
                                เปิดใช้งาน
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">การตั้งค่านี้จะสามารถเข้าถึงได้จากระบบ</p>
                    </div>

                    <!-- Type Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            ข้อมูลประเภท
                        </h4>
                        <div class="text-xs text-blue-800 space-y-1">
                            <div><strong>String:</strong> ข้อความทั่วไป</div>
                            <div><strong>Boolean:</strong> true/false, 1/0, on/off</div>
                            <div><strong>Integer:</strong> ตัวเลขเต็ม</div>
                            <div><strong>Float:</strong> ตัวเลขทศนิยม</div>
                            <div><strong>Email:</strong> อีเมล</div>
                            <div><strong>URL:</strong> ลิงก์</div>
                            <div><strong>JSON:</strong> ข้อมูล JSON</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('backend.settings.index') }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto text-center">
                    <i class="fas fa-times mr-2"></i>
                    ยกเลิก
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                    <i class="fas fa-save mr-2"></i>
                    บันทึก
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
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
@endpush
