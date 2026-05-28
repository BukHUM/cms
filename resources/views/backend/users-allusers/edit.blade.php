@extends('backend.layouts.app')

@section('title', 'แก้ไขผู้ใช้งาน')
@section('page-title', 'แก้ไขผู้ใช้งาน')
@section('page-description', 'แก้ไขข้อมูลผู้ใช้งานในระบบ')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-user-edit mr-2"></i>
                <span class="hidden sm:inline">แก้ไขข้อมูลผู้ใช้งาน: {{ $user->name }}</span>
                <span class="sm:hidden">แก้ไขผู้ใช้งาน</span>
            </h3>
        </div>
        
        <form action="{{ route('backend.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        ข้อมูลพื้นฐาน
                    </h4>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            ชื่อผู้ใช้งาน <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="form-input @error('name') border-red-500 @enderror"
                               placeholder="กรอกชื่อผู้ใช้งาน"
                               required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            อีเมล์ <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="form-input @error('email') border-red-500 @enderror"
                               placeholder="กรอกอีเมล์"
                               required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name" class="form-label">ชื่อ</label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $user->first_name) }}"
                               class="form-input @error('first_name') border-red-500 @enderror"
                               placeholder="กรอกชื่อ">
                        @error('first_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label">นามสกุล</label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name', $user->last_name) }}"
                               class="form-input @error('last_name') border-red-500 @enderror"
                               placeholder="กรอกนามสกุล">
                        @error('last_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="form-input @error('phone') border-red-500 @enderror"
                               placeholder="กรอกเบอร์โทรศัพท์">
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Security & Roles -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        ความปลอดภัยและบทบาท
                    </h4>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">รหัสผ่านใหม่</label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input @error('password') border-red-500 @enderror pr-10"
                                   placeholder="กรอกรหัสผ่านใหม่ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)">
                            <button type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</div>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-input @error('password_confirmation') border-red-500 @enderror pr-10"
                                   placeholder="ยืนยันรหัสผ่านใหม่">
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">บทบาท</label>
                        <div class="space-y-2">
                            @forelse($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->id }}"
                                           {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">
                                        {{ $role->display_name }}
                                        @if($role->description)
                                            <span class="text-gray-500">- {{ $role->description }}</span>
                                        @endif
                                    </span>
                                </label>
                            @empty
                                <p class="text-gray-500 text-sm">ไม่มีบทบาทที่ใช้งานได้</p>
                            @endforelse
                        </div>
                        @error('roles')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">เปิดใช้งานบัญชีนี้</span>
                        </label>
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
                        <span class="text-gray-900 block sm:inline">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">อัปเดตล่าสุด:</span>
                        <span class="text-gray-900 block sm:inline">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($user->last_login_at)
                        <div>
                            <span class="font-medium text-gray-700">เข้าสู่ระบบล่าสุด:</span>
                            <span class="text-gray-900 block sm:inline">{{ $user->last_login_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($user->last_login_ip)
                            <div>
                                <span class="font-medium text-gray-700">IP ล่าสุด:</span>
                                <span class="text-gray-900 block sm:inline">{{ $user->last_login_ip }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('backend.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto order-2 sm:order-1">
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
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password confirmation validation
const passwordConfirmationInput = document.getElementById('password_confirmation');
if (passwordConfirmationInput) {
    passwordConfirmationInput.addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password && confirmation && password !== confirmation) {
            this.setCustomValidity('รหัสผ่านไม่ตรงกัน');
        } else {
            this.setCustomValidity('');
        }
    });
}

const passwordInput = document.getElementById('password');
if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        const confirmation = document.getElementById('password_confirmation');
        if (confirmation && confirmation.value) {
            confirmation.dispatchEvent(new Event('input'));
        }
    });
}
</script>
@endpush
