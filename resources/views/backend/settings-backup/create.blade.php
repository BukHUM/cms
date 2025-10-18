@extends('backend.layouts.app')

@section('title', 'เพิ่มการตั้งค่าการสำรองข้อมูล')
@section('page-title', 'เพิ่มการตั้งค่าการสำรองข้อมูล')
@section('page-description', 'เพิ่มการตั้งค่าใหม่สำหรับการสำรองข้อมูล')

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

    <!-- Create Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus mr-2"></i>
                เพิ่มการตั้งค่าการสำรองข้อมูล
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('backend.settings-backup.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 border-b pb-2">ข้อมูลพื้นฐาน</h4>
                        
                        <div class="form-group">
                            <label class="form-label">รหัสการตั้งค่า <span class="text-red-500">*</span></label>
                            <input type="text" name="key" value="{{ old('key') }}" 
                                   class="form-input-custom focus-blue @error('key') border-red-500 @enderror" 
                                   placeholder="backup_auto_enabled" required>
                            @error('key')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">ใช้ตัวอักษรภาษาอังกฤษและเครื่องหมาย _ เท่านั้น</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ค่าการตั้งค่า <span class="text-red-500">*</span></label>
                            <input type="text" name="value" value="{{ old('value') }}" 
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
                                <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String (ข้อความ)</option>
                                <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean (ใช่/ไม่ใช่)</option>
                                <option value="integer" {{ old('type') == 'integer' ? 'selected' : '' }}>Integer (ตัวเลข)</option>
                                <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON (ข้อมูลโครงสร้าง)</option>
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
                                      placeholder="อธิบายการใช้งานของการตั้งค่านี้">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">กลุ่มการตั้งค่า <span class="text-red-500">*</span></label>
                            <select name="group" class="form-input-custom focus-blue @error('group') border-red-500 @enderror" required>
                                <option value="">เลือกกลุ่มการตั้งค่า</option>
                                <option value="general" {{ old('group') == 'general' ? 'selected' : '' }}>ทั่วไป</option>
                                <option value="auto_backup" {{ old('group') == 'auto_backup' ? 'selected' : '' }}>สำรองอัตโนมัติ</option>
                                <option value="notification" {{ old('group') == 'notification' ? 'selected' : '' }}>การแจ้งเตือน</option>
                                <option value="storage" {{ old('group') == 'storage' ? 'selected' : '' }}>การจัดเก็บ</option>
                                <option value="security" {{ old('group') == 'security' ? 'selected' : '' }}>ความปลอดภัย</option>
                            </select>
                            @error('group')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">ลำดับการแสดงผล</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" 
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
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm">เปิดใช้งาน</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="mr-2">
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
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-question-circle mr-2"></i>
                คำแนะนำการใช้งาน
            </h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">ประเภทข้อมูล</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><strong>String:</strong> ข้อความทั่วไป เช่น "backup_enabled"</li>
                        <li><strong>Boolean:</strong> ค่าใช่/ไม่ใช่ เช่น true, false</li>
                        <li><strong>Integer:</strong> ตัวเลข เช่น 10, 100</li>
                        <li><strong>JSON:</strong> ข้อมูลโครงสร้าง เช่น {"key": "value"}</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">กลุ่มการตั้งค่า</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><strong>ทั่วไป:</strong> การตั้งค่าพื้นฐาน</li>
                        <li><strong>สำรองอัตโนมัติ:</strong> การตั้งค่าการสำรองอัตโนมัติ</li>
                        <li><strong>การแจ้งเตือน:</strong> การตั้งค่าการแจ้งเตือน</li>
                        <li><strong>การจัดเก็บ:</strong> การตั้งค่าการจัดเก็บไฟล์</li>
                        <li><strong>ความปลอดภัย:</strong> การตั้งค่าความปลอดภัย</li>
                    </ul>
                </div>
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

// Auto-generate key from description
document.querySelector('textarea[name="description"]').addEventListener('input', function() {
    const description = this.value;
    const keyInput = document.querySelector('input[name="key"]');
    
    if (description && !keyInput.value) {
        // Convert Thai description to English key
        const key = description
            .toLowerCase()
            .replace(/[^\w\s]/g, '')
            .replace(/\s+/g, '_')
            .replace(/^_+|_+$/g, '');
        
        if (key) {
            keyInput.value = key;
        }
    }
});
</script>
@endpush
