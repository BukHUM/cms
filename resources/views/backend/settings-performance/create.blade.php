@extends('backend.layouts.app')

@section('title', 'Create Performance Setting - เพิ่มการตั้งค่าประสิทธิภาพ')

@section('content')
<div class="main-content">
    <main class="main-content-area">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-plus mr-2 text-blue-600"></i>
                        เพิ่มการตั้งค่าประสิทธิภาพ
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">สร้างการตั้งค่าประสิทธิภาพใหม่</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('backend.settings-performance.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        กลับ
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog mr-2"></i>
                    ข้อมูลการตั้งค่า
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.settings-performance.store') }}" method="POST" id="performance-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="form-label" for="name">ชื่อการตั้งค่า <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="form-input @error('name') border-red-500 @enderror" 
                                   placeholder="ชื่อการตั้งค่าประสิทธิภาพ" required>
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Key -->
                        <div class="form-group">
                            <label class="form-label" for="key">คีย์ <span class="text-red-500">*</span></label>
                            <input type="text" name="key" id="key" value="{{ old('key') }}" 
                                   class="form-input @error('key') border-red-500 @enderror" 
                                   placeholder="performance.cache.enabled" required>
                            @error('key')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">ใช้รูปแบบ dot notation เช่น performance.cache.enabled</p>
                        </div>

                        <!-- Type -->
                        <div class="form-group">
                            <label class="form-label" for="type">ประเภทข้อมูล <span class="text-red-500">*</span></label>
                            <select name="type" id="type" class="form-input @error('type') border-red-500 @enderror" required>
                                <option value="">เลือกประเภทข้อมูล</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label class="form-label" for="category">หมวดหมู่ <span class="text-red-500">*</span></label>
                            <select name="category" id="category" class="form-input @error('category') border-red-500 @enderror" required>
                                <option value="">เลือกหมวดหมู่</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Value -->
                        <div class="form-group md:col-span-2">
                            <label class="form-label" for="value">ค่า <span class="text-red-500">*</span></label>
                            <div id="value-input-container">
                                <input type="text" name="value" id="value" value="{{ old('value') }}" 
                                       class="form-input @error('value') border-red-500 @enderror" 
                                       placeholder="ค่าการตั้งค่า" required>
                            </div>
                            @error('value')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Default Value -->
                        <div class="form-group md:col-span-2">
                            <label class="form-label" for="default_value">ค่าเริ่มต้น</label>
                            <div id="default-value-input-container">
                                <input type="text" name="default_value" id="default_value" value="{{ old('default_value') }}" 
                                       class="form-input @error('default_value') border-red-500 @enderror" 
                                       placeholder="ค่าเริ่มต้น">
                            </div>
                            @error('default_value')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group md:col-span-2">
                            <label class="form-label" for="description">คำอธิบาย</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-input @error('description') border-red-500 @enderror" 
                                      placeholder="คำอธิบายการตั้งค่านี้">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="form-group">
                            <label class="form-label" for="sort_order">ลำดับการเรียง</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" 
                                   class="form-input @error('sort_order') border-red-500 @enderror" 
                                   placeholder="0" min="0">
                            @error('sort_order')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="form-group">
                            <label class="form-label">สถานะ</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                    <span class="ml-2 text-sm text-gray-700">ใช้งาน</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Rules -->
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-check-circle mr-2"></i>
                            กฎการตรวจสอบ
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label" for="validation_required">จำเป็น</label>
                                <input type="checkbox" name="validation_rules[required]" value="1" 
                                       {{ old('validation_rules.required') ? 'checked' : '' }}
                                       class="form-checkbox h-4 w-4 text-blue-600">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="validation_min">ค่าต่ำสุด</label>
                                <input type="number" name="validation_rules[min]" value="{{ old('validation_rules.min') }}" 
                                       class="form-input" placeholder="ค่าต่ำสุด">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="validation_max">ค่าสูงสุด</label>
                                <input type="number" name="validation_rules[max]" value="{{ old('validation_rules.max') }}" 
                                       class="form-input" placeholder="ค่าสูงสุด">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="validation_min_length">ความยาวต่ำสุด</label>
                                <input type="number" name="validation_rules[min_length]" value="{{ old('validation_rules.min_length') }}" 
                                       class="form-input" placeholder="ความยาวต่ำสุด">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="validation_max_length">ความยาวสูงสุด</label>
                                <input type="number" name="validation_rules[max_length]" value="{{ old('validation_rules.max_length') }}" 
                                       class="form-input" placeholder="ความยาวสูงสุด">
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-list mr-2"></i>
                            ตัวเลือก (สำหรับ array/json)
                        </h4>
                        <div class="form-group">
                            <label class="form-label" for="options">ตัวเลือก (JSON Format)</label>
                            <textarea name="options" id="options" rows="4" 
                                      class="form-input @error('options') border-red-500 @enderror" 
                                      placeholder='{"option1": "ค่า 1", "option2": "ค่า 2"}'>{{ old('options') }}</textarea>
                            @error('options')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">ใช้รูปแบบ JSON สำหรับตัวเลือก</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('backend.settings-performance.index') }}" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            ยกเลิก
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueContainer = document.getElementById('value-input-container');
    const defaultValueContainer = document.getElementById('default-value-input-container');
    
    function updateValueInput(type) {
        const currentValue = document.getElementById('value').value;
        const currentDefaultValue = document.getElementById('default_value').value;
        
        // Clear containers
        valueContainer.innerHTML = '';
        defaultValueContainer.innerHTML = '';
        
        let valueInput, defaultInput;
        
        switch(type) {
            case 'boolean':
                valueInput = document.createElement('select');
                valueInput.name = 'value';
                valueInput.id = 'value';
                valueInput.className = 'form-input';
                valueInput.innerHTML = `
                    <option value="0" ${currentValue === '0' ? 'selected' : ''}>ปิด</option>
                    <option value="1" ${currentValue === '1' ? 'selected' : ''}>เปิด</option>
                `;
                
                defaultInput = document.createElement('select');
                defaultInput.name = 'default_value';
                defaultInput.id = 'default_value';
                defaultInput.className = 'form-input';
                defaultInput.innerHTML = `
                    <option value="0" ${currentDefaultValue === '0' ? 'selected' : ''}>ปิด</option>
                    <option value="1" ${currentDefaultValue === '1' ? 'selected' : ''}>เปิด</option>
                `;
                break;
                
            case 'integer':
            case 'float':
                valueInput = document.createElement('input');
                valueInput.type = 'number';
                valueInput.name = 'value';
                valueInput.id = 'value';
                valueInput.className = 'form-input';
                valueInput.value = currentValue;
                valueInput.step = type === 'float' ? '0.01' : '1';
                
                defaultInput = document.createElement('input');
                defaultInput.type = 'number';
                defaultInput.name = 'default_value';
                defaultInput.id = 'default_value';
                defaultInput.className = 'form-input';
                defaultInput.value = currentDefaultValue;
                defaultInput.step = type === 'float' ? '0.01' : '1';
                break;
                
            case 'array':
            case 'json':
                valueInput = document.createElement('textarea');
                valueInput.name = 'value';
                valueInput.id = 'value';
                valueInput.className = 'form-input';
                valueInput.rows = 4;
                valueInput.value = currentValue;
                valueInput.placeholder = '["item1", "item2"] หรือ {"key": "value"}';
                
                defaultInput = document.createElement('textarea');
                defaultInput.name = 'default_value';
                defaultInput.id = 'default_value';
                defaultInput.className = 'form-input';
                defaultInput.rows = 4;
                defaultInput.value = currentDefaultValue;
                defaultInput.placeholder = '["item1", "item2"] หรือ {"key": "value"}';
                break;
                
            default:
                valueInput = document.createElement('input');
                valueInput.type = 'text';
                valueInput.name = 'value';
                valueInput.id = 'value';
                valueInput.className = 'form-input';
                valueInput.value = currentValue;
                
                defaultInput = document.createElement('input');
                defaultInput.type = 'text';
                defaultInput.name = 'default_value';
                defaultInput.id = 'default_value';
                defaultInput.className = 'form-input';
                defaultInput.value = currentDefaultValue;
        }
        
        valueContainer.appendChild(valueInput);
        defaultValueContainer.appendChild(defaultInput);
    }
    
    // Update inputs when type changes
    typeSelect.addEventListener('change', function() {
        updateValueInput(this.value);
    });
    
    // Initialize with current type
    if (typeSelect.value) {
        updateValueInput(typeSelect.value);
    }
});
</script>
@endsection
