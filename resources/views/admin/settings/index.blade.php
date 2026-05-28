@extends('layouts.admin')

@section('title', 'ตั้งค่าระบบ')
@section('subtitle', 'จัดการการตั้งค่าระบบและค่าพารามิเตอร์ต่างๆ')

@section('content')

<!-- Tab Content -->
<div class="tab-content">
    <!-- General Settings Tab -->
    <div id="general-tab" class="tab-pane active">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">การตั้งค่าทั่วไป</h3>
                <p class="text-sm text-slate-600">จัดการการตั้งค่าพื้นฐานของระบบ</p>
            </div>
            
            <form class="p-6 space-y-8">
                <!-- Site Information -->
                <div class="space-y-6">
                    <h4 class="text-md font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-globe mr-3 text-primary"></i>
                        ข้อมูลเว็บไซต์
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ชื่อเว็บไซต์</label>
                            <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="ชื่อเว็บไซต์" value="Core Admin System">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">คำอธิบายเว็บไซต์</label>
                            <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="คำอธิบายเว็บไซต์" value="ระบบจัดการหลังบ้าน">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">URL เว็บไซต์</label>
                            <input type="url" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="https://example.com" value="https://core.local">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">อีเมลติดต่อ</label>
                            <input type="email" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="admin@example.com" value="admin@core.local">
                        </div>
                    </div>
                </div>
                
                <!-- System Settings -->
                <div class="space-y-6">
                    <h4 class="text-md font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-cogs mr-3 text-primary"></i>
                        การตั้งค่าระบบ
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ภาษาเริ่มต้น</label>
                            <select class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                                <option value="th">ไทย</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">เขตเวลา</label>
                            <select class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                                <option value="Asia/Bangkok">Asia/Bangkok</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">รูปแบบวันที่</label>
                            <select class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                                <option value="d/m/Y">dd/mm/yyyy</option>
                                <option value="Y-m-d">yyyy-mm-dd</option>
                                <option value="m/d/Y">mm/dd/yyyy</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">รูปแบบเวลา</label>
                            <select class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth">
                                <option value="H:i:s">24 ชั่วโมง</option>
                                <option value="h:i:s A">12 ชั่วโมง</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Save Button -->
                <div class="flex justify-end pt-6 border-t border-slate-200">
                    <button type="submit" class="bg-gradient-primary text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Email Settings Tab -->
    <div id="email-tab" class="tab-pane hidden">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">การตั้งค่าอีเมล</h3>
                <p class="text-sm text-slate-600">จัดการการส่งอีเมลและ SMTP</p>
            </div>
            
            <form class="p-6 space-y-8">
                <!-- SMTP Settings -->
                <div class="space-y-6">
                    <h4 class="text-md font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-server mr-3 text-primary"></i>
                        การตั้งค่า SMTP
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">SMTP Host</label>
                            <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="smtp.gmail.com" value="smtp.gmail.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Port</label>
                            <input type="number" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="587" value="587">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                            <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="your-email@gmail.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <input type="password" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary">
                            <span class="ml-2 text-sm text-slate-700">ใช้ TLS</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary">
                            <span class="ml-2 text-sm text-slate-700">ใช้ SSL</span>
                        </label>
                    </div>
                </div>
                
                <!-- Email Templates -->
                <div class="space-y-6">
                    <h4 class="text-md font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-envelope-open-text mr-3 text-primary"></i>
                        เทมเพลตอีเมล
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">อีเมลผู้ส่ง</label>
                            <input type="email" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="noreply@example.com" value="noreply@core.local">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ชื่อผู้ส่ง</label>
                            <input type="text" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="System Admin" value="Core Admin System">
                        </div>
                    </div>
                </div>
                
                <!-- Test Email -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h5 class="font-semibold text-blue-800 mb-3">ทดสอบการส่งอีเมล</h5>
                    <div class="flex items-center gap-4">
                        <input type="email" class="flex-1 px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:border-blue-400" placeholder="อีเมลทดสอบ">
                        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            ส่งทดสอบ
                        </button>
                    </div>
                </div>
                
                <!-- Save Button -->
                <div class="flex justify-end pt-6 border-t border-slate-200">
                    <button type="submit" class="bg-gradient-primary text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings Tab -->
    <div id="security-tab" class="tab-pane hidden">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">การตั้งค่าความปลอดภัย</h3>
                <p class="text-sm text-slate-600">จัดการการรักษาความปลอดภัยของระบบ</p>
            </div>
            
            <form class="p-6 space-y-8">
                <!-- Password Policy -->
                <div class="space-y-6">
                    <h4 class="text-md font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-key mr-3 text-primary"></i>
                        นโยบายรหัสผ่าน
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ความยาวขั้นต่ำ</label>
                            <input type="number" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="8" value="8" min="4" max="32">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">อายุรหัสผ่าน (วัน)</label>
                            <input type="number" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="90" value="90" min="1" max="365">
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="ml-2 text-sm text-slate-700">ต้องมีตัวอักษรพิมพ์ใหญ่</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="ml-2 text-sm text-slate-700">ต้องมีตัวอักษรพิมพ์เล็ก</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="ml-2 text-sm text-slate-700">ต้องมีตัวเลข</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary">
                            <span class="ml-2 text-sm text-slate-700">ต้องมีอักขระพิเศษ</span>
                        </label>
                    </div>
                </div>
                
                <!-- Session Settings -->
                <div class="space-y-6">
                    <h4 class="text-md font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-clock mr-3 text-primary"></i>
                        การตั้งค่าเซสชัน
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">เวลาหมดอายุเซสชัน (นาที)</label>
                            <input type="number" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="120" value="120" min="5" max="1440">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">จำนวนการเข้าสู่ระบบผิด (ครั้ง)</label>
                            <input type="number" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth" placeholder="5" value="5" min="3" max="10">
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="ml-2 text-sm text-slate-700">จำการเข้าสู่ระบบ</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" checked>
                            <span class="ml-2 text-sm text-slate-700">ล็อคบัญชีเมื่อเข้าสู่ระบบผิด</span>
                        </label>
                    </div>
                </div>
                
                <!-- Save Button -->
                <div class="flex justify-end pt-6 border-t border-slate-200">
                    <button type="submit" class="bg-gradient-primary text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- System Info Tab -->
    <div id="system-info-tab" class="tab-pane hidden">
        <div class="space-y-6">
            <!-- Server Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">ข้อมูลเซิร์ฟเวอร์</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="font-medium text-slate-700">PHP Version</span>
                                <span class="text-sm text-slate-600">8.1.0</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="font-medium text-slate-700">Laravel Version</span>
                                <span class="text-sm text-slate-600">10.0.0</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="font-medium text-slate-700">Database</span>
                                <span class="text-sm text-slate-600">MySQL 8.0</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="font-medium text-slate-700">Server Software</span>
                                <span class="text-sm text-slate-600">Apache/2.4</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="font-medium text-slate-700">Memory Limit</span>
                                <span class="text-sm text-slate-600">256M</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="font-medium text-slate-700">Max Execution Time</span>
                                <span class="text-sm text-slate-600">30s</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">สถานะระบบ</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-6 bg-green-50 rounded-xl border border-green-200">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-green-800 mb-2">ฐานข้อมูล</h4>
                            <p class="text-sm text-green-600">เชื่อมต่อปกติ</p>
                        </div>
                        
                        <div class="text-center p-6 bg-green-50 rounded-xl border border-green-200">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-white text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-green-800 mb-2">Cache</h4>
                            <p class="text-sm text-green-600">ทำงานปกติ</p>
                        </div>
                        
                        <div class="text-center p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-yellow-800 mb-2">พื้นที่เก็บข้อมูล</h4>
                            <p class="text-sm text-yellow-600">67% ใช้งาน</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle hash navigation
    function showTabFromHash() {
        const hash = window.location.hash;
        if (hash) {
            const tabId = hash.substring(1); // Remove #
            const targetTab = document.getElementById(tabId + '-tab');
            if (targetTab) {
                // Hide all tabs
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.add('hidden');
                    pane.classList.remove('active');
                });
                
                // Show target tab
                targetTab.classList.remove('hidden');
                targetTab.classList.add('active');
            }
        }
    }
    
    // Show tab on page load
    showTabFromHash();
    
    // Handle hash changes
    window.addEventListener('hashchange', showTabFromHash);
    
    // Form submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: 'บันทึกการตั้งค่าเรียบร้อยแล้ว!'
            });
        });
    });
    
    // Test email functionality
    const testEmailBtn = document.querySelector('button[type="button"]');
    if (testEmailBtn && testEmailBtn.textContent.includes('ส่งทดสอบ')) {
        testEmailBtn.addEventListener('click', function() {
            const emailInput = this.previousElementSibling;
            const email = emailInput.value;
            
            if (!email) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกอีเมล',
                    text: 'กรุณากรอกอีเมลที่ต้องการทดสอบ',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            Swal.fire({
                title: 'กำลังส่งอีเมลทดสอบ...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simulate email sending
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'ส่งอีเมลสำเร็จ!',
                    text: `อีเมลทดสอบถูกส่งไปยัง ${email} แล้ว`,
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#667eea'
                });
            }, 2000);
        });
    }
});
</script>
@endpush