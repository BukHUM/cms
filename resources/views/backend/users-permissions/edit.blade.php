@extends('backend.layouts.app')

@section('title', 'แก้ไขสิทธิ์การเข้าถึง')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="page-title">แก้ไขสิทธิ์การเข้าถึง</h1>
            <p class="page-description">แก้ไขข้อมูลสิทธิ์: {{ $permission->display_name }}</p>
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
                    <form method="POST" action="{{ route('backend.permissions.update', $permission) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    ชื่อสิทธิ์ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" class="form-input @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name', $permission->name) }}" 
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
                                       id="display_name" name="display_name" value="{{ old('display_name', $permission->display_name) }}" 
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
                                        <option value="{{ $group }}" 
                                                {{ old('group', $permission->group) == $group ? 'selected' : '' }}>
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
                                      placeholder="อธิบายรายละเอียดของสิทธิ์นี้">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="flex items-center">
                                <input class="form-check-input mr-3" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $permission->is_active) ? 'checked' : '' }}>
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
                                <i class="fas fa-save mr-2"></i> บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <!-- Permission Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ข้อมูลสิทธิ์</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">ชื่อสิทธิ์:</span>
                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $permission->name }}</code>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">สถานะ:</span>
                            @if($permission->is_active)
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            @else
                                <span class="badge badge-secondary">ปิดใช้งาน</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">วันที่สร้าง:</span>
                            <span class="text-sm text-gray-500">{{ $permission->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">อัปเดตล่าสุด:</span>
                            <span class="text-sm text-gray-500">{{ $permission->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">การใช้งาน</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-blue-600">{{ $permission->roles->count() }}</div>
                        <div class="text-sm text-gray-600">บทบาทที่ใช้สิทธิ์นี้</div>
                    </div>
                    
                    @if($permission->roles->count() > 0)
                        <div class="space-y-2">
                            <h4 class="text-sm font-medium text-gray-700">บทบาทที่เกี่ยวข้อง:</h4>
                            <div class="flex flex-wrap gap-1">
                                @foreach($permission->roles as $role)
                                    <span class="badge badge-primary">{{ $role->display_name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-2xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">ยังไม่มีบทบาทที่ใช้สิทธิ์นี้</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">การดำเนินการ</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('backend.permissions.show', $permission) }}" class="btn-primary w-full text-center">
                            <i class="fas fa-eye mr-2"></i> ดูรายละเอียด
                        </a>
                        
                        <form method="POST" action="{{ route('backend.permissions.toggle-status', $permission) }}" 
                              onsubmit="return confirmToggleStatus('{{ $permission->name }}')">
                            @csrf
                            <button type="submit" class="w-full {{ $permission->is_active ? 'btn-secondary' : 'btn-success' }}">
                                <i class="fas fa-{{ $permission->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $permission->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}
                            </button>
                        </form>
                        
                        @if($permission->roles->count() == 0)
                            <form method="POST" action="{{ route('backend.permissions.destroy', $permission) }}" 
                                  onsubmit="return confirmDelete('{{ $permission->name }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger w-full">
                                    <i class="fas fa-trash mr-2"></i> ลบสิทธิ์
                                </button>
                            </form>
                        @else
                            <button type="button" class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-md cursor-not-allowed" 
                                    title="ไม่สามารถลบได้ เนื่องจากถูกใช้งานโดยบทบาทอื่น">
                                <i class="fas fa-trash mr-2"></i> ลบสิทธิ์
                            </button>
                        @endif
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

function confirmToggleStatus(permissionName) {
    return Swal.fire({
        title: 'ยืนยันการเปลี่ยนสถานะ',
        text: `คุณต้องการเปลี่ยนสถานะของสิทธิ์ "${permissionName}" ใช่หรือไม่?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        return result.isConfirmed;
    });
}

function confirmDelete(permissionName) {
    return Swal.fire({
        title: 'ยืนยันการลบ',
        text: `คุณต้องการลบสิทธิ์ "${permissionName}" ใช่หรือไม่?\n\nการดำเนินการนี้ไม่สามารถยกเลิกได้`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        return result.isConfirmed;
    });
}
</script>
@endpush
@endsection
