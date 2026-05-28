@extends('backend.layouts.app')

@section('title', 'การตั้งค่าอีเมล์')
@section('page-title', 'การตั้งค่าอีเมล์')
@section('page-description', 'จัดการการตั้งค่าการส่งอีเมล์และ SMTP')

@section('content')
<div class="main-content-area">
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-2 mb-6">
        <button onclick="resetToDefault()" 
                class="btn-warning w-full sm:w-auto">
            <i class="fas fa-undo mr-2"></i>
            รีเซ็ตเป็นค่าเริ่มต้น
        </button>
        <button id="test-email-btn" onclick="testEmail()" 
                class="btn-success w-full sm:w-auto">
            <i class="fas fa-paper-plane mr-2"></i>
            ทดสอบการส่งอีเมล์
        </button>
    </div>

    <!-- Email Settings Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="{{ route('backend.settings-email.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Mail Driver -->
                    <div>
                        <label for="mail_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Mail Driver
                        </label>
                        <select id="mail_driver" 
                                name="mail_driver" 
                                onchange="updateMailConfig()"
                                class="form-input @error('mail_driver') border-red-500 @enderror">
                            <option value="gmail" {{ old('mail_driver', $emailSettings['mail_driver']->value ?? '') == 'gmail' ? 'selected' : '' }}>Gmail</option>
                            <option value="office365" {{ old('mail_driver', $emailSettings['mail_driver']->value ?? '') == 'office365' ? 'selected' : '' }}>Office 365</option>
                            <option value="mailtrap" {{ old('mail_driver', $emailSettings['mail_driver']->value ?? '') == 'mailtrap' ? 'selected' : '' }}>Mailtrap</option>
                            <option value="hotmail" {{ old('mail_driver', $emailSettings['mail_driver']->value ?? '') == 'hotmail' ? 'selected' : '' }}>Hotmail</option>
                            <option value="smtp" {{ old('mail_driver', $emailSettings['mail_driver']->value ?? '') == 'smtp' ? 'selected' : '' }}>SMTP (Custom)</option>
                        </select>
                        @error('mail_driver')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">เลือกวิธีการส่งอีเมล ระบบจะตั้งค่าอัตโนมัติตามที่เลือก</p>
                    </div>

                    <!-- Mail Port -->
                    <div>
                        <label for="mail_smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Mail Port
                        </label>
                        <input type="number" 
                               id="mail_smtp_port" 
                               name="mail_smtp_port" 
                               value="{{ old('mail_smtp_port', $emailSettings['mail_smtp_port']->value ?? '') }}"
                               class="form-input @error('mail_smtp_port') border-red-500 @enderror"
                               placeholder="587"
                               min="1" max="65535">
                        @error('mail_smtp_port')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">พอร์ตมาตรฐาน: 587 (TLS), 465 (SSL), 25 (ไม่เข้ารหัส)</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="mail_smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="mail_smtp_password" 
                                   name="mail_smtp_password" 
                                   value="{{ old('mail_smtp_password', $emailSettings['mail_smtp_password']->value ?? '') }}"
                                   class="form-input pr-10 @error('mail_smtp_password') border-red-500 @enderror"
                                   placeholder="รหัสผ่านอีเมล">
                            <button type="button" 
                                    onclick="togglePassword('mail_smtp_password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="mail_smtp_password_icon"></i>
                            </button>
                        </div>
                        @error('mail_smtp_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">รหัสผ่านอีเมล</p>
                    </div>

                    <!-- From Name -->
                    <div>
                        <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            From Name
                        </label>
                        <input type="text" 
                               id="mail_from_name" 
                               name="mail_from_name" 
                               value="{{ old('mail_from_name', $emailSettings['mail_from_name']->value ?? '') }}"
                               class="form-input @error('mail_from_name') border-red-500 @enderror"
                               placeholder="Laravel Backend">
                        @error('mail_from_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ชื่อที่ผู้รับจะเห็น เช่น "ระบบแจ้งเตือน", "ทีมสนับสนุน"</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Mail Host -->
                    <div>
                        <label for="mail_smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Mail Host
                        </label>
                        <input type="text" 
                               id="mail_smtp_host" 
                               name="mail_smtp_host" 
                               value="{{ old('mail_smtp_host', $emailSettings['mail_smtp_host']->value ?? '') }}"
                               class="form-input @error('mail_smtp_host') border-red-500 @enderror"
                               placeholder="smtp.gmail.com">
                        @error('mail_smtp_host')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ตัวอย่าง: smtp.gmail.com, smtp.outlook.com, mail.yourdomain.com</p>
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="mail_smtp_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Username
                        </label>
                        <input type="text" 
                               id="mail_smtp_username" 
                               name="mail_smtp_username" 
                               value="{{ old('mail_smtp_username', $emailSettings['mail_smtp_username']->value ?? '') }}"
                               class="form-input @error('mail_smtp_username') border-red-500 @enderror"
                               placeholder="your-email@gmail.com">
                        @error('mail_smtp_username')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">อีเมลที่ใช้สำหรับการส่ง หรือ username ของเซิร์ฟเวอร์อีเมล</p>
                    </div>

                    <!-- Encryption -->
                    <div>
                        <label for="mail_smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Encryption
                        </label>
                        <input type="text" 
                               id="mail_smtp_encryption" 
                               name="mail_smtp_encryption" 
                               value="{{ old('mail_smtp_encryption', $emailSettings['mail_smtp_encryption']->value ?? '') }}"
                               class="form-input @error('mail_smtp_encryption') border-red-500 @enderror"
                               placeholder="TLS">
                        @error('mail_smtp_encryption')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">แนะนำใช้ TLS สำหรับความปลอดภัย หรือ SSL สำหรับพอร์ต 465</p>
                    </div>

                    <!-- Enable Notifications -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            การแจ้งเตือน
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_email_notifications" 
                                   name="enable_email_notifications" 
                                   value="1"
                                   {{ old('enable_email_notifications', $emailSettings['enable_email_notifications']->value ?? '1') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="enable_email_notifications" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                เปิดใช้งานการแจ้งเตือนทางอีเมล
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ส่งการแจ้งเตือนต่างๆ ทางอีเมล</p>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-cogs mr-2"></i>
                    การตั้งค่าขั้นสูง
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Queue Enabled -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tasks mr-1"></i>
                            Mail Queue
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="mail_queue_enabled" 
                                   name="mail_queue_enabled" 
                                   value="1"
                                   {{ old('mail_queue_enabled', $emailSettings['mail_queue_enabled']->value ?? '0') == '1' ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="mail_queue_enabled" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                เปิดใช้งาน Mail Queue
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ส่งอีเมลผ่าน Queue เพื่อประสิทธิภาพที่ดีขึ้น</p>
                    </div>

                    <!-- Retry Attempts -->
                    <div>
                        <label for="mail_retry_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-redo mr-1"></i>
                            จำนวนครั้งลองใหม่
                        </label>
                        <input type="number" 
                               id="mail_retry_attempts" 
                               name="mail_retry_attempts" 
                               value="{{ old('mail_retry_attempts', $emailSettings['mail_retry_attempts']->value ?? '3') }}"
                               class="form-input @error('mail_retry_attempts') border-red-500 @enderror"
                               placeholder="3"
                               min="1" max="10">
                        @error('mail_retry_attempts')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">จำนวนครั้งในการลองส่งอีเมลใหม่เมื่อล้มเหลว</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" 
                        onclick="resetToDefault()"
                        class="btn-secondary w-full sm:w-auto">
                    <i class="fas fa-undo mr-2"></i>
                    รีเซ็ตเป็นค่าเริ่มต้น
                </button>
                <button type="submit" 
                        class="btn-primary w-full sm:w-auto">
                    <i class="fas fa-save mr-2"></i>
                    บันทึกการตั้งค่า
                </button>
            </div>
        </form>
    </div>

    <!-- Test Email Modal -->
    <div id="test-email-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-paper-plane mr-2"></i>
                        ทดสอบการส่งอีเมล์
                    </h3>
                </div>
                <form id="test-email-form" class="p-6">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="space-y-4">
                        <div>
                            <label for="test_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                อีเมลปลายทาง <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="test_email" 
                                   name="test_email" 
                                   class="form-input"
                                   placeholder="test@example.com"
                                   required>
                        </div>
                        <div>
                            <label for="test_subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                หัวข้อ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="test_subject" 
                                   name="test_subject" 
                                   class="form-input"
                                   placeholder="ทดสอบการส่งอีเมล์"
                                   required>
                        </div>
                        <div>
                            <label for="test_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                ข้อความ <span class="text-red-500">*</span>
                            </label>
                            <textarea id="test_message" 
                                      name="test_message" 
                                      rows="4"
                                      class="form-input"
                                      placeholder="นี่คือข้อความทดสอบการส่งอีเมล์"
                                      required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeTestEmailModal()"
                                class="btn-secondary">
                            ยกเลิก
                        </button>
                        <button type="submit" 
                                class="btn-success">
                            <i class="fas fa-paper-plane mr-2"></i>
                            ส่งทดสอบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Form -->
    <form id="reset-form" method="POST" action="{{ route('backend.settings-email.reset') }}" style="display: none;">
        @csrf
        @method('POST')
    </form>
</div>
@endsection

@push('styles')
<style>
    /* Custom CSS removed - using Tailwind utilities instead */
    /* button:disabled -> disabled:opacity-60 disabled:cursor-not-allowed */
    /* .fa-spinner -> animate-spin (built-in Tailwind) */
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Toggle password visibility
    window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // Test email modal
    window.testEmail = function() {
        const modal = document.getElementById('test-email-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    };

    window.closeTestEmailModal = function() {
        const modal = document.getElementById('test-email-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Mail Driver configurations
    const mailConfigs = {
        gmail: {
            host: 'smtp.gmail.com',
            port: '587',
            encryption: 'TLS',
            username: '',
            password: '',
            hint: 'สำหรับ Gmail ต้องใช้ App Password แทนรหัสผ่านปกติ'
        },
        office365: {
            host: 'smtp.office365.com',
            port: '587',
            encryption: 'TLS',
            username: '',
            password: '',
            hint: 'สำหรับ Office 365 ใช้ email และรหัสผ่านของบัญชี'
        },
        mailtrap: {
            host: 'sandbox.smtp.mailtrap.io',
            port: '2525',
            encryption: 'TLS',
            username: 'your_mailtrap_username',
            password: 'your_mailtrap_password',
            hint: 'สำหรับ Mailtrap.io ใช้ข้อมูลจาก Dashboard'
        },
        hotmail: {
            host: 'smtp-mail.outlook.com',
            port: '587',
            encryption: 'TLS',
            username: '',
            password: '',
            hint: 'สำหรับ Hotmail/Outlook ใช้ email และรหัสผ่านของบัญชี'
        },
        smtp: {
            host: '',
            port: '587',
            encryption: 'TLS',
            username: '',
            password: '',
            hint: 'สำหรับ SMTP แบบกำหนดเอง กรุณากรอกข้อมูลเอง'
        }
    };

    // Update mail configuration based on selected driver
    window.updateMailConfig = function() {
        const driver = document.getElementById('mail_driver').value;
        const config = mailConfigs[driver];
        
        if (config) {
            document.getElementById('mail_smtp_host').value = config.host;
            document.getElementById('mail_smtp_port').value = config.port;
            document.getElementById('mail_smtp_encryption').value = config.encryption;
            
            // Update placeholders
            document.getElementById('mail_smtp_host').placeholder = config.host;
            document.getElementById('mail_smtp_port').placeholder = config.port;
            document.getElementById('mail_smtp_encryption').placeholder = config.encryption;
            
            // Update hints
            const usernameHint = document.querySelector('#mail_smtp_username').parentNode.querySelector('.text-xs');
            const passwordHint = document.querySelector('#mail_smtp_password').parentNode.querySelector('.text-xs');
            
            if (usernameHint) {
                usernameHint.textContent = config.hint;
            }
            if (passwordHint) {
                passwordHint.textContent = config.hint;
            }
            
            // Clear username and password for security
            if (driver !== 'mailtrap') {
                document.getElementById('mail_smtp_username').value = '';
                document.getElementById('mail_smtp_password').value = '';
            }
        }
    };

    // Initialize configuration on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateMailConfig();
    });

    // Reset to default
    window.resetToDefault = function() {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการรีเซ็ตการตั้งค่าอีเมล์เป็นค่าเริ่มต้นหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, รีเซ็ตเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'กำลังรีเซ็ตการตั้งค่า',
                    text: 'กรุณารอสักครู่...',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const form = document.getElementById('reset-form');
                if (form) {
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            title: 'สำเร็จ!',
                            text: data.message || 'รีเซ็ตการตั้งค่าอีเมล์เรียบร้อยแล้ว',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            // Reload page to show reset values
                            window.location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถรีเซ็ตการตั้งค่าได้',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    });
                }
            }
        });
    };

    // Test email form submission
    const testEmailForm = document.getElementById('test-email-form');
    if (testEmailForm) {
        testEmailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.classList.add('disabled:opacity-60', 'disabled:cursor-not-allowed');
            submitButton.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>กำลังส่งอีเมล์...';
            
            // Also disable main button
            const mainButton = document.getElementById('test-email-btn');
            if (mainButton) {
                mainButton.disabled = true;
                mainButton.classList.add('disabled:opacity-60', 'disabled:cursor-not-allowed');
                mainButton.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>กำลังส่งอีเมล์...';
            }
            
            // Show loading modal
            Swal.fire({
                title: 'กำลังส่งอีเมล์ทดสอบ',
                text: 'กรุณารอสักครู่...',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route("backend.settings-email.test") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    });
                    closeTestEmailModal();
                    
                    // Restore main button state
                    const mainButton = document.getElementById('test-email-btn');
                    if (mainButton) {
                        mainButton.disabled = false;
                        mainButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>ทดสอบการส่งอีเมล์';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถส่งอีเมล์ทดสอบได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
                
                // Restore main button state on error
                const mainButton = document.getElementById('test-email-btn');
                if (mainButton) {
                    mainButton.disabled = false;
                    mainButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>ทดสอบการส่งอีเมล์';
                }
            })
            .finally(() => {
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }

    // Close modal when clicking outside
    const modal = document.getElementById('test-email-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeTestEmailModal();
            }
        });
    }

    // Handle main form submission with SweetAlert
    const mainForm = document.querySelector('form[action="{{ route("backend.settings-email.update") }}"]');
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.classList.add('disabled:opacity-60', 'disabled:cursor-not-allowed');
            submitButton.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>กำลังบันทึก...';
            
            // Show loading SweetAlert
            Swal.fire({
                title: 'กำลังบันทึกการตั้งค่า',
                text: 'กรุณารอสักครู่...',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: data.message || 'บันทึกการตั้งค่าอีเมล์เรียบร้อยแล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถบันทึกการตั้งค่าได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            })
            .finally(() => {
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }
})();
</script>
@endpush
