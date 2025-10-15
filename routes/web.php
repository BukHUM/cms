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
Route::prefix('admin')->middleware(['web'])->group(function () {
    Route::get('/', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        
        // Get dashboard data using cache service for better performance
        $dashboardStats = \App\Services\CacheService::getDashboardStats();
        
        // Get recent activities with optimized query
        $recentActivities = \App\Models\AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Check if jobs table exists
        try {
            $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
        } catch (\Exception $e) {
            $pendingJobs = 0;
        }
        
        return view('admin.dashboard', compact(
            'dashboardStats',
            'pendingJobs', 
            'recentActivities'
        ));
    })->name('admin.dashboard');
    
    // User Management
    Route::get('/user-management', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return app(App\Http\Controllers\UserManagementController::class)->index();
    })->name('admin.user-management');
    
    
    Route::get('/settings', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        return view('admin.settings.index');
    })->name('admin.settings.index');
    
    // Settings API Routes
    Route::prefix('settings')->group(function () {
        // General Settings
        Route::get('/general', [App\Http\Controllers\SettingsController::class, 'getGeneralSettings'])->name('admin.settings.general.get');
        Route::post('/general', [App\Http\Controllers\SettingsController::class, 'saveGeneralSettings'])->name('admin.settings.general.save');
        
        // Email Settings
        Route::get('/email', [App\Http\Controllers\SettingsController::class, 'getEmailSettings'])->name('admin.settings.email.get');
        Route::post('/email', [App\Http\Controllers\SettingsController::class, 'saveEmailSettings'])->name('admin.settings.email.save');
        Route::post('/email/test', [App\Http\Controllers\SettingsController::class, 'testEmail'])->name('admin.settings.email.test');
        
        // Security Settings
        Route::get('/security', [App\Http\Controllers\SettingsController::class, 'getSecuritySettings'])->name('admin.settings.security.get');
        Route::post('/security', [App\Http\Controllers\SettingsController::class, 'saveSecuritySettings'])->name('admin.settings.security.save');
        
        // Backup Settings
        Route::get('/backup', [App\Http\Controllers\SettingsController::class, 'getBackupSettings'])->name('admin.settings.backup.get');
        Route::post('/backup', [App\Http\Controllers\SettingsController::class, 'saveBackupSettings'])->name('admin.settings.backup.save');
        Route::post('/backup/create', [App\Http\Controllers\SettingsController::class, 'createBackup'])->name('admin.settings.backup.create');
        Route::get('/backup/history', [App\Http\Controllers\SettingsController::class, 'getBackupHistory'])->name('admin.settings.backup.history');
        Route::get('/backup/download/{id}', [App\Http\Controllers\SettingsController::class, 'downloadBackup'])->name('admin.settings.backup.download');
        Route::delete('/backup/delete/{id}', [App\Http\Controllers\SettingsController::class, 'deleteBackup'])->name('admin.settings.backup.delete');
        
        // Audit Settings
        Route::get('/audit', [App\Http\Controllers\SettingsController::class, 'getAuditSettings'])->name('admin.settings.audit.get');
        Route::post('/audit', [App\Http\Controllers\SettingsController::class, 'saveAuditSettings'])->name('admin.settings.audit.save');
        Route::get('/audit/logs', [App\Http\Controllers\SettingsController::class, 'getAuditLogs'])->name('admin.settings.audit.logs');
        Route::post('/audit/export', [App\Http\Controllers\SettingsController::class, 'exportAuditLogs'])->name('admin.settings.audit.export');
        Route::delete('/audit/clear', [App\Http\Controllers\SettingsController::class, 'clearAuditLogs'])->name('admin.settings.audit.clear');
        
        // Update Settings
        Route::get('/update', [App\Http\Controllers\SettingsController::class, 'getUpdateSettings'])->name('admin.settings.update.get');
        Route::post('/update', [App\Http\Controllers\SettingsController::class, 'saveUpdateSettings'])->name('admin.settings.update.save');
        Route::post('/update/check', [App\Http\Controllers\SettingsController::class, 'checkForUpdates'])->name('admin.settings.update.check');
        
        // Performance Settings
        Route::get('/performance', [App\Http\Controllers\SettingsController::class, 'getPerformanceSettings'])->name('admin.settings.performance.get');
        Route::post('/performance', [App\Http\Controllers\SettingsController::class, 'savePerformanceSettings'])->name('admin.settings.performance.save');
        Route::get('/performance/metrics', [App\Http\Controllers\SettingsController::class, 'getPerformanceMetrics'])->name('admin.settings.performance.metrics');
        Route::get('/performance/slow-queries', [App\Http\Controllers\SettingsController::class, 'getSlowQueries'])->name('admin.settings.performance.slow-queries');
        Route::get('/performance/duplicate-queries', [App\Http\Controllers\SettingsController::class, 'getDuplicateQueries'])->name('admin.settings.performance.duplicate-queries');
        Route::get('/performance/table-statistics', [App\Http\Controllers\SettingsController::class, 'getTableStatistics'])->name('admin.settings.performance.table-statistics');
        Route::post('/performance/clear-cache', [App\Http\Controllers\SettingsController::class, 'clearCache'])->name('admin.settings.performance.clear-cache');
        Route::post('/performance/test', [App\Http\Controllers\SettingsController::class, 'runPerformanceTest'])->name('admin.settings.performance.test');
        
        // Slow Query Log management routes
        Route::get('/performance/slow-log-status', [App\Http\Controllers\PerformanceController::class, 'getSlowQueryLogStatus'])->name('admin.settings.performance.slow-log-status');
        
        // System Info Routes
        Route::get('/system-info', [App\Http\Controllers\SettingsController::class, 'getSystemInfo'])->name('admin.settings.system-info.get');
        Route::post('/system-info/export', [App\Http\Controllers\SettingsController::class, 'exportSystemInfo'])->name('admin.settings.system-info.export');
        Route::get('/system-info/logs/{filename}', [App\Http\Controllers\SettingsController::class, 'getLogFile'])->name('admin.settings.system-info.logs');
        Route::delete('/system-info/clear-logs', [App\Http\Controllers\SettingsController::class, 'clearLogs'])->name('admin.settings.system-info.clear-logs');
        Route::get('/system-info/download-logs', [App\Http\Controllers\SettingsController::class, 'downloadLogs'])->name('admin.settings.system-info.download-logs');
        
        // All Settings
        Route::get('/all', [App\Http\Controllers\SettingsController::class, 'getAllSettings'])->name('admin.settings.all.get');
    });
    
    // Profile Management Routes
    Route::prefix('profile')->name('admin.profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('update-password');
        Route::get('/activity-log', [App\Http\Controllers\ProfileController::class, 'activityLog'])->name('activity-log');
        Route::post('/update-avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('update-avatar');
    });
    
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

