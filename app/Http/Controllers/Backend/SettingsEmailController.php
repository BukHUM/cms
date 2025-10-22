<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SettingsEmailController extends Controller
{
    public function index(Request $request)
    {
        // Get all email-related settings
        $emailSettings = Setting::where('category', 'email')
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        if ($request->expectsJson()) {
            return response()->json($emailSettings);
        }

        return view('backend.settings-email.index', compact('emailSettings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => ['required', 'string', 'in:gmail,office365,mailtrap,hotmail,smtp'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'mail_smtp_host' => ['required', 'string', 'max:255'],
            'mail_smtp_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'mail_smtp_username' => ['nullable', 'string', 'max:255'],
            'mail_smtp_password' => ['nullable', 'string', 'max:255'],
            'mail_smtp_encryption' => ['required', 'string', 'max:255'],
            'enable_email_notifications' => ['nullable', 'in:0,1'],
            'mail_queue_enabled' => ['nullable', 'in:0,1'],
            'mail_retry_attempts' => ['integer', 'min:1', 'max:10'],
        ]);

        // Handle checkbox values - if not present, set to 0
        $validated['enable_email_notifications'] = $request->has('enable_email_notifications') ? '1' : '0';
        $validated['mail_queue_enabled'] = $request->has('mail_queue_enabled') ? '1' : '0';

        // Update email settings
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'category' => 'email'],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'group_name' => 'smtp',
                    'description' => $this->getSettingDescription($key),
                    'is_public' => $this->isPublicSetting($key),
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Email settings updated successfully']);
        }

        return redirect()->route('backend.settings-email.index')->with('success', 'อัปเดตการตั้งค่าอีเมล์เรียบร้อยแล้ว');
    }

    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => ['required', 'email'],
            'test_subject' => ['required', 'string', 'max:255'],
            'test_message' => ['required', 'string'],
        ]);

        try {
            $mailService = new MailService();
            $result = $mailService->sendTestEmail(
                $validated['test_email'],
                $validated['test_subject'],
                $validated['test_message']
            );

            if ($result['success']) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => $result['message']], 200);
                }
                return redirect()->back()->with('success', $result['message']);
            } else {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => $result['message']], 500);
                }
                return redirect()->back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Failed to send test email: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'ไม่สามารถส่งอีเมล์ทดสอบได้: ' . $e->getMessage());
        }
    }

    public function resetToDefault(Request $request)
    {
        $mailService = new MailService();
        $defaultSettings = [
            'mail_driver' => 'mailtrap',
            'mail_from_name' => 'Laravel Backend',
            'mail_smtp_host' => 'sandbox.smtp.mailtrap.io',
            'mail_smtp_port' => '2525',
            'mail_smtp_username' => 'your_mailtrap_username',
            'mail_smtp_password' => 'your_mailtrap_password',
            'mail_smtp_encryption' => 'TLS',
            'enable_email_notifications' => 'true',
            'mail_queue_enabled' => 'false',
            'mail_retry_attempts' => '3',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'category' => 'email'],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                    'group_name' => 'smtp',
                    'description' => $this->getSettingDescription($key),
                    'is_public' => $this->isPublicSetting($key),
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Email settings reset to default']);
        }

        return redirect()->route('backend.settings-email.index')->with('success', 'รีเซ็ตการตั้งค่าอีเมล์เป็นค่าเริ่มต้นเรียบร้อยแล้ว');
    }

    public function validateSettings(Request $request)
    {
        $mailService = new MailService();
        $validation = $mailService->validateEmailSettings();

        if ($request->expectsJson()) {
            return response()->json($validation);
        }

        if ($validation['valid']) {
            return redirect()->back()->with('success', 'การตั้งค่าอีเมล์ถูกต้อง');
        } else {
            return redirect()->back()->with('error', 'การตั้งค่าอีเมล์ไม่ถูกต้อง: ' . implode(', ', $validation['errors']));
        }
    }

    public function getSettingsSummary(Request $request)
    {
        $mailService = new MailService();
        $summary = $mailService->getEmailSettingsSummary();

        if ($request->expectsJson()) {
            return response()->json($summary);
        }

        return view('backend.settings-email.summary', compact('summary'));
    }

    private function getSettingType($key)
    {
        $types = [
            'mail_driver' => 'string',
            'mail_from_name' => 'string',
            'mail_smtp_host' => 'string',
            'mail_smtp_port' => 'integer',
            'mail_smtp_username' => 'string',
            'mail_smtp_password' => 'string',
            'mail_smtp_encryption' => 'string',
            'enable_email_notifications' => 'boolean',
            'mail_queue_enabled' => 'boolean',
            'mail_retry_attempts' => 'integer',
        ];

        return $types[$key] ?? 'string';
    }

    private function getSettingDescription($key)
    {
        $descriptions = [
            'mail_driver' => 'Mail Driver',
            'mail_from_name' => 'ชื่อผู้ส่งอีเมล',
            'mail_smtp_host' => 'SMTP Host Server',
            'mail_smtp_port' => 'SMTP Port',
            'mail_smtp_username' => 'SMTP Username',
            'mail_smtp_password' => 'SMTP Password',
            'mail_smtp_encryption' => 'การเข้ารหัส SMTP',
            'enable_email_notifications' => 'เปิดใช้งานการแจ้งเตือนทางอีเมล',
            'mail_queue_enabled' => 'เปิดใช้งาน Mail Queue',
            'mail_retry_attempts' => 'จำนวนครั้งในการลองส่งอีเมล์ใหม่',
        ];

        return $descriptions[$key] ?? '';
    }

    private function isPublicSetting($key)
    {
        $publicSettings = [
            'enable_email_notifications',
            'mail_queue_enabled',
        ];

        return in_array($key, $publicSettings);
    }
}
