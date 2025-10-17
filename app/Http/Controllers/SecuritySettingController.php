<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SecuritySettingController extends Controller
{
    public function index(Request $request)
    {
        // Get all security-related settings
        $securitySettings = Setting::where('group', 'security')
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        // Initialize default settings if they don't exist
        $this->initializeDefaultSecuritySettings();

        // Get settings again after initialization
        $securitySettings = Setting::where('group', 'security')
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        if ($request->expectsJson()) {
            return response()->json($securitySettings);
        }

        return view('backend.settings-security.index', compact('securitySettings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => ['required', 'integer', 'min:6', 'max:50'],
            'password_require_uppercase' => ['boolean'],
            'password_require_lowercase' => ['boolean'],
            'password_require_numbers' => ['boolean'],
            'password_require_special' => ['boolean'],
            'password_expiry_days' => ['nullable', 'integer', 'min:30', 'max:365'],
            'max_login_attempts' => ['required', 'integer', 'min:3', 'max:10'],
            'lockout_duration' => ['required', 'integer', 'min:5', 'max:60'],
            'session_timeout' => ['required', 'integer', 'min:15', 'max:480'],
            'enable_2fa' => ['boolean'],
            'enable_captcha' => ['boolean'],
            'enable_ip_whitelist' => ['boolean'],
            'ip_whitelist' => ['nullable', 'string'],
            'enable_audit_log' => ['boolean'],
            'enable_login_notifications' => ['boolean'],
            'enable_failed_login_alerts' => ['boolean'],
            'enable_password_history' => ['boolean'],
            'password_history_count' => ['nullable', 'integer', 'min:3', 'max:12'],
        ]);

        // Update security settings
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'group' => 'security',
                    'description' => $this->getSettingDescription($key),
                    'is_public' => $this->isPublicSetting($key),
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Security settings updated successfully']);
        }

        return redirect()->route('backend.settings-security.index')->with('success', 'อัปเดตการตั้งค่าความปลอดภัยเรียบร้อยแล้ว');
    }

    public function testPasswordStrength(Request $request)
    {
        $validated = $request->validate([
            'test_password' => ['required', 'string'],
        ]);

        $securityService = new SecurityService();
        $strength = $securityService->checkPasswordStrength($validated['test_password']);

        if ($request->expectsJson()) {
            return response()->json($strength);
        }

        return redirect()->back()->with('password_strength', $strength);
    }

    public function validateIpWhitelist(Request $request)
    {
        $validated = $request->validate([
            'ip_list' => ['required', 'string'],
        ]);

        $securityService = new SecurityService();
        $validation = $securityService->validateIpWhitelist($validated['ip_list']);

        if ($request->expectsJson()) {
            return response()->json($validation);
        }

        if ($validation['valid']) {
            return redirect()->back()->with('success', 'IP Whitelist ถูกต้อง');
        } else {
            return redirect()->back()->with('error', 'IP Whitelist ไม่ถูกต้อง: ' . implode(', ', $validation['errors']));
        }
    }

    public function generatePassword(Request $request)
    {
        $validated = $request->validate([
            'length' => ['integer', 'min:8', 'max:50'],
            'include_uppercase' => ['boolean'],
            'include_lowercase' => ['boolean'],
            'include_numbers' => ['boolean'],
            'include_special' => ['boolean'],
        ]);

        $securityService = new SecurityService();
        $password = $securityService->generateSecurePassword(
            $validated['length'] ?? 12,
            $validated['include_uppercase'] ?? true,
            $validated['include_lowercase'] ?? true,
            $validated['include_numbers'] ?? true,
            $validated['include_special'] ?? true
        );

        if ($request->expectsJson()) {
            return response()->json(['password' => $password]);
        }

        return redirect()->back()->with('generated_password', $password);
    }

    public function resetToDefault(Request $request)
    {
        $defaultSettings = [
            'password_min_length' => '8',
            'password_require_uppercase' => 'true',
            'password_require_lowercase' => 'true',
            'password_require_numbers' => 'true',
            'password_require_special' => 'true',
            'password_expiry_days' => '90',
            'max_login_attempts' => '5',
            'lockout_duration' => '15',
            'session_timeout' => '120',
            'enable_2fa' => 'false',
            'enable_captcha' => 'false',
            'enable_ip_whitelist' => 'false',
            'ip_whitelist' => '',
            'enable_audit_log' => 'true',
            'enable_login_notifications' => 'true',
            'enable_failed_login_alerts' => 'true',
            'enable_password_history' => 'true',
            'password_history_count' => '5',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'group' => 'security',
                    'description' => $this->getSettingDescription($key),
                    'is_public' => $this->isPublicSetting($key),
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Security settings reset to default']);
        }

        return redirect()->route('backend.settings-security.index')->with('success', 'รีเซ็ตการตั้งค่าความปลอดภัยเป็นค่าเริ่มต้นเรียบร้อยแล้ว');
    }

    public function getSecurityReport(Request $request)
    {
        $securityService = new SecurityService();
        $report = $securityService->generateSecurityReport();

        if ($request->expectsJson()) {
            return response()->json($report);
        }

        return view('backend.settings-security.report', compact('report'));
    }

    private function getSettingType($key)
    {
        $types = [
            'password_min_length' => 'integer',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_special' => 'boolean',
            'password_expiry_days' => 'integer',
            'max_login_attempts' => 'integer',
            'lockout_duration' => 'integer',
            'session_timeout' => 'integer',
            'enable_2fa' => 'boolean',
            'enable_captcha' => 'boolean',
            'enable_ip_whitelist' => 'boolean',
            'ip_whitelist' => 'string',
            'enable_audit_log' => 'boolean',
            'enable_login_notifications' => 'boolean',
            'enable_failed_login_alerts' => 'boolean',
            'enable_password_history' => 'boolean',
            'password_history_count' => 'integer',
        ];

        return $types[$key] ?? 'string';
    }

    private function getSettingDescription($key)
    {
        $descriptions = [
            'password_min_length' => 'ความยาวขั้นต่ำของรหัสผ่าน',
            'password_require_uppercase' => 'ต้องการตัวอักษรพิมพ์ใหญ่',
            'password_require_lowercase' => 'ต้องการตัวอักษรพิมพ์เล็ก',
            'password_require_numbers' => 'ต้องการตัวเลข',
            'password_require_special' => 'ต้องการอักขระพิเศษ',
            'password_expiry_days' => 'จำนวนวันหมดอายุของรหัสผ่าน',
            'max_login_attempts' => 'จำนวนครั้งสูงสุดในการล็อกอินผิด',
            'lockout_duration' => 'ระยะเวลาล็อกบัญชี (นาที)',
            'session_timeout' => 'ระยะเวลาสิ้นสุดของ session (นาที)',
            'enable_2fa' => 'เปิดใช้งานการยืนยันตัวตนสองขั้นตอน',
            'enable_captcha' => 'เปิดใช้งาน CAPTCHA',
            'enable_ip_whitelist' => 'เปิดใช้งาน IP Whitelist',
            'ip_whitelist' => 'รายการ IP ที่อนุญาต',
            'enable_audit_log' => 'เปิดใช้งาน Audit Log',
            'enable_login_notifications' => 'เปิดใช้งานการแจ้งเตือนการล็อกอิน',
            'enable_failed_login_alerts' => 'เปิดใช้งานการแจ้งเตือนการล็อกอินผิด',
            'enable_password_history' => 'เปิดใช้งานประวัติรหัสผ่าน',
            'password_history_count' => 'จำนวนรหัสผ่านเก่าที่เก็บไว้',
        ];

        return $descriptions[$key] ?? '';
    }

    private function isPublicSetting($key)
    {
        $publicSettings = [
            'enable_2fa',
            'enable_captcha',
            'enable_ip_whitelist',
            'enable_audit_log',
            'enable_login_notifications',
            'enable_failed_login_alerts',
            'enable_password_history',
        ];

        return in_array($key, $publicSettings);
    }

    private function initializeDefaultSecuritySettings()
    {
        $defaultSettings = [
            'password_min_length' => '8',
            'password_require_uppercase' => 'true',
            'password_require_lowercase' => 'true',
            'password_require_numbers' => 'true',
            'password_require_special' => 'true',
            'password_expiry_days' => '90',
            'max_login_attempts' => '5',
            'lockout_duration' => '15',
            'session_timeout' => '120',
            'enable_2fa' => 'false',
            'enable_captcha' => 'false',
            'enable_ip_whitelist' => 'false',
            'ip_whitelist' => '',
            'enable_audit_log' => 'true',
            'enable_login_notifications' => 'true',
            'enable_failed_login_alerts' => 'true',
            'enable_password_history' => 'true',
            'password_history_count' => '5',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'group' => 'security',
                    'description' => $this->getSettingDescription($key),
                    'is_public' => $this->isPublicSetting($key),
                ]
            );
        }
    }
}