Route::post('/admin/login', function (Illuminate\Http\Request $request) {
    $loginService = new \App\Services\LoginSecurityService();
    $result = $loginService->attemptLogin($request);
    
    if ($result['success']) {
        $user = $result['user'];
        
        // Update last login time
        $user->update(['last_login_at' => now()]);
        
        // Regenerate session ID for security
        session()->regenerate();
        
        // Set session with user data and security information
        session([
            'admin_logged_in' => true, 
            'admin_user_id' => $user->id,
            'admin_user_email' => $user->email,
            'admin_user_name' => $user->name,
            'admin_user_role' => $user->role,
            'last_activity' => time(),
            'user_ip_address' => $request->ip(),
            'login_time' => time(),
            'session_fingerprint' => hash('sha256', $request->ip() . $request->userAgent())
        ]);
        
        return redirect()->route('admin.dashboard');
    }
    
    // Handle locked accounts
    if (isset($result['locked']) && $result['locked']) {
        return redirect()->back()->withErrors([
            'email' => $result['message']
        ])->withInput();
    }
    
    // Handle failed login
    return redirect()->back()->withErrors([
        'email' => $result['message']
    ])->withInput();
})->middleware('throttle:5,1')->name('admin.login');

Route::get('/register', function () {
    return redirect('/login');
})->name('register');

