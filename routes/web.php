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

// Frontend Routes
Route::get('/', function () {
    return view('frontend.welcome');
})->name('frontend.home');

// Backend Routes
Route::prefix('backend')->name('backend.')->group(function () {
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
