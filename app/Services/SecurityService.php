<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SecurityService
{
    /**
     * Check password strength based on current settings
     */
    public function checkPasswordStrength($password)
    {
        $minLength = Setting::get('password_min_length', 8);
        $requireUppercase = Setting::get('password_require_uppercase', true);
        $requireLowercase = Setting::get('password_require_lowercase', true);
        $requireNumbers = Setting::get('password_require_numbers', true);
        $requireSpecial = Setting::get('password_require_special', true);

        $score = 0;
        $maxScore = 0;
        $feedback = [];

        // Length check
        $maxScore += 2;
        if (strlen($password) >= $minLength) {
            $score += 2;
        } else {
            $feedback[] = "รหัสผ่านต้องมีความยาวอย่างน้อย {$minLength} ตัวอักษร";
        }

        // Uppercase check
        if ($requireUppercase) {
            $maxScore += 1;
            if (preg_match('/[A-Z]/', $password)) {
                $score += 1;
            } else {
                $feedback[] = "รหัสผ่านต้องมีตัวอักษรพิมพ์ใหญ่";
            }
        }

        // Lowercase check
        if ($requireLowercase) {
            $maxScore += 1;
            if (preg_match('/[a-z]/', $password)) {
                $score += 1;
            } else {
                $feedback[] = "รหัสผ่านต้องมีตัวอักษรพิมพ์เล็ก";
            }
        }

        // Numbers check
        if ($requireNumbers) {
            $maxScore += 1;
            if (preg_match('/[0-9]/', $password)) {
                $score += 1;
            } else {
                $feedback[] = "รหัสผ่านต้องมีตัวเลข";
            }
        }

        // Special characters check
        if ($requireSpecial) {
            $maxScore += 1;
            if (preg_match('/[^A-Za-z0-9]/', $password)) {
                $score += 1;
            } else {
                $feedback[] = "รหัสผ่านต้องมีอักขระพิเศษ";
            }
        }

        // Calculate strength level
        $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
        
        if ($percentage >= 80) {
            $level = 'strong';
            $levelText = 'แข็งแรง';
            $color = 'green';
        } elseif ($percentage >= 60) {
            $level = 'medium';
            $levelText = 'ปานกลาง';
            $color = 'yellow';
        } elseif ($percentage >= 40) {
            $level = 'weak';
            $levelText = 'อ่อนแอ';
            $color = 'orange';
        } else {
            $level = 'very_weak';
            $levelText = 'อ่อนแอมาก';
            $color = 'red';
        }

        return [
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => round($percentage),
            'level' => $level,
            'level_text' => $levelText,
            'color' => $color,
            'feedback' => $feedback,
            'is_valid' => empty($feedback)
        ];
    }

    /**
     * Generate secure password
     */
    public function generateSecurePassword($length = 12, $includeUppercase = true, $includeLowercase = true, $includeNumbers = true, $includeSpecial = true)
    {
        $chars = '';
        
        if ($includeLowercase) {
            $chars .= 'abcdefghijklmnopqrstuvwxyz';
        }
        
        if ($includeUppercase) {
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        if ($includeNumbers) {
            $chars .= '0123456789';
        }
        
        if ($includeSpecial) {
            $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }

        $password = '';
        $charsLength = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charsLength - 1)];
        }

        return $password;
    }

    /**
     * Validate IP whitelist
     */
    public function validateIpWhitelist($ipList)
    {
        $errors = [];
        $ips = array_filter(array_map('trim', explode(',', $ipList)));

        foreach ($ips as $ip) {
            if (!empty($ip)) {
                // Check if it's a valid IP or CIDR
                if (!filter_var($ip, FILTER_VALIDATE_IP) && !$this->isValidCIDR($ip)) {
                    $errors[] = "IP address '{$ip}' is not valid";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'ip_count' => count($ips)
        ];
    }

    /**
     * Check if string is valid CIDR notation
     */
    private function isValidCIDR($cidr)
    {
        if (!strpos($cidr, '/')) {
            return false;
        }

        list($ip, $mask) = explode('/', $cidr);
        
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        $mask = (int)$mask;
        return $mask >= 0 && $mask <= 32;
    }

    /**
     * Generate security report
     */
    public function generateSecurityReport()
    {
        $settings = Setting::where('group', 'security')->get()->keyBy('key');
        
        $report = [
            'password_policy' => [
                'min_length' => $settings->get('password_min_length', 8),
                'require_uppercase' => $settings->get('password_require_uppercase', true),
                'require_lowercase' => $settings->get('password_require_lowercase', true),
                'require_numbers' => $settings->get('password_require_numbers', true),
                'require_special' => $settings->get('password_require_special', true),
                'expiry_days' => $settings->get('password_expiry_days', 90),
                'history_count' => $settings->get('password_history_count', 5),
            ],
            'login_security' => [
                'max_attempts' => $settings->get('max_login_attempts', 5),
                'lockout_duration' => $settings->get('lockout_duration', 15),
                'session_timeout' => $settings->get('session_timeout', 120),
                'enable_2fa' => $settings->get('enable_2fa', false),
                'enable_captcha' => $settings->get('enable_captcha', false),
            ],
            'access_control' => [
                'enable_ip_whitelist' => $settings->get('enable_ip_whitelist', false),
                'ip_whitelist' => $settings->get('ip_whitelist', ''),
            ],
            'monitoring' => [
                'enable_audit_log' => $settings->get('enable_audit_log', true),
                'enable_login_notifications' => $settings->get('enable_login_notifications', true),
                'enable_failed_login_alerts' => $settings->get('enable_failed_login_alerts', true),
            ],
            'recommendations' => $this->getSecurityRecommendations($settings),
            'risk_level' => $this->calculateRiskLevel($settings),
        ];

        return $report;
    }

    /**
     * Get security recommendations
     */
    private function getSecurityRecommendations($settings)
    {
        $recommendations = [];

        // Password policy recommendations
        if ($settings->get('password_min_length', 8) < 12) {
            $recommendations[] = 'เพิ่มความยาวขั้นต่ำของรหัสผ่านเป็น 12 ตัวอักษร';
        }

        if (!$settings->get('password_require_special', true)) {
            $recommendations[] = 'เปิดใช้งานการต้องการอักขระพิเศษในรหัสผ่าน';
        }

        if ($settings->get('password_expiry_days', 90) > 180) {
            $recommendations[] = 'ลดระยะเวลาหมดอายุของรหัสผ่านเป็น 90 วัน';
        }

        // Login security recommendations
        if (!$settings->get('enable_2fa', false)) {
            $recommendations[] = 'เปิดใช้งานการยืนยันตัวตนสองขั้นตอน (2FA)';
        }

        if ($settings->get('max_login_attempts', 5) > 5) {
            $recommendations[] = 'ลดจำนวนครั้งสูงสุดในการล็อกอินผิดเป็น 5 ครั้ง';
        }

        if ($settings->get('session_timeout', 120) > 240) {
            $recommendations[] = 'ลดระยะเวลาสิ้นสุดของ session เป็น 120 นาที';
        }

        // Access control recommendations
        if (!$settings->get('enable_ip_whitelist', false)) {
            $recommendations[] = 'พิจารณาเปิดใช้งาน IP Whitelist สำหรับผู้ดูแลระบบ';
        }

        // Monitoring recommendations
        if (!$settings->get('enable_audit_log', true)) {
            $recommendations[] = 'เปิดใช้งาน Audit Log เพื่อติดตามกิจกรรม';
        }

        if (!$settings->get('enable_failed_login_alerts', true)) {
            $recommendations[] = 'เปิดใช้งานการแจ้งเตือนการล็อกอินผิด';
        }

        return $recommendations;
    }

    /**
     * Calculate overall risk level
     */
    private function calculateRiskLevel($settings)
    {
        $riskScore = 0;
        $maxScore = 0;

        // Password policy (40% weight)
        $maxScore += 40;
        if ($settings->get('password_min_length', 8) >= 12) $riskScore += 10;
        if ($settings->get('password_require_uppercase', true)) $riskScore += 5;
        if ($settings->get('password_require_lowercase', true)) $riskScore += 5;
        if ($settings->get('password_require_numbers', true)) $riskScore += 5;
        if ($settings->get('password_require_special', true)) $riskScore += 5;
        if ($settings->get('password_expiry_days', 90) <= 90) $riskScore += 10;

        // Login security (30% weight)
        $maxScore += 30;
        if ($settings->get('enable_2fa', false)) $riskScore += 15;
        if ($settings->get('max_login_attempts', 5) <= 5) $riskScore += 10;
        if ($settings->get('session_timeout', 120) <= 120) $riskScore += 5;

        // Monitoring (20% weight)
        $maxScore += 20;
        if ($settings->get('enable_audit_log', true)) $riskScore += 10;
        if ($settings->get('enable_failed_login_alerts', true)) $riskScore += 10;

        // Access control (10% weight)
        $maxScore += 10;
        if ($settings->get('enable_ip_whitelist', false)) $riskScore += 10;

        $percentage = $maxScore > 0 ? ($riskScore / $maxScore) * 100 : 0;

        if ($percentage >= 80) {
            return ['level' => 'low', 'text' => 'ต่ำ', 'color' => 'green'];
        } elseif ($percentage >= 60) {
            return ['level' => 'medium', 'text' => 'ปานกลาง', 'color' => 'yellow'];
        } elseif ($percentage >= 40) {
            return ['level' => 'high', 'text' => 'สูง', 'color' => 'orange'];
        } else {
            return ['level' => 'critical', 'text' => 'วิกฤต', 'color' => 'red'];
        }
    }

    /**
     * Check security settings for user login
     */
    public function checkSecuritySettings($user, $request)
    {
        // Check if user needs to change password
        $passwordExpiryDays = Setting::get('password_expiry_days', 90);
        if ($user->password_changed_at) {
            $daysSinceChange = $user->password_changed_at->diffInDays(now());
            if ($daysSinceChange >= $passwordExpiryDays) {
                Log::warning("User {$user->email} password expired", [
                    'user_id' => $user->id,
                    'days_since_change' => $daysSinceChange,
                    'ip_address' => $request->ip()
                ]);
            }
        }

        // Check IP whitelist if enabled
        $enableIpWhitelist = Setting::get('enable_ip_whitelist', false);
        if ($enableIpWhitelist) {
            $ipWhitelist = Setting::get('ip_whitelist', '');
            $clientIp = $request->ip();
            
            if (!empty($ipWhitelist)) {
                $allowedIps = array_filter(array_map('trim', explode(',', $ipWhitelist)));
                $isAllowed = false;
                
                foreach ($allowedIps as $allowedIp) {
                    if ($this->ipMatches($clientIp, $allowedIp)) {
                        $isAllowed = true;
                        break;
                    }
                }
                
                if (!$isAllowed) {
                    Log::warning("User {$user->email} login from non-whitelisted IP", [
                        'user_id' => $user->id,
                        'client_ip' => $clientIp,
                        'whitelist' => $ipWhitelist
                    ]);
                }
            }
        }

        // Check session timeout
        $sessionTimeout = Setting::get('session_timeout', 120);
        if ($sessionTimeout > 0) {
            // This will be handled by Laravel's session configuration
            Log::info("Session timeout set to {$sessionTimeout} minutes for user {$user->email}");
        }

        return true;
    }

    /**
     * Check if IP matches allowed IP or CIDR
     */
    private function ipMatches($ip, $allowedIp)
    {
        if ($ip === $allowedIp) {
            return true;
        }

        // Check CIDR notation
        if (strpos($allowedIp, '/') !== false) {
            list($subnet, $mask) = explode('/', $allowedIp);
            $ipLong = ip2long($ip);
            $subnetLong = ip2long($subnet);
            $maskLong = -1 << (32 - $mask);
            
            return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
        }

        return false;
    }

    /**
     * Check if current security settings are compliant
     */
    public function checkCompliance()
    {
        $settings = Setting::where('group', 'security')->get()->keyBy('key');
        
        $compliance = [
            'password_policy' => [
                'min_length_12' => $settings->get('password_min_length', 8) >= 12,
                'require_uppercase' => $settings->get('password_require_uppercase', true),
                'require_lowercase' => $settings->get('password_require_lowercase', true),
                'require_numbers' => $settings->get('password_require_numbers', true),
                'require_special' => $settings->get('password_require_special', true),
                'expiry_90_days' => $settings->get('password_expiry_days', 90) <= 90,
            ],
            'login_security' => [
                'max_attempts_5' => $settings->get('max_login_attempts', 5) <= 5,
                'lockout_15_min' => $settings->get('lockout_duration', 15) <= 15,
                'session_120_min' => $settings->get('session_timeout', 120) <= 120,
                'enable_2fa' => $settings->get('enable_2fa', false),
            ],
            'monitoring' => [
                'audit_log' => $settings->get('enable_audit_log', true),
                'login_notifications' => $settings->get('enable_login_notifications', true),
                'failed_login_alerts' => $settings->get('enable_failed_login_alerts', true),
            ],
        ];

        return $compliance;
    }
}
