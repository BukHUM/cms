@extends('layouts.admin')

@section('title', 'ข้อมูลส่วนตัว')
@section('subtitle', 'จัดการข้อมูลส่วนตัวและบัญชีของคุณ')

@push('head')
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="bg-gradient-primary text-white px-6 py-8 text-center">
                <!-- Avatar -->
                <div class="relative inline-block mb-4">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto">
                        {{ substr($user->name ?? 'A', 0, 1) }}
                    </div>
                    <button onclick="document.getElementById('avatarInput').click()" class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center text-primary hover:bg-slate-50 transition-colors shadow-lg">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
                
                <!-- User Info -->
                <h3 class="text-xl font-semibold mb-1">{{ $user->name ?? 'Admin User' }}</h3>
                <p class="text-white/80 text-sm mb-2">{{ $user->email ?? 'admin@example.com' }}</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                    {{ $user->roles?->first()?->name ?? 'Administrator' }}
                </span>
            </div>
            
            <!-- Quick Actions -->
            <div class="p-6 space-y-3">
                <button onclick="document.getElementById('avatarInput').click()" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gradient-primary text-white rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fas fa-camera"></i>
                    เปลี่ยนรูปโปรไฟล์
                </button>
                <button onclick="
                    console.log('Opening edit modal...');
                    const modal = document.getElementById('editModal');
                    if (modal) {
                        modal.classList.add('show');
                        document.body.classList.add('overflow-hidden');
                        console.log('Edit modal opened');
                    } else {
                        console.error('Edit modal not found');
                    }
                " class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                    <i class="fas fa-edit"></i>
                    แก้ไขข้อมูล
                </button>
                <button onclick="
                    console.log('Opening password modal...');
                    const modal = document.getElementById('passwordModal');
                    if (modal) {
                        modal.classList.add('show');
                        document.body.classList.add('overflow-hidden');
                        console.log('Password modal opened');
                    } else {
                        console.error('Password modal not found');
                    }
                " class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-orange-300 text-orange-700 rounded-lg font-medium hover:bg-orange-50 hover:border-orange-400 transition-all duration-200">
                    <i class="fas fa-key"></i>
                    เปลี่ยนรหัสผ่าน
                </button>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mt-6">
            <div class="px-6 py-4 border-b border-slate-200">
                <h4 class="font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-primary"></i>
                    สถิติการใช้งาน
                </h4>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <div class="text-lg font-bold text-slate-800">{{ $stats['account_created'] ? $stats['account_created']->format('d/m/Y') : 'ไม่ระบุ' }}</div>
                        <div class="text-xs text-slate-600">สมาชิกเมื่อ</div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <div class="text-lg font-bold text-slate-800">{{ $stats['last_login'] ? $stats['last_login']->format('d/m/Y') : 'ไม่เคย' }}</div>
                        <div class="text-xs text-slate-600">เข้าสู่ระบบล่าสุด</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-center mt-4">
                    <div class="p-4 bg-blue-50 rounded-xl">
                        <div class="text-lg font-bold text-blue-800">{{ $stats['total_logins'] }}</div>
                        <div class="text-xs text-blue-600">ครั้งที่เข้าสู่ระบบ</div>
                    </div>
                    <div class="p-4 bg-green-50 rounded-xl">
                        <div class="text-lg font-bold text-green-800">{{ $stats['profile_updates'] }}</div>
                        <div class="text-xs text-green-600">ครั้งที่แก้ไขโปรไฟล์</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Details -->
    <div class="lg:col-span-3 space-y-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-user mr-3 text-primary"></i>
                        ข้อมูลส่วนตัว
                    </h3>
                    <div class="flex items-center gap-2">
                        <button onclick="
                            console.log('Opening edit modal...');
                            const modal = document.getElementById('editModal');
                            if (modal) {
                                modal.classList.add('show');
                                document.body.classList.add('overflow-hidden');
                                console.log('Edit modal opened');
                            } else {
                                console.error('Edit modal not found');
                            }
                        " class="px-3 py-1.5 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                            <i class="fas fa-edit mr-1"></i>
                            แก้ไข
                        </button>
                        <button onclick="
                            console.log('Opening password modal...');
                            const modal = document.getElementById('passwordModal');
                            if (modal) {
                                modal.classList.add('show');
                                document.body.classList.add('overflow-hidden');
                                console.log('Password modal opened');
                            } else {
                                console.error('Password modal not found');
                            }
                        " class="px-3 py-1.5 border border-orange-300 text-orange-700 rounded-lg text-sm font-medium hover:bg-orange-50 hover:border-orange-400 transition-all duration-200">
                            <i class="fas fa-key mr-1"></i>
                            เปลี่ยนรหัสผ่าน
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-600">ชื่อ-นามสกุล</label>
                                <div class="text-slate-800 font-medium">{{ $user->name ?? 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-600">อีเมล</label>
                                <div class="text-slate-800 font-medium">{{ $user->email ?? 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-600">เบอร์โทรศัพท์</label>
                                <div class="text-slate-800 font-medium">{{ $user->phone ?? 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-orange-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-600">ที่อยู่</label>
                                <div class="text-slate-800 font-medium">{{ $user->address ?? 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-indigo-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-600">ข้อมูลส่วนตัว</label>
                                <div class="text-slate-800 font-medium">{{ $user->bio ?? 'ยังไม่ได้ระบุ' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar text-gray-600"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-slate-600">อัปเดตล่าสุด</label>
                                <div class="text-slate-800 font-medium">{{ $user->updated_at?->format('d/m/Y H:i') ?? 'ไม่ระบุ' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Activity Log -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-history mr-3 text-primary"></i>
                        ประวัติการใช้งานล่าสุด
                    </h3>
                    <a href="{{ route('admin.profile.activity-log') }}" class="px-3 py-1.5 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                        <i class="fas fa-list mr-1"></i>
                        ดูทั้งหมด
                    </a>
                </div>
            </div>
            <div class="p-6">
                <!-- Real Activities -->
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800">{{ $activity['description'] }}</p>
                                <p class="text-sm text-slate-600">
                                    @if($activity['ip_address'])
                                        จาก IP: {{ $activity['ip_address'] }}
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $activity['action'])) }}
                                    @endif
                                </p>
                                <p class="text-xs text-slate-500 mt-1">{{ $activity['created_at']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-slate-400 text-xl"></i>
                            </div>
                            <p class="text-slate-500">ยังไม่มีประวัติการใช้งาน</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.profile.activity-log') }}" class="inline-flex items-center gap-2 px-4 py-2 text-primary hover:text-primary-dark transition-colors">
                        <span>ดูประวัติทั้งหมด</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file input for avatar upload -->
<input type="file" id="avatarInput" accept="image/*" class="hidden">

<!-- Edit Profile Modal -->
<div id="editModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                <i class="fas fa-edit mr-3 text-primary"></i>
                แก้ไขข้อมูลส่วนตัว
            </h3>
            <button onclick="
                console.log('Closing edit modal...');
                const modal = document.getElementById('editModal');
                if (modal) {
                    modal.classList.remove('show');
                    document.body.classList.remove('overflow-hidden');
                    console.log('Edit modal closed');
                }
            " class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        <form id="editForm" action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ชื่อ-นามสกุล</label>
                    <input type="text" name="name" value="{{ $user->name ?? '' }}" 
                           class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">อีเมล</label>
                    <input type="email" name="email" value="{{ $user->email ?? '' }}" 
                           class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">เบอร์โทรศัพท์</label>
                    <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" 
                           class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ที่อยู่</label>
                    <input type="text" name="address" value="{{ $user->address ?? '' }}" 
                           class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ข้อมูลส่วนตัว</label>
                <textarea name="bio" rows="4" 
                          class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('bio') border-red-500 @enderror">{{ $user->bio ?? '' }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="button" onclick="
                    console.log('Closing edit modal...');
                    const modal = document.getElementById('editModal');
                    if (modal) {
                        modal.classList.remove('show');
                        document.body.classList.remove('overflow-hidden');
                        console.log('Edit modal closed');
                    }
                " class="px-6 py-2.5 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    ยกเลิก
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                <i class="fas fa-key mr-3 text-orange-600"></i>
                เปลี่ยนรหัสผ่าน
            </h3>
            <button onclick="
                console.log('Closing password modal...');
                const modal = document.getElementById('passwordModal');
                if (modal) {
                    modal.classList.remove('show');
                    document.body.classList.remove('overflow-hidden');
                    console.log('Password modal closed');
                }
            " class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        <form id="passwordForm" action="{{ route('admin.profile.update-password') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">รหัสผ่านปัจจุบัน</label>
                <input type="password" name="current_password" 
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('current_password') border-red-500 @enderror">
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">รหัสผ่านใหม่</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('password_confirmation') border-red-500 @enderror">
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <button type="button" onclick="
                    console.log('Closing password modal...');
                    const modal = document.getElementById('passwordModal');
                    if (modal) {
                        modal.classList.remove('show');
                        document.body.classList.remove('overflow-hidden');
                        console.log('Password modal closed');
                    }
                " class="px-6 py-2.5 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    ยกเลิก
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-600 to-orange-700 text-white rounded-lg font-medium hover:from-orange-700 hover:to-orange-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-key mr-2"></i>
                    เปลี่ยนรหัสผ่าน
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
#editModal, #passwordModal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 9999 !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
    display: none;
}

#editModal.show, #passwordModal.show {
    display: flex !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if modal elements exist
    console.log('DOM loaded, checking modal elements...');
    console.log('Edit modal:', document.getElementById('editModal'));
    console.log('Password modal:', document.getElementById('passwordModal'));
    
    // Test modal functions
    window.testEditModal = function() {
        console.log('Testing edit modal...');
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.classList.add('show');
            document.body.classList.add('overflow-hidden');
            console.log('Edit modal opened');
        } else {
            console.error('Edit modal not found');
        }
    };
    
    window.testPasswordModal = function() {
        console.log('Testing password modal...');
        const modal = document.getElementById('passwordModal');
        if (modal) {
            modal.classList.add('show');
            document.body.classList.add('overflow-hidden');
            console.log('Password modal opened');
        } else {
            console.error('Password modal not found');
        }
    };
    
    // Avatar upload handling
    const avatarInput = document.getElementById('avatarInput');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ขนาดไฟล์ต้องไม่เกิน 2MB',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไฟล์ต้องเป็นรูปภาพ (JPEG, PNG, JPG, GIF)',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'กำลังอัปโหลด...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Upload file (simulate)
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: 'อัปโหลดรูปโปรไฟล์เรียบร้อยแล้ว',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }, 2000);
        }
    });
    
    // Edit form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        // Let the form submit normally - no need to prevent default
        // The form will submit to the server and handle the response
    });
    
    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        // Let the form submit normally - no need to prevent default
        // The form will submit to the server and handle the response
    });
    
    // Close modals when clicking outside
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
    
    const passwordModal = document.getElementById('passwordModal');
    if (passwordModal) {
        passwordModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordModal();
            }
        });
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closePasswordModal();
        }
    });
});
</script>
@endpush