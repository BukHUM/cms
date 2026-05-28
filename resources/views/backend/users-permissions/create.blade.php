@extends('backend.layouts.app')

@section('title', 'เพิ่มสิทธิ์การเข้าถึง')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="page-title">เพิ่มสิทธิ์การเข้าถึง</h1>
            <p class="page-description">สร้างสิทธิ์การเข้าถึงใหม่</p>
        </div>
        <div>
            <a href="{{ route('backend.permissions.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> กลับ
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ข้อมูลสิทธิ์การเข้าถึง</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.permissions.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    ชื่อสิทธิ์ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" class="form-input @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="เช่น user.create" required>
                                <div class="text-sm text-gray-500 mt-1">
                                    ชื่อสิทธิ์ต้องเป็นภาษาอังกฤษและไม่ซ้ำกับสิทธิ์อื่น
                                </div>
                                @error('name')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="display_name" class="form-label">
                                    ชื่อแสดง <span class="text-red-500">*</span>
                                </label>
                                <input type="text" class="form-input @error('display_name') border-red-500 @enderror" 
                                       id="display_name" name="display_name" value="{{ old('display_name') }}" 
                                       placeholder="เช่น สร้างผู้ใช้" required>
                                @error('display_name')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="group" class="form-label">
                                    กลุ่มสิทธิ์ <span class="text-red-500">*</span>
                                </label>
                                <select class="form-input @error('group') border-red-500 @enderror" 
                                        id="group" name="group" required>
                                    <option value="">เลือกกลุ่มสิทธิ์</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}" {{ old('group') == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                    <option value="custom" {{ old('group') == 'custom' ? 'selected' : '' }}>
                                        กำหนดเอง
                                    </option>
                                </select>
                                @error('group')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group" id="custom-group-container" style="display: none;">
                                <label for="custom_group" class="form-label">กลุ่มสิทธิ์กำหนดเอง</label>
                                <input type="text" class="form-input" id="custom_group" name="custom_group" 
                                       value="{{ old('custom_group') }}" placeholder="ระบุชื่อกลุ่มใหม่">
                                <div class="text-sm text-gray-500 mt-1">กรอกเมื่อเลือก "กำหนดเอง" ในกลุ่มสิทธิ์</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea class="form-input @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="อธิบายรายละเอียดของสิทธิ์นี้">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="flex items-center">
                                <input class="form-check-input mr-3" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-label mb-0" for="is_active">
                                    เปิดใช้งานสิทธิ์นี้
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('backend.permissions.index') }}" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <!-- Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">คำแนะนำ</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-medium text-blue-900 mb-2">การตั้งชื่อสิทธิ์</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• ใช้รูปแบบ <code class="bg-blue-100 px-1 rounded">resource.action</code></li>
                                    <li>• เช่น: <code class="bg-blue-100 px-1 rounded">user.create</code>, <code class="bg-blue-100 px-1 rounded">post.edit</code></li>
                                    <li>• ใช้ตัวอักษรพิมพ์เล็กและจุดเป็นตัวคั่น</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-medium text-yellow-900 mb-2">ข้อควรระวัง</h4>
                                <ul class="text-sm text-yellow-800 space-y-1">
                                    <li>• ชื่อสิทธิ์ต้องไม่ซ้ำกับสิทธิ์อื่น</li>
                                    <li>• การลบสิทธิ์จะไม่สามารถยกเลิกได้</li>
                                    <li>• สิทธิ์ที่ถูกใช้งานโดยบทบาทจะไม่สามารถลบได้</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">สถิติสิทธิ์</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Permission::count() }}</div>
                            <div class="text-sm text-gray-600">สิทธิ์ทั้งหมด</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ \App\Models\Permission::active()->count() }}</div>
                            <div class="text-sm text-gray-600">เปิดใช้งาน</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupSelect = document.getElementById('group');
    const customGroupContainer = document.getElementById('custom-group-container');
    const customGroupInput = document.getElementById('custom_group');
    
    function toggleCustomGroup() {
        if (groupSelect.value === 'custom') {
            customGroupContainer.style.display = 'block';
            customGroupInput.required = true;
        } else {
            customGroupContainer.style.display = 'none';
            customGroupInput.required = false;
            customGroupInput.value = '';
        }
    }
    
    groupSelect.addEventListener('change', toggleCustomGroup);
    toggleCustomGroup(); // Initial call
    
    // Form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        if (groupSelect.value === 'custom' && !customGroupInput.value.trim()) {
            e.preventDefault();
            Swal.fire({
                title: 'คำเตือน',
                text: 'กรุณาระบุชื่อกลุ่มสิทธิ์กำหนดเอง',
                icon: 'warning',
                confirmButtonText: 'ตกลง'
            });
            customGroupInput.focus();
            return false;
        }
        
        // Set the group value to custom_group if custom is selected
        if (groupSelect.value === 'custom') {
            groupSelect.value = customGroupInput.value.trim();
        }
    });
});
</script>
@endpush
@endsection
