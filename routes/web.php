<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\SettingsGeneralController;
use App\Http\Controllers\Backend\SettingsPerformanceController;
use App\Http\Controllers\Backend\SettingsBackupController;
use App\Http\Controllers\Backend\SettingsEmailController;
use App\Http\Controllers\Backend\SettingsSecurityController;
use App\Http\Controllers\Backend\SettingsAuditLogController;
use App\Http\Controllers\Backend\SettingsSystemInfoController;
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
    Route::resource('settings-general', SettingsGeneralController::class)->except(['create', 'store', 'edit']);
    Route::patch('settings-general/{setting}/toggle-status', [SettingsGeneralController::class, 'toggleStatus'])->name('settings-general.toggle-status');
    Route::post('settings-general/bulk-update', [SettingsGeneralController::class, 'bulkUpdate'])->name('settings-general.bulk-update');
    Route::post('settings-general/{setting}/reset', [SettingsGeneralController::class, 'reset'])->name('settings-general.reset');
    Route::get('settings-general-export', [SettingsGeneralController::class, 'export'])->name('settings-general.export');
    
    // Performance Settings Routes
    Route::resource('settings-performance', SettingsPerformanceController::class);
    Route::patch('settings-performance/{setting}/toggle-status', [SettingsPerformanceController::class, 'toggleStatus'])->name('settings-performance.toggle-status');
    Route::post('settings-performance/bulk-update', [SettingsPerformanceController::class, 'bulkUpdate'])->name('settings-performance.bulk-update');
    Route::post('settings-performance/{setting}/reset', [SettingsPerformanceController::class, 'reset'])->name('settings-performance.reset');
    Route::get('settings-performance-export', [SettingsPerformanceController::class, 'export'])->name('settings-performance.export');
    
    // Backup Settings Routes
    Route::resource('settings-backup', SettingsBackupController::class);
    Route::patch('settings-backup/{setting}/toggle-status', [SettingsBackupController::class, 'toggleStatus'])->name('settings-backup.toggle-status');
    Route::post('settings-backup/bulk-update', [SettingsBackupController::class, 'bulkUpdate'])->name('settings-backup.bulk-update');
    Route::post('settings-backup/{setting}/reset', [SettingsBackupController::class, 'reset'])->name('settings-backup.reset');
    Route::get('settings-backup-export', [SettingsBackupController::class, 'export'])->name('settings-backup.export');
    Route::post('settings-backup/create-backup', [SettingsBackupController::class, 'createBackup'])->name('settings-backup.create-backup');
    Route::get('settings-backup/get-backups', [SettingsBackupController::class, 'getBackups'])->name('settings-backup.get-backups');
    Route::get('settings-backup/download/{backupName}', [SettingsBackupController::class, 'downloadBackup'])->name('settings-backup.download');
    Route::delete('settings-backup/delete/{backupName}', [SettingsBackupController::class, 'deleteBackup'])->name('settings-backup.delete-backup');
    
    // Email Settings Routes
    Route::get('settings-email', [SettingsEmailController::class, 'index'])->name('settings-email.index');
    Route::put('settings-email', [SettingsEmailController::class, 'update'])->name('settings-email.update');
    Route::post('settings-email/test', [SettingsEmailController::class, 'testEmail'])->name('settings-email.test');
    Route::post('settings-email/reset', [SettingsEmailController::class, 'resetToDefault'])->name('settings-email.reset');
    Route::post('settings-email/validate', [SettingsEmailController::class, 'validateSettings'])->name('settings-email.validate');
    Route::get('settings-email/summary', [SettingsEmailController::class, 'getSettingsSummary'])->name('settings-email.summary');
    
    // Security Settings Routes
    Route::get('settings-security', [SettingsSecurityController::class, 'index'])->name('settings-security.index');
    Route::put('settings-security', [SettingsSecurityController::class, 'update'])->name('settings-security.update');
    Route::post('settings-security/test-password', [SettingsSecurityController::class, 'testPasswordStrength'])->name('settings-security.test-password');
    Route::post('settings-security/validate-ip', [SettingsSecurityController::class, 'validateIpWhitelist'])->name('settings-security.validate-ip');
    Route::post('settings-security/generate-password', [SettingsSecurityController::class, 'generatePassword'])->name('settings-security.generate-password');
    Route::post('settings-security/reset', [SettingsSecurityController::class, 'resetToDefault'])->name('settings-security.reset');
    Route::get('settings-security/report', [SettingsSecurityController::class, 'getSecurityReport'])->name('settings-security.report');
    
    
    // System Info Routes
    Route::get('settings-systeminfo', [SettingsSystemInfoController::class, 'index'])->name('settings-systeminfo.index');
    Route::get('settings-systeminfo/export', [SettingsSystemInfoController::class, 'export'])->name('settings-systeminfo.export');
    
    // Settings Backup Routes
    Route::middleware(['settings.backup.access'])->group(function () {
        Route::resource('settings-backup', SettingsBackupController::class);
        Route::post('settings-backup/bulk-update', [SettingsBackupController::class, 'updateBulk'])->name('settings-backup.bulk-update');
        Route::post('settings-backup/create-backup', [SettingsBackupController::class, 'createBackup'])->name('settings-backup.create-backup');
        Route::get('settings-backup/get-backups', [SettingsBackupController::class, 'getBackups'])->name('settings-backup.get-backups');
        Route::get('settings-backup/download/{backupName}', [SettingsBackupController::class, 'downloadBackup'])->name('settings-backup.download');
        Route::delete('settings-backup/delete/{backupName}', [SettingsBackupController::class, 'deleteBackup'])->name('settings-backup.delete');
    });
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
    Route::apiResource('settings', SettingsGeneralController::class);
    
    // Audit Logs
    Route::apiResource('audit-logs', SettingsAuditLogController::class);
    
});

// Maintenance API (accessible even during maintenance mode)
Route::get('/api/maintenance-status', [App\Http\Controllers\Api\MaintenanceController::class, 'status']);
