@extends('auth.layout')

@section('title', 'ลืมรหัสผ่าน - CMS Backend')
@section('page-title', 'ลืมรหัสผ่าน')
@section('page-description', 'กรุณาใส่อีเมล์ของคุณเพื่อรีเซ็ตรหัสผ่าน')
@section('logo-color', 'bg-orange-600')
@section('logo-icon', 'fas fa-key')

@section('content')
    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        
        <!-- Email Field -->
        <x-auth.email-field />

        <!-- Submit Button -->
        <x-auth.submit-button 
            text="ส่งลิงก์รีเซ็ตรหัสผ่าน" 
            icon="fas fa-paper-plane" 
            color="orange" 
        />
    </form>

    <!-- Back to Login -->
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800 auth-transition">
            <i class="fas fa-arrow-left mr-1"></i>
            กลับไปหน้าเข้าสู่ระบบ
        </a>
    </div>
@endsection

@section('footer-links')
    <!-- Additional footer content if needed -->
@endsection
