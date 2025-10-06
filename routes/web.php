<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Frontend Routes
Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

Route::get('/services', function () {
    return view('frontend.services');
})->name('services');

Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');

Route::post('/contact/send', function () {
    // Validate form data
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:1000'
    ], [
        'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
        'name.max' => 'ชื่อ-นามสกุลต้องไม่เกิน 255 ตัวอักษร',
        'email.required' => 'กรุณากรอกอีเมล',
        'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
        'email.max' => 'อีเมลต้องไม่เกิน 255 ตัวอักษร',
        'phone.max' => 'เบอร์โทรศัพท์ต้องไม่เกิน 20 ตัวอักษร',
        'subject.required' => 'กรุณากรอกหัวข้อ',
        'subject.max' => 'หัวข้อต้องไม่เกิน 255 ตัวอักษร',
        'message.required' => 'กรุณากรอกข้อความ',
        'message.max' => 'ข้อความต้องไม่เกิน 1000 ตัวอักษร'
    ]);
    
    try {
        // Here you would typically:
        // 1. Store in database
        // 2. Send email notification to admin
        // 3. Send auto-reply to user
        // 4. Log the contact request
        
        // For demo, just simulate processing
        $name = $validated['name'];
        $email = $validated['email'];
        $phone = $validated['phone'] ?? 'ไม่ระบุ';
        $subject = $validated['subject'];
        $message = $validated['message'];
        
        // Log contact request (for demo)
        \Log::info('Contact form submitted', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
        
        return redirect()->route('contact')->with('success', 'ส่งข้อความเรียบร้อยแล้ว! เราจะติดต่อกลับโดยเร็วที่สุด');
        
    } catch (\Exception $e) {
        \Log::error('Contact form error: ' . $e->getMessage());
        return redirect()->route('contact')->with('error', 'เกิดข้อผิดพลาดในการส่งข้อความ กรุณาลองใหม่อีกครั้ง');
    }
})->name('contact.send');

// Admin Routes (Protected)
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/dashboard', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/users', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return view('admin.users.index');
    })->name('admin.users.index');
    
    Route::get('/settings', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return view('admin.settings.index');
    })->name('admin.settings.index');
    
    Route::get('/reports', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return view('admin.reports.index');
    })->name('admin.reports.index');
});

// Authentication Routes
Route::get('/login', function () {
    return view('admin.login');
})->name('login');

Route::post('/admin/login', function () {
    // Simple authentication logic (for demo purposes)
    $email = request('email');
    $password = request('password');
    
    // Demo credentials
    if ($email === 'admin@example.com' && $password === 'password') {
        // Set session
        session(['admin_logged_in' => true, 'admin_user' => $email]);
        
        return redirect()->route('admin.dashboard')->with('success', 'เข้าสู่ระบบสำเร็จ!');
    }
    
    return redirect()->back()->withErrors([
        'email' => 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง'
    ])->withInput();
})->name('admin.login');

Route::get('/register', function () {
    return redirect('/login');
})->name('register');

Route::post('/logout', function () {
    session()->forget(['admin_logged_in', 'admin_user']);
    return redirect('/')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
})->name('logout');

Route::get('/profile', function () {
    return redirect('/admin');
})->name('profile');

Route::get('/dashboard', function () {
    return redirect('/admin');
})->name('dashboard');

// Debug Test Route
Route::get('/debug-test', function () {
    // Test database query for debugbar
    $totalUsers = DB::table('laravel_users')->count();
    
    return view('frontend.debug-test', compact('totalUsers'));
});

// SweetAlert2 Test Route
Route::get('/sweetalert-test', function () {
    return view('frontend.sweetalert-test');
})->name('sweetalert.test');

// Test API route for AJAX testing
Route::get('/api/test', function () {
    return response()->json([
        'message' => 'AJAX Test Successful',
        'timestamp' => now()->toISOString(),
        'data' => [
            'users_count' => DB::table('laravel_users')->count(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug')
        ]
    ]);
});
