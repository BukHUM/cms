@extends('backend.layouts.app')

@section('title', 'การตั้งค่าความปลอดภัย')

@section('content')
<div class="main-content-area">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                <i class="fas fa-shield-alt mr-2"></i>
                การตั้งค่าความปลอดภัย
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                จัดการการตั้งค่าความปลอดภัยและนโยบายรหัสผ่าน
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <button onclick="resetToDefault()" 
                    class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 w-full sm:w-auto">
                <i class="fas fa-undo mr-2"></i>
                รีเซ็ตเป็นค่าเริ่มต้น
            </button>
        </div>
    </div>

    <!-- Security Settings Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('backend.settings-security.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Password Policy Section -->
            <div class="mb-8">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-lock mr-2"></i>
                        นโยบายรหัสผ่าน
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">กำหนดความต้องการสำหรับรหัสผ่าน</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Password Length -->
                    <div>
                        <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-ruler mr-1"></i>
                            ความยาวขั้นต่ำ <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="password_min_length" 
                               name="password_min_length" 
                               value="{{ old('password_min_length', '8') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password_min_length') border-red-500 @enderror"
                               min="6" max="50"
                               required>
                        @error('password_min_length')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">ตัวอักษร (แนะนำ: 12)</p>
                    </div>

                    <!-- Password Expiry -->
                    <div>
                        <label for="password_expiry_days" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            หมดอายุ (วัน)
                        </label>
                        <input type="number" 
                               id="password_expiry_days" 
                               name="password_expiry_days" 
                               value="{{ old('password_expiry_days', '90') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password_expiry_days') border-red-500 @enderror"
                               min="30" max="365">
                        @error('password_expiry_days')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">วัน (แนะนำ: 90)</p>
                    </div>

                    <!-- Password History -->
                    <div>
                        <label for="password_history_count" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-history mr-1"></i>
                            ประวัติรหัสผ่าน
                        </label>
                        <input type="number" 
                               id="password_history_count" 
                               name="password_history_count" 
                               value="{{ old('password_history_count', '5') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password_history_count') border-red-500 @enderror"
                               min="3" max="12">
                        @error('password_history_count')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">จำนวนรหัสผ่านเก่า</p>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">ความต้องการรหัสผ่าน</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="password_require_uppercase" 
                                   name="password_require_uppercase" 
                                   value="1"
                                   {{ old('password_require_uppercase', '1') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="password_require_uppercase" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-font mr-1"></i>
                                ตัวอักษรพิมพ์ใหญ่
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="password_require_lowercase" 
                                   name="password_require_lowercase" 
                                   value="1"
                                   {{ old('password_require_lowercase', '1') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="password_require_lowercase" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-font mr-1"></i>
                                ตัวอักษรพิมพ์เล็ก
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="password_require_numbers" 
                                   name="password_require_numbers" 
                                   value="1"
                                   {{ old('password_require_numbers', '1') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="password_require_numbers" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-hashtag mr-1"></i>
                                ตัวเลข
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="password_require_special" 
                                   name="password_require_special" 
                                   value="1"
                                   {{ old('password_require_special', '1') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="password_require_special" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-exclamation mr-1"></i>
                                อักขระพิเศษ
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Security Section -->
            <div class="mb-8">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        ความปลอดภัยการล็อกอิน
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">การตั้งค่าการล็อกอินและ session</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Max Login Attempts -->
                    <div>
                        <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-times-circle mr-1"></i>
                            จำนวนครั้งสูงสุด <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="max_login_attempts" 
                               name="max_login_attempts" 
                               value="{{ old('max_login_attempts', '5') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('max_login_attempts') border-red-500 @enderror"
                               min="3" max="10"
                               required>
                        @error('max_login_attempts')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">ครั้ง (แนะนำ: 5)</p>
                    </div>

                    <!-- Lockout Duration -->
                    <div>
                        <label for="lockout_duration" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clock mr-1"></i>
                            ระยะเวลาล็อก <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="lockout_duration" 
                               name="lockout_duration" 
                               value="{{ old('lockout_duration', '15') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('lockout_duration') border-red-500 @enderror"
                               min="5" max="60"
                               required>
                        @error('lockout_duration')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">นาที (แนะนำ: 15)</p>
                    </div>

                    <!-- Session Timeout -->
                    <div>
                        <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-hourglass-half mr-1"></i>
                            Session Timeout <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="session_timeout" 
                               name="session_timeout" 
                               value="{{ old('session_timeout', '120') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('session_timeout') border-red-500 @enderror"
                               min="15" max="480"
                               required>
                        @error('session_timeout')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">นาที (แนะนำ: 120)</p>
                    </div>
                </div>

                <!-- Security Features -->
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">ฟีเจอร์ความปลอดภัย</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_2fa" 
                                   name="enable_2fa" 
                                   value="1"
                                   {{ old('enable_2fa', '0') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="enable_2fa" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-mobile-alt mr-1"></i>
                                การยืนยันตัวตนสองขั้นตอน (2FA)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_captcha" 
                                   name="enable_captcha" 
                                   value="1"
                                   {{ old('enable_captcha', '0') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="enable_captcha" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-robot mr-1"></i>
                                CAPTCHA
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_password_history" 
                                   name="enable_password_history" 
                                   value="1"
                                   {{ old('enable_password_history', '1') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="enable_password_history" class="ml-2 block text-sm font-medium text-gray-700">
                                <i class="fas fa-history mr-1"></i>
                                ประวัติรหัสผ่าน
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <button type="button" 
                        onclick="resetToDefault()"
                        class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full sm:w-auto">
                    <i class="fas fa-undo mr-2"></i>
                    รีเซ็ตเป็นค่าเริ่มต้น
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                    <i class="fas fa-save mr-2"></i>
                    บันทึกการตั้งค่า
                </button>
            </div>
        </form>
    </div>

    <!-- Reset Form -->
    <form id="reset-form" method="POST" action="{{ route('backend.settings-security.reset') }}" style="display: none;">
        @csrf
        @method('POST')
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Reset to default
    function resetToDefault() {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการรีเซ็ตการตั้งค่าความปลอดภัยเป็นค่าเริ่มต้นหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, รีเซ็ตเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('reset-form');
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>
@endpush