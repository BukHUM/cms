@extends('backend.layouts.app')

@section('title', 'เพิ่มผู้ใช้งาน')
@section('page-title', 'เพิ่มผู้ใช้งาน')
@section('page-description', 'สร้างผู้ใช้งานใหม่ในระบบ')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-user-plus mr-2"></i>
                ข้อมูลผู้ใช้งานใหม่
            </h3>
        </div>
        
        <form action="{{ route('backend.users.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        ข้อมูลพื้นฐาน
                    </h4>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            ชื่อผู้ใช้งาน <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="กรอกชื่อผู้ใช้งาน"
                               required>
                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            อีเมล์ <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="กรอกอีเมล์"
                               required>
                        @error('email')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ</label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                               placeholder="กรอกชื่อ">
                        @error('first_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">นามสกุล</label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                               placeholder="กรอกนามสกุล">
                        @error('last_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                               placeholder="กรอกเบอร์โทรศัพท์">
                        @error('phone')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Security & Roles -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        ความปลอดภัยและบทบาท
                    </h4>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            รหัสผ่าน <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-10 @error('password') border-red-500 @enderror"
                                   placeholder="กรอกรหัสผ่าน"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            ยืนยันรหัสผ่าน <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-10 @error('password_confirmation') border-red-500 @enderror"
                                   placeholder="ยืนยันรหัสผ่าน"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">บทบาท</label>
                        <div class="space-y-2">
                            @forelse($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->id }}"
                                           {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
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
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">เปิดใช้งานบัญชีนี้</span>
                        </label>
                    </div>
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
                        บันทึกข้อมูล
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

// Password strength indicator
const passwordInput = document.getElementById('password');
if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = getPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });
}

function getPasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    return score;
}

function updatePasswordStrengthIndicator(strength) {
    // You can implement password strength indicator here
    console.log('Password strength:', strength);
}
</script>
@endpush
