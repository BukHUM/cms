@extends('auth.layout')

@section('title', 'เข้าสู่ระบบ - CMS Backend')
@section('page-title', 'CMS Backend')
@section('page-description', 'เข้าสู่ระบบเพื่อจัดการระบบ')
@section('logo-color', 'bg-blue-600')
@section('logo-icon', 'fas fa-cogs')

@section('content')
    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        
        <!-- Email Field -->
        <x-auth.email-field />

        <!-- Password Field -->
        <x-auth.password-field />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Hidden input to ensure remember field is always sent -->
                <input type="hidden" name="remember" value="0">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember" 
                    value="1"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded auth-focus"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    จดจำการเข้าสู่ระบบ
                </label>
            </div>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500 auth-transition">
                    ลืมรหัสผ่าน?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <x-auth.submit-button />
    </form>
@endsection

@section('footer-links')
    <!-- Additional footer content if needed -->
@endsection
