<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip session timeout check for login routes
        if ($request->is('login') || $request->is('admin/login') || $request->is('logout')) {
            return $next($request);
        }
        
        // Check for both Laravel auth and custom admin session
        $isAuthenticated = Auth::check() || session('admin_logged_in');
        
        if ($isAuthenticated) {
            $lastActivity = session('last_activity');
            $timeout = config('session.lifetime', 30) * 60; // Convert minutes to seconds
            
            // Check if session has expired
            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                // Log session timeout for security monitoring
                Log::warning('Session timeout detected', [
                    'user_id' => Auth::id() ?? session('admin_user_id'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_activity' => $lastActivity,
                    'timeout_duration' => $timeout
                ]);
                
                // Clear all session data
                $this->clearSessionData();
                
                // Regenerate session ID for security
                session()->regenerate();
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่',
                        'session_expired' => true,
                        'redirect_url' => route('login')
                    ], 401);
                }
                
                return redirect()->route('login')->with('error', 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่');
            }
            
            // Update last activity time
            session(['last_activity' => time()]);
            
            // Check for suspicious activity (multiple sessions from different IPs)
            $this->checkSuspiciousActivity($request);
        }
        
        return $next($request);
    }
    
    /**
     * Clear all session data securely
     */
    private function clearSessionData(): void
    {
        $sessionKeys = [
            'admin_logged_in',
            'admin_user_id',
            'admin_user_email',
            'admin_user_name',
            'admin_user_role',
            'last_activity',
            'login_web_' . config('auth.guards.web.session'),
            'password_hash_' . config('auth.guards.web.session')
        ];
        
        foreach ($sessionKeys as $key) {
            session()->forget($key);
        }
        
        // Invalidate session completely
        session()->invalidate();
    }
    
    /**
     * Check for suspicious activity
     */
    private function checkSuspiciousActivity(Request $request): void
    {
        $currentIp = $request->ip();
        $storedIp = session('user_ip_address');
        
        // If this is the first request, store the IP
        if (!$storedIp) {
            session(['user_ip_address' => $currentIp]);
            return;
        }
        
        // Check if IP has changed (potential session hijacking)
        if ($storedIp !== $currentIp) {
            Log::warning('Suspicious activity detected - IP address changed', [
                'user_id' => Auth::id() ?? session('admin_user_id'),
                'old_ip' => $storedIp,
                'new_ip' => $currentIp,
                'user_agent' => $request->userAgent(),
                'session_id' => session()->getId()
            ]);
            
            // Update stored IP
            session(['user_ip_address' => $currentIp]);
        }
    }
}