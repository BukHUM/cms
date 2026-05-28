@extends('layouts.admin')

@section('title', 'แก้ไขข้อมูลส่วนตัว')
@section('subtitle', 'อัปเดตข้อมูลส่วนตัวของคุณ')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 flex items-center">
                <i class="fas fa-user-edit mr-3 text-primary"></i>
                แก้ไขข้อมูลส่วนตัว
            </h3>
        </div>
        
        <form class="p-6 space-y-8" method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div class="space-y-6">
                <h4 class="text-md font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-user mr-3 text-primary"></i>
                    ข้อมูลส่วนตัว
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">ชื่อ-นามสกุล</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" 
                               class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">อีเมล</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" 
                               class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" 
                               class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">ที่อยู่</label>
                        <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}" 
                               class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ข้อมูลส่วนตัว</label>
                    <textarea name="bio" rows="4" 
                              class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-smooth @error('bio') border-red-500 @enderror">{{ old('bio', $user->bio ?? '') }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Save Button -->
            <div class="flex justify-end pt-6 border-t border-slate-200">
                <div class="flex gap-3">
                    <a href="{{ route('admin.profile.index') }}" class="px-6 py-3 border border-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit" class="bg-gradient-primary text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save mr-2"></i>
                        บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection