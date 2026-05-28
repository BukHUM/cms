@extends('layouts.admin')

@section('title', 'เปลี่ยนรหัสผ่าน')
@section('subtitle', 'อัปเดตรหัสผ่านของคุณเพื่อความปลอดภัย')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                <i class="fas fa-key mr-3 text-primary"></i>
                เปลี่ยนรหัสผ่าน
            </h3>
        </div>
        
        <form class="p-6 space-y-8" method="POST" action="{{ route('admin.profile.update-password') }}">
            @csrf
            @method('PUT')
            
            <!-- Password Fields -->
            <div class="space-y-6">
                <h4 class="text-md font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-shield-alt mr-3 text-primary"></i>
                    รหัสผ่านใหม่
                </h4>
                
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
            </div>
            
            <!-- Password Requirements -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h5 class="font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    ข้อกำหนดรหัสผ่าน
                </h5>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• ความยาวอย่างน้อย 8 ตัวอักษร</li>
                    <li>• ต้องมีตัวอักษรพิมพ์ใหญ่และพิมพ์เล็ก</li>
                    <li>• ต้องมีตัวเลขอย่างน้อย 1 ตัว</li>
                    <li>• ควรมีอักขระพิเศษ (!@#$%^&*)</li>
                </ul>
            </div>
            
            <!-- Save Button -->
            <div class="flex justify-end pt-6 border-t border-slate-200">
                <div class="flex gap-3">
                    <a href="{{ route('admin.profile.index') }}" class="px-6 py-3 border border-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit" class="bg-gradient-primary text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-key mr-2"></i>
                        เปลี่ยนรหัสผ่าน
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection