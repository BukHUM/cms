<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Send test email using current settings
     */
    public function sendTestEmail($to, $subject, $message)
    {
        try {
            // Get email settings
            $fromAddress = Setting::get('mail_from_address', 'noreply@example.com');
            $fromName = Setting::get('mail_from_name', 'CMS Backend');

            // Send email
            Mail::raw($message, function ($mail) use ($to, $subject, $fromAddress, $fromName) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($fromAddress, $fromName);
            });

            return [
                'success' => true,
                'message' => 'Test email sent successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send test email: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send notification email
     */
    public function sendNotification($to, $subject, $message, $data = [])
    {
        // Check if email notifications are enabled
        if (!Setting::get('enable_email_notifications', true)) {
            return [
                'success' => false,
                'message' => 'Email notifications are disabled'
            ];
        }

        try {
            $fromAddress = Setting::get('mail_from_address', 'noreply@example.com');
            $fromName = Setting::get('mail_from_name', 'CMS Backend');

            Mail::send('emails.notification', [
                'message' => $message,
                'data' => $data
            ], function ($mail) use ($to, $subject, $fromAddress, $fromName) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($fromAddress, $fromName);
            });

            return [
                'success' => true,
                'message' => 'Notification email sent successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send notification email: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to send notification email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail($user)
    {
        $subject = 'ยินดีต้อนรับสู่ระบบ CMS Backend';
        $message = "สวัสดี {$user->name},\n\nยินดีต้อนรับสู่ระบบ CMS Backend! บัญชีของคุณได้ถูกสร้างเรียบร้อยแล้ว\n\nข้อมูลการเข้าสู่ระบบ:\nอีเมล: {$user->email}\n\nหากคุณมีคำถามใดๆ กรุณาติดต่อทีมสนับสนุน\n\nขอบคุณ";

        return $this->sendNotification($user->email, $subject, $message, [
            'user' => $user,
            'type' => 'welcome'
        ]);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($user, $resetToken)
    {
        $subject = 'รีเซ็ตรหัสผ่าน - CMS Backend';
        $resetUrl = url("/password/reset/{$resetToken}");
        $message = "สวัสดี {$user->name},\n\nคุณได้ขอรีเซ็ตรหัสผ่านสำหรับบัญชีของคุณ\n\nคลิกที่ลิงก์ด้านล่างเพื่อรีเซ็ตรหัสผ่าน:\n{$resetUrl}\n\nลิงก์นี้จะหมดอายุใน 60 นาที\n\nหากคุณไม่ได้ขอรีเซ็ตรหัสผ่าน กรุณาเพิกเฉยต่ออีเมลนี้\n\nขอบคุณ";

        return $this->sendNotification($user->email, $subject, $message, [
            'user' => $user,
            'reset_token' => $resetToken,
            'type' => 'password_reset'
        ]);
    }

    /**
     * Send account status change notification
     */
    public function sendAccountStatusChangeEmail($user, $status)
    {
        $statusText = $status ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        $subject = "สถานะบัญชีถูกเปลี่ยนแปลง - CMS Backend";
        $message = "สวัสดี {$user->name},\n\nสถานะบัญชีของคุณได้ถูก{$statusText}เรียบร้อยแล้ว\n\nหากคุณมีคำถามใดๆ กรุณาติดต่อทีมสนับสนุน\n\nขอบคุณ";

        return $this->sendNotification($user->email, $subject, $message, [
            'user' => $user,
            'status' => $status,
            'type' => 'account_status_change'
        ]);
    }

    /**
     * Send system notification
     */
    public function sendSystemNotification($to, $subject, $message, $priority = 'normal')
    {
        $priorityPrefix = '';
        switch ($priority) {
            case 'high':
                $priorityPrefix = '[สำคัญ] ';
                break;
            case 'urgent':
                $priorityPrefix = '[ด่วน] ';
                break;
        }

        $subject = $priorityPrefix . $subject;

        return $this->sendNotification($to, $subject, $message, [
            'priority' => $priority,
            'type' => 'system_notification'
        ]);
    }

    /**
     * Validate email settings
     */
    public function validateEmailSettings()
    {
        $requiredSettings = [
            'mail_from_address',
            'mail_from_name',
            'mail_smtp_host',
            'mail_smtp_port',
            'mail_smtp_encryption'
        ];

        $errors = [];
        foreach ($requiredSettings as $setting) {
            $value = Setting::get($setting);
            if (empty($value)) {
                $errors[] = "Setting '{$setting}' is required";
            }
        }

        // Validate email format
        $fromAddress = Setting::get('mail_from_address');
        if ($fromAddress && !filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format for 'mail_from_address'";
        }

        // Validate port
        $port = Setting::get('mail_smtp_port');
        if ($port && (!is_numeric($port) || $port < 1 || $port > 65535)) {
            $errors[] = "Invalid port number for 'mail_smtp_port'";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get email settings summary
     */
    public function getEmailSettingsSummary()
    {
        return [
            'from_address' => Setting::get('mail_from_address'),
            'from_name' => Setting::get('mail_from_name'),
            'smtp_host' => Setting::get('mail_smtp_host'),
            'smtp_port' => Setting::get('mail_smtp_port'),
            'smtp_encryption' => Setting::get('mail_smtp_encryption'),
            'notifications_enabled' => Setting::get('enable_email_notifications', true),
            'queue_enabled' => Setting::get('mail_queue_enabled', false),
            'retry_attempts' => Setting::get('mail_retry_attempts', 3),
        ];
    }
}