Route::post('/logout', function () {
    // Log logout activity
    \Log::info('User logout', [
        'user_id' => session('admin_user_id'),
        'user_email' => session('admin_user_email'),
        'ip_address' => request()->ip(),
        'session_duration' => time() - (session('login_time') ?? time())
    ]);
    
    // Regenerate session ID for security
    session()->regenerate();
    
    // Clear all admin session data
    session()->forget([
        'admin_logged_in',
        'admin_user_id',
        'admin_user_email',
        'admin_user_name',
        'admin_user_role',
        'last_activity',
        'user_ip_address',
        'login_time',
        'session_fingerprint'
    ]);
    
    // Invalidate session completely
    session()->invalidate();
    
    return redirect('/');
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

// Debug Level Test Route
Route::get('/debug-level-test', function () {
    return view('tests.debug-test');
});

// Debug Test API Routes
Route::prefix('api/debug-test')->group(function () {
    Route::get('/error', function () {
        // Simulate an error for testing
        return response()->json([
            'message' => 'Error simulation for testing',
            'error_type' => 'Test Error',
            'debug_level' => config('app.debug_level', 'standard'),
            'debug_mode' => config('app.debug'),
            'error_simulated' => true,
            'timestamp' => now()->toISOString()
        ]);
    });
    
    Route::get('/logging', function () {
        \Log::info('Debug Level Test - Info message');
        \Log::warning('Debug Level Test - Warning message');
        \Log::error('Debug Level Test - Error message');
        \Log::debug('Debug Level Test - Debug message');
        
        return response()->json([
            'message' => 'Logging test completed',
            'debug_level' => config('app.debug_level', 'standard'),
            'logs_written' => true
        ]);
    });
    
    Route::get('/database', function () {
        $users = DB::table('laravel_users')->limit(5)->get();
        $totalUsers = DB::table('laravel_users')->count();
        
        return response()->json([
            'message' => 'Database test completed',
            'debug_level' => config('app.debug_level', 'standard'),
            'total_users' => $totalUsers,
            'sample_users' => $users,
            'queries_executed' => true
        ]);
    });
    
    Route::get('/performance', function () {
        $startTime = microtime(true);
        
        // Simulate some work
        for ($i = 0; $i < 1000; $i++) {
            $result = $i * $i;
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        return response()->json([
            'message' => 'Performance test completed',
            'debug_level' => config('app.debug_level', 'standard'),
            'execution_time_ms' => round($executionTime, 2),
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
        ]);
    });
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

// Debug Mode Test Route
Route::get('/test-debug', function () {
    return response()->json([
        'message' => 'Debug Mode Test',
        'timestamp' => now()->toISOString(),
        'debug_mode' => config('app.debug'),
        'debug_level' => config('app.debug_level', 'standard'),
        'settings_debug_level' => \App\Helpers\SettingsHelper::get('debug_level', 'standard'),
        'settings_debug_mode' => \App\Helpers\SettingsHelper::get('debug_mode'),
        'env_debug' => env('APP_DEBUG')
    ]);
});

// Performance API Routes
Route::prefix('api/performance')->group(function () {
    Route::get('/slow-queries', [App\Http\Controllers\PerformanceController::class, 'getSlowQueries']);
    Route::get('/duplicate-queries', [App\Http\Controllers\PerformanceController::class, 'getDuplicateQueries']);
    Route::get('/metrics', [App\Http\Controllers\PerformanceController::class, 'getPerformanceMetrics']);
    Route::get('/table-statistics', [App\Http\Controllers\PerformanceController::class, 'getTableStatistics']);
    Route::get('/connection-statistics', [App\Http\Controllers\PerformanceController::class, 'getConnectionStatistics']);
    Route::get('/settings', [App\Http\Controllers\PerformanceController::class, 'getPerformanceSettings']);
    Route::post('/settings', [App\Http\Controllers\PerformanceController::class, 'savePerformanceSettings']);
    Route::post('/test-query-logging', [App\Http\Controllers\PerformanceController::class, 'testQueryLogging']);
});

// Audit API Routes
Route::prefix('api/audit')->group(function () {
    Route::get('/recent', [App\Http\Controllers\AuditController::class, 'getRecentLogs']);
    Route::get('/logs', [App\Http\Controllers\AuditController::class, 'getLogs']);
    Route::get('/statistics', [App\Http\Controllers\AuditController::class, 'getStatistics']);
    Route::get('/export', [App\Http\Controllers\AuditController::class, 'exportLogs']);
    Route::get('/{id}', [App\Http\Controllers\AuditController::class, 'getLogById']);
    Route::post('/create', [App\Http\Controllers\AuditController::class, 'createLog']);
    Route::delete('/cleanup', [App\Http\Controllers\AuditController::class, 'cleanupOldLogs']);
});

// Settings API Routes
Route::prefix('api/settings')->group(function () {
    Route::post('/audit', [App\Http\Controllers\SettingsController::class, 'saveAuditSettings']);
    Route::get('/audit', [App\Http\Controllers\SettingsController::class, 'getAuditSettings']);
});

// User Management Routes
Route::prefix('admin')->middleware(['web'])->group(function () {
    // User Management Routes (New Tab System)
    Route::get('/user-management', [App\Http\Controllers\UserManagementController::class, 'index'])->name('admin.user-management');
    
    // API Routes for User Management
    Route::prefix('api/user-management')->group(function () {
        // Users API
        Route::get('/users', [App\Http\Controllers\UserManagementController::class, 'getUsers'])->name('user-management.users.api');
        Route::post('/users', [App\Http\Controllers\UserManagementController::class, 'storeUser'])->name('user-management.users.store');
        Route::get('/users/{user}', [App\Http\Controllers\UserManagementController::class, 'getUser'])->name('user-management.users.show');
        Route::put('/users/{user}', [App\Http\Controllers\UserManagementController::class, 'updateUser'])->name('user-management.users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\UserManagementController::class, 'deleteUser'])->name('user-management.users.delete');
        Route::post('/users/{user}/status', [App\Http\Controllers\UserManagementController::class, 'updateUserStatus'])->name('user-management.users.status');
        Route::post('/users/{user}/roles', [App\Http\Controllers\UserManagementController::class, 'updateUserRoles'])->name('user-management.users.roles');
        Route::get('/users/export', [App\Http\Controllers\UserManagementController::class, 'exportUsers'])->name('user-management.users.export');
        
        // Roles API
        Route::get('/roles', [App\Http\Controllers\UserManagementController::class, 'getRoles'])->name('user-management.roles.api');
        Route::post('/roles', [App\Http\Controllers\UserManagementController::class, 'storeRole'])->name('user-management.roles.store');
        Route::get('/roles/{role}', [App\Http\Controllers\UserManagementController::class, 'getRole'])->name('user-management.roles.show');
        Route::put('/roles/{role}', [App\Http\Controllers\UserManagementController::class, 'updateRole'])->name('user-management.roles.update');
        Route::delete('/roles/{role}', [App\Http\Controllers\UserManagementController::class, 'deleteRole'])->name('user-management.roles.delete');
        Route::post('/roles/{role}/status', [App\Http\Controllers\UserManagementController::class, 'updateRoleStatus'])->name('user-management.roles.status');
        Route::get('/roles/{role}/permissions', [App\Http\Controllers\UserManagementController::class, 'getRolePermissions'])->name('user-management.roles.permissions');
        Route::post('/roles/{role}/permissions', [App\Http\Controllers\UserManagementController::class, 'updateRolePermissions'])->name('user-management.roles.permissions.update');
        
        // Permissions API
        Route::get('/permissions', [App\Http\Controllers\UserManagementController::class, 'getPermissions'])->name('user-management.permissions.api');
        Route::post('/permissions', [App\Http\Controllers\UserManagementController::class, 'storePermission'])->name('user-management.permissions.store');
        Route::get('/permissions/{permission}', [App\Http\Controllers\UserManagementController::class, 'getPermission'])->name('user-management.permissions.show');
        Route::put('/permissions/{permission}', [App\Http\Controllers\UserManagementController::class, 'updatePermission'])->name('user-management.permissions.update');
        Route::delete('/permissions/{permission}', [App\Http\Controllers\UserManagementController::class, 'deletePermission'])->name('user-management.permissions.delete');
        Route::post('/permissions/{permission}/status', [App\Http\Controllers\UserManagementController::class, 'updatePermissionStatus'])->name('user-management.permissions.status');
        Route::get('/permissions/groups', [App\Http\Controllers\UserManagementController::class, 'getPermissionGroups'])->name('user-management.permissions.groups');
    });
});

// System Info API Routes
Route::prefix('api/system')->group(function () {
    Route::get('/info', [App\Http\Controllers\SystemInfoController::class, 'getSystemInfo']);
    Route::post('/timezone', [App\Http\Controllers\SystemInfoController::class, 'updateTimezone']);
});
