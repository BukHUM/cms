<?php

namespace App\Services;

use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginSecurityService
{
    /**
     * Maximum failed attempts per email before lockout
     */
    const MAX_EMAIL_ATTEMPTS = 5;
    
    /**
     * Maximum failed attempts per IP before lockout
     */
    const MAX_IP_ATTEMPTS = 10;
    
    /**
     * Lockout duration for email (minutes)
     */
    const EMAIL_LOCKOUT_MINUTES = 15;
    
    /**
     * Lockout duration for IP (minutes)
     */
    const IP_LOCKOUT_MINUTES = 30;

    /**
     * Attempt to login with security checks
     */
    public function attemptLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Check if email is locked
        if (LoginAttempt::isEmailLocked($email, self::MAX_EMAIL_ATTEMPTS, self::EMAIL_LOCKOUT_MINUTES)) {
            Log::warning('Login attempt blocked - email locked', [
                'email' => $email,
                'ip' => $ipAddress,
                'attempts' => LoginAttempt::getFailedAttemptsCount($email, self::EMAIL_LOCKOUT_MINUTES)
            ]);
            
            return [
                'success' => false,
                'message' => 'บัญชีนี้ถูกล็อคชั่วคราวเนื่องจากพยายามเข้าสู่ระบบผิดหลายครั้ง กรุณาลองใหม่ในอีก 15 นาที',
                'locked' => true
            ];
        }

        // Check if IP is locked
        if (LoginAttempt::isIpLocked($ipAddress, self::MAX_IP_ATTEMPTS, self::IP_LOCKOUT_MINUTES)) {
            Log::warning('Login attempt blocked - IP locked', [
                'email' => $email,
                'ip' => $ipAddress,
                'attempts' => LoginAttempt::getFailedAttemptsCountByIp($ipAddress, self::IP_LOCKOUT_MINUTES)
            ]);
            
            return [
                'success' => false,
                'message' => 'IP นี้ถูกบล็อกชั่วคราวเนื่องจากพยายามเข้าสู่ระบบผิดหลายครั้ง กรุณาลองใหม่ในอีก 30 นาที',
                'locked' => true
            ];
        }

        // Find user
        $user = User::where('email', $email)->first();

        // Check credentials
        if ($user && Hash::check($password, $user->password)) {
            // Successful login
            LoginAttempt::recordAttempt($email, $ipAddress, true, $userAgent);
            
            Log::info('Successful login', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $ipAddress
            ]);

            return [
                'success' => true,
                'user' => $user,
                'message' => 'เข้าสู่ระบบสำเร็จ!'
            ];
        } else {
            // Failed login
            LoginAttempt::recordAttempt($email, $ipAddress, false, $userAgent);
            
            $failedAttempts = LoginAttempt::getFailedAttemptsCount($email, self::EMAIL_LOCKOUT_MINUTES);
            $remainingAttempts = self::MAX_EMAIL_ATTEMPTS - $failedAttempts;
            
            Log::warning('Failed login attempt', [
                'email' => $email,
                'ip' => $ipAddress,
                'failed_attempts' => $failedAttempts,
                'remaining_attempts' => $remainingAttempts
            ]);

            $message = 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง';
            if ($remainingAttempts > 0 && $remainingAttempts <= 3) {
                $message .= " (เหลืออีก {$remainingAttempts} ครั้งก่อนบัญชีจะถูกล็อค)";
            }

            return [
                'success' => false,
                'message' => $message,
                'remaining_attempts' => $remainingAttempts
            ];
        }
    }

    /**
     * Get security statistics for admin dashboard
     */
    public function getSecurityStats()
    {
        $last24Hours = now()->subHours(24);
        
        return [
            'total_attempts_24h' => LoginAttempt::where('attempted_at', '>', $last24Hours)->count(),
            'failed_attempts_24h' => LoginAttempt::where('attempted_at', '>', $last24Hours)->where('success', false)->count(),
            'successful_attempts_24h' => LoginAttempt::where('attempted_at', '>', $last24Hours)->where('success', true)->count(),
            'unique_ips_24h' => LoginAttempt::where('attempted_at', '>', $last24Hours)->distinct('ip_address')->count('ip_address'),
            'locked_emails' => $this->getLockedEmailsCount(),
            'locked_ips' => $this->getLockedIpsCount()
        ];
    }

    /**
     * Get count of currently locked emails
     */
    private function getLockedEmailsCount()
    {
        $emails = LoginAttempt::where('success', false)
            ->where('attempted_at', '>', now()->subMinutes(self::EMAIL_LOCKOUT_MINUTES))
            ->groupBy('email')
            ->havingRaw('COUNT(*) >= ?', [self::MAX_EMAIL_ATTEMPTS])
            ->pluck('email');

        return $emails->count();
    }

    /**
     * Get count of currently locked IPs
     */
    private function getLockedIpsCount()
    {
        $ips = LoginAttempt::where('success', false)
            ->where('attempted_at', '>', now()->subMinutes(self::IP_LOCKOUT_MINUTES))
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) >= ?', [self::MAX_IP_ATTEMPTS])
            ->pluck('ip_address');

        return $ips->count();
    }

    /**
     * Clean up old login attempts
     */
    public function cleanupOldAttempts()
    {
        $deleted = LoginAttempt::cleanupOldAttempts(30);
        
        Log::info('Cleaned up old login attempts', ['deleted_count' => $deleted]);
        
        return $deleted;
    }

    /**
     * Get recent suspicious activity
     */
    public function getSuspiciousActivity($hours = 24)
    {
        $since = now()->subHours($hours);
        
        // Multiple failed attempts from same IP
        $suspiciousIps = LoginAttempt::where('success', false)
            ->where('attempted_at', '>', $since)
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) >= ?', [5])
            ->selectRaw('ip_address, COUNT(*) as attempt_count')
            ->get();

        // Multiple failed attempts for same email
        $suspiciousEmails = LoginAttempt::where('success', false)
            ->where('attempted_at', '>', $since)
            ->groupBy('email')
            ->havingRaw('COUNT(*) >= ?', [3])
            ->selectRaw('email, COUNT(*) as attempt_count')
            ->get();

        return [
            'suspicious_ips' => $suspiciousIps,
            'suspicious_emails' => $suspiciousEmails
        ];
    }
}
