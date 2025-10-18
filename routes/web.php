<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\SecuritySettingController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingsAuditLogController;
use App\Http\Controllers\Backend\PerformanceController;
use App\Http\Controllers\Backend\SettingsUpdateController;
use App\Http\Controllers\Backend\SystemInfoController;
use App\Http\Controllers\Auth\AuthController;

// Test route for Performance (without auth)
Route::get('/test-performance-no-auth', function () {
    try {
        // Temporarily disable auth middleware
        $controller = new \App\Http\Controllers\Backend\PerformanceController();
        
        // Use reflection to call index method directly
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('index');
        $method->setAccessible(true);
        
        $request = new \Illuminate\Http\Request();
        $response = $method->invoke($controller, $request);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Performance controller works without auth!',
            'response_type' => get_class($response)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});

// Test route for Performance
Route::get('/test-performance', function () {
    try {
        $controller = new \App\Http\Controllers\Backend\PerformanceController();
        $request = new \Illuminate\Http\Request();
        $response = $controller->index($request);
        return response()->json([
            'status' => 'success',
            'message' => 'Performance controller works!',
            'response_type' => get_class($response)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});

// Frontend Routes
Route::get('/', function () {
    return view('frontend.welcome');
})->name('frontend.home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // GET logout for convenience

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showPasswordResetForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');

// Auth Check API
Route::get('/auth/check', [AuthController::class, 'checkAuth'])->name('auth.check');

// Backend Routes
Route::prefix('backend')->name('backend.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.dashboard');
    })->name('dashboard');
    
    // User Management Routes
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Role Management Routes
    Route::resource('roles', RoleController::class);
    Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    
    // Permission Management Routes
    Route::resource('permissions', PermissionController::class);
    Route::patch('permissions/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    Route::post('permissions/bulk-action', [PermissionController::class, 'bulkAction'])->name('permissions.bulk-action');
    
    // Settings Audit Log Routes (ต้องอยู่ก่อน settings resource)
    Route::get('settings/auditlog', [SettingsAuditLogController::class, 'index'])->name('settings.auditlog.index');
    Route::get('settings/auditlog/{auditLog}', [SettingsAuditLogController::class, 'show'])->name('settings.auditlog.show');
    Route::get('settings/auditlog-export', [SettingsAuditLogController::class, 'export'])->name('settings.auditlog.export');
    Route::post('settings/auditlog/get-logs', [SettingsAuditLogController::class, 'getAuditLogs'])->name('settings.auditlog.get-logs');
    
    // Settings Management Routes
    Route::resource('settings', SettingController::class);
    Route::patch('settings/{setting}/toggle-status', [SettingController::class, 'toggleStatus'])->name('settings.toggle-status');
    Route::post('settings/bulk-action', [SettingController::class, 'bulkAction'])->name('settings.bulk-action');
    
    // Email Settings Routes
    Route::get('settings-email', [EmailSettingController::class, 'index'])->name('settings-email.index');
    Route::put('settings-email', [EmailSettingController::class, 'update'])->name('settings-email.update');
    Route::post('settings-email/test', [EmailSettingController::class, 'testEmail'])->name('settings-email.test');
    Route::post('settings-email/reset', [EmailSettingController::class, 'resetToDefault'])->name('settings-email.reset');
    Route::post('settings-email/validate', [EmailSettingController::class, 'validateSettings'])->name('settings-email.validate');
    Route::get('settings-email/summary', [EmailSettingController::class, 'getSettingsSummary'])->name('settings-email.summary');
    
    // Security Settings Routes
    Route::get('settings-security', [SecuritySettingController::class, 'index'])->name('settings-security.index');
    Route::put('settings-security', [SecuritySettingController::class, 'update'])->name('settings-security.update');
    Route::post('settings-security/test-password', [SecuritySettingController::class, 'testPasswordStrength'])->name('settings-security.test-password');
    Route::post('settings-security/validate-ip', [SecuritySettingController::class, 'validateIpWhitelist'])->name('settings-security.validate-ip');
    Route::post('settings-security/generate-password', [SecuritySettingController::class, 'generatePassword'])->name('settings-security.generate-password');
    Route::post('settings-security/reset', [SecuritySettingController::class, 'resetToDefault'])->name('settings-security.reset');
    Route::get('settings-security/report', [SecuritySettingController::class, 'getSecurityReport'])->name('settings-security.report');
    
    // Performance Settings Routes
    Route::resource('settings-performance', PerformanceController::class);
    Route::post('settings-performance/{performance}/reset', [PerformanceController::class, 'reset'])->name('settings-performance.reset');
    Route::post('settings-performance/bulk-update', [PerformanceController::class, 'bulkUpdate'])->name('settings-performance.bulk-update');
    Route::get('settings-performance-export', [PerformanceController::class, 'export'])->name('settings-performance.export');
    
    // Settings Update Routes
    Route::get('settings-update', [SettingsUpdateController::class, 'index'])->name('settings-update.index');
    Route::post('settings-update/quick-update', [SettingsUpdateController::class, 'quickUpdate'])->name('settings-update.quick-update');
    Route::post('settings-update/clear-cache', [SettingsUpdateController::class, 'clearCache'])->name('settings-update.clear-cache');
    Route::post('settings-update/optimize', [SettingsUpdateController::class, 'optimize'])->name('settings-update.optimize');
    Route::post('settings-update/migrate', [SettingsUpdateController::class, 'migrate'])->name('settings-update.migrate');
    Route::post('settings-update/seed', [SettingsUpdateController::class, 'seed'])->name('settings-update.seed');
    Route::post('settings-update/{settingsUpdate}/start', [SettingsUpdateController::class, 'start'])->name('settings-update.start');
    Route::post('settings-update/{settingsUpdate}/cancel', [SettingsUpdateController::class, 'cancel'])->name('settings-update.cancel');
    Route::post('settings-update/{settingsUpdate}/retry', [SettingsUpdateController::class, 'retry'])->name('settings-update.retry');
    
    // System Info Routes
    Route::get('settings-systeminfo', [SystemInfoController::class, 'index'])->name('settings-systeminfo.index');
    Route::get('settings-systeminfo/export', [SystemInfoController::class, 'export'])->name('settings-systeminfo.export');
});

// API Routes for CMS
Route::prefix('api')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Users Management
    Route::apiResource('users', UserController::class);
    Route::get('/users-roles', [UserController::class, 'roles']);
    
    // Roles Management
    Route::apiResource('roles', RoleController::class);
    
    // Permissions Management
    Route::apiResource('permissions', PermissionController::class);
    
    // Settings Management
    Route::apiResource('settings', SettingController::class);
    
    // Audit Logs
    Route::apiResource('audit-logs', AuditLogController::class);
    
});
