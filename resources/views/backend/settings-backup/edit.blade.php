@extends('backend.layouts.app')

@section('title', 'แก้ไขการตั้งค่าการสำรองข้อมูล')
@section('page-title', 'แก้ไขการตั้งค่าการสำรองข้อมูล')
@section('page-description', 'แก้ไขการตั้งค่าสำหรับการสำรองข้อมูล')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('backend.settings-backup.index') }}" class="btn-custom-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            กลับไปหน้ารายการ
        </a>
    </div>

    <!-- Edit Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i>
                แก้ไขการตั้งค่าการสำรองข้อมูล
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('backend.settings-backup.update', $settingsBackup) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">ข้อมูลพื้นฐาน</h4>
                        
                        <div class="form-group">
                            <label class="form-label">รหัสการตั้งค่า <span class="text-red-500">*</span></label>
                            <input type="text" name="key" value="{{ old('key', $settingsBackup->key) }}" 
                                   class="form-input-custom focus-blue @error('key') border-red-500 @enderror" 
                                   placeholder="backup_auto_enabled" required>
                            @error('key')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">ใช้ตัวอักษรภาษาอังกฤษและเครื่องหมาย _ เท่านั้น</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ค่าการตั้งค่า <span class="text-red-500">*</span></label>
                            <input type="text" name="value" value="{{ old('value', $settingsBackup->value) }}" 
                                   class="form-input-custom focus-blue @error('value') border-red-500 @enderror" 
                                   placeholder="true" required>
                            @error('value')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">ประเภทข้อมูล <span class="text-red-500">*</span></label>
                            <select name="type" class="form-input-custom focus-blue @error('type') border-red-500 @enderror" required>
                                <option value="">เลือกประเภทข้อมูล</option>
                                <option value="string" {{ old('type', $settingsBackup->type) == 'string' ? 'selected' : '' }}>String (ข้อความ)</option>
                                <option value="boolean" {{ old('type', $settingsBackup->type) == 'boolean' ? 'selected' : '' }}>Boolean (ใช่/ไม่ใช่)</option>
                                <option value="integer" {{ old('type', $settingsBackup->type) == 'integer' ? 'selected' : '' }}>Integer (ตัวเลข)</option>
                                <option value="json" {{ old('type', $settingsBackup->type) == 'json' ? 'selected' : '' }}>JSON (ข้อมูลโครงสร้าง)</option>
                            </select>
                            @error('type')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">ข้อมูลเพิ่มเติม</h4>
                        
                        <div class="form-group">
                            <label class="form-label">คำอธิบาย</label>
                            <textarea name="description" rows="3" 
                                      class="form-input-custom focus-blue @error('description') border-red-500 @enderror" 
                                      placeholder="อธิบายการใช้งานของการตั้งค่านี้">{{ old('description', $settingsBackup->description) }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">กลุ่มการตั้งค่า <span class="text-red-500">*</span></label>
                            <select name="group" class="form-input-custom focus-blue @error('group') border-red-500 @enderror" required>
                                <option value="">เลือกกลุ่มการตั้งค่า</option>
                                <option value="general" {{ old('group', $settingsBackup->group) == 'general' ? 'selected' : '' }}>ทั่วไป</option>
                                <option value="auto_backup" {{ old('group', $settingsBackup->group) == 'auto_backup' ? 'selected' : '' }}>สำรองอัตโนมัติ</option>
                                <option value="notification" {{ old('group', $settingsBackup->group) == 'notification' ? 'selected' : '' }}>การแจ้งเตือน</option>
                                <option value="storage" {{ old('group', $settingsBackup->group) == 'storage' ? 'selected' : '' }}>การจัดเก็บ</option>
                                <option value="security" {{ old('group', $settingsBackup->group) == 'security' ? 'selected' : '' }}>ความปลอดภัย</option>
                            </select>
                            @error('group')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">ลำดับการแสดงผล</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $settingsBackup->sort_order) }}" 
                                   min="0" max="999" 
                                   class="form-input-custom focus-blue @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">ใช้สำหรับเรียงลำดับการแสดงผล (0 = แสดงก่อน)</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">สถานะการใช้งาน</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', $settingsBackup->is_active) == '1' ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm">เปิดใช้งาน</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active', $settingsBackup->is_active) == '0' ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm">ปิดใช้งาน</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                    <a href="{{ route('backend.settings-backup.index') }}" class="btn-custom-secondary">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </a>
                    <button type="submit" class="btn-custom-primary">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Value Preview -->
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-eye mr-2"></i>
                ตัวอย่างค่าปัจจุบัน
            </h3>
        </div>
        <div class="card-body">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">ข้อมูลการตั้งค่า</h4>
                        <ul class="space-y-1 text-sm text-gray-600">
                            <li><strong>รหัส:</strong> {{ $settingsBackup->key }}</li>
                            <li><strong>ประเภท:</strong> {{ $settingsBackup->type }}</li>
                            <li><strong>กลุ่ม:</strong> {{ $settingsBackup->group }}</li>
                            <li><strong>สถานะ:</strong> 
                                <span class="badge {{ $settingsBackup->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $settingsBackup->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">ค่าปัจจุบัน</h4>
                        <div class="bg-white p-3 rounded border">
                            <code class="text-sm">{{ $settingsBackup->value }}</code>
                        </div>
                        @if($settingsBackup->description)
                            <p class="text-sm text-gray-600 mt-2">{{ $settingsBackup->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card mt-6 border-red-200">
        <div class="card-header bg-red-50">
            <h3 class="card-title text-red-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                เขตอันตราย
            </h3>
        </div>
        <div class="card-body">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">ลบการตั้งค่านี้</h4>
                    <p class="text-sm text-gray-600">การลบการตั้งค่านี้จะไม่สามารถกู้คืนได้ กรุณาตรวจสอบให้แน่ใจก่อนดำเนินการ</p>
                </div>
                <button onclick="deleteSetting()" class="btn-custom-danger">
                    <i class="fas fa-trash mr-2"></i>
                    ลบการตั้งค่า
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const key = document.querySelector('input[name="key"]').value;
    const value = document.querySelector('input[name="value"]').value;
    const type = document.querySelector('select[name="type"]').value;
    const group = document.querySelector('select[name="group"]').value;

    // Validate key format
    if (!/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(key)) {
        e.preventDefault();
        Swal.fire({
            title: 'ข้อผิดพลาด',
            text: 'รหัสการตั้งค่าต้องเริ่มต้นด้วยตัวอักษรหรือ _ และใช้ได้เฉพาะตัวอักษร ตัวเลข และ _ เท่านั้น',
            icon: 'error'
        });
        return;
    }

    // Validate value based on type
    if (type === 'boolean') {
        if (!['true', 'false', '1', '0'].includes(value.toLowerCase())) {
            e.preventDefault();
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: 'ค่าสำหรับประเภท Boolean ต้องเป็น true, false, 1 หรือ 0 เท่านั้น',
                icon: 'error'
            });
            return;
        }
    } else if (type === 'integer') {
        if (!/^\d+$/.test(value)) {
            e.preventDefault();
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: 'ค่าสำหรับประเภท Integer ต้องเป็นตัวเลขเท่านั้น',
                icon: 'error'
            });
            return;
        }
    } else if (type === 'json') {
        try {
            JSON.parse(value);
        } catch (error) {
            e.preventDefault();
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: 'ค่าสำหรับประเภท JSON ไม่ถูกต้อง',
                icon: 'error'
            });
            return;
        }
    }

    // Show loading
    Swal.fire({
        title: 'กำลังบันทึก',
        text: 'กรุณารอสักครู่...',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false
    });
});

// Delete setting function
function deleteSetting() {
    Swal.fire({
        title: 'ยืนยันการลบ',
        text: 'คุณต้องการลบการตั้งค่านี้หรือไม่? การดำเนินการนี้ไม่สามารถกู้คืนได้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("backend.settings-backup.destroy", $settingsBackup) }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add method override
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Auto-format value based on type
document.querySelector('select[name="type"]').addEventListener('change', function() {
    const valueInput = document.querySelector('input[name="value"]');
    const type = this.value;
    
    if (type === 'boolean') {
        valueInput.placeholder = 'true หรือ false';
    } else if (type === 'integer') {
        valueInput.placeholder = 'ตัวเลข เช่น 10';
    } else if (type === 'json') {
        valueInput.placeholder = '{"key": "value"}';
    } else {
        valueInput.placeholder = 'ข้อความ';
    }
});
</script>
@endpush
