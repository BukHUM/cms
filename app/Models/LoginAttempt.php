<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $table = 'laravel_login_attempts';

    protected $fillable = [
        'email',
        'ip_address',
        'success',
        'user_agent',
        'attempted_at'
    ];

    protected $casts = [
        'success' => 'boolean',
        'attempted_at' => 'datetime'
    ];

    /**
     * Record a login attempt
     */
    public static function recordAttempt($email, $ipAddress, $success, $userAgent = null)
    {
        return self::create([
            'email' => $email,
            'ip_address' => $ipAddress,
            'success' => $success,
            'user_agent' => $userAgent,
            'attempted_at' => now()
        ]);
    }

    /**
     * Check if email is locked due to too many failed attempts
     */
    public static function isEmailLocked($email, $maxAttempts = 5, $lockoutMinutes = 15)
    {
        $lockoutTime = now()->subMinutes($lockoutMinutes);
        
        $failedAttempts = self::where('email', $email)
            ->where('success', false)
            ->where('attempted_at', '>', $lockoutTime)
            ->count();

        return $failedAttempts >= $maxAttempts;
    }

    /**
     * Check if IP is locked due to too many failed attempts
     */
    public static function isIpLocked($ipAddress, $maxAttempts = 10, $lockoutMinutes = 30)
    {
        $lockoutTime = now()->subMinutes($lockoutMinutes);
        
        $failedAttempts = self::where('ip_address', $ipAddress)
            ->where('success', false)
            ->where('attempted_at', '>', $lockoutTime)
            ->count();

        return $failedAttempts >= $maxAttempts;
    }

    /**
     * Get failed attempts count for email in last X minutes
     */
    public static function getFailedAttemptsCount($email, $minutes = 15)
    {
        $since = now()->subMinutes($minutes);
        
        return self::where('email', $email)
            ->where('success', false)
            ->where('attempted_at', '>', $since)
            ->count();
    }

    /**
     * Get failed attempts count for IP in last X minutes
     */
    public static function getFailedAttemptsCountByIp($ipAddress, $minutes = 30)
    {
        $since = now()->subMinutes($minutes);
        
        return self::where('ip_address', $ipAddress)
            ->where('success', false)
            ->where('attempted_at', '>', $since)
            ->count();
    }

    /**
     * Clean up old login attempts (older than 30 days)
     */
    public static function cleanupOldAttempts($days = 30)
    {
        $cutoffDate = now()->subDays($days);
        
        return self::where('attempted_at', '<', $cutoffDate)->delete();
    }

    /**
     * Get recent login attempts for an email
     */
    public static function getRecentAttemptsForEmail($email, $hours = 24)
    {
        $since = now()->subHours($hours);
        
        return self::where('email', $email)
            ->where('attempted_at', '>', $since)
            ->orderBy('attempted_at', 'desc')
            ->get();
    }

    /**
     * Get recent login attempts for an IP
     */
    public static function getRecentAttemptsForIp($ipAddress, $hours = 24)
    {
        $since = now()->subHours($hours);
        
        return self::where('ip_address', $ipAddress)
            ->where('attempted_at', '>', $since)
            ->orderBy('attempted_at', 'desc')
            ->get();
    }
}