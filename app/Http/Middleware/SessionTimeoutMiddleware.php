<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Only check for authenticated admin users
        if (session('admin_logged_in')) {
            $lastActivity = session('last_activity');
            $timeout = config('session.lifetime', 120) * 60; // Convert minutes to seconds
            
            // Check if session has expired
            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                // Session expired, logout user
                session()->forget([
                    'admin_logged_in',
                    'admin_user_id',
                    'admin_user_email',
                    'admin_user_name',
                    'admin_user_role',
                    'last_activity'
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่',
                        'session_expired' => true
                    ], 401);
                }
                
                return redirect()->route('login')->with('error', 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่');
            }
            
            // Update last activity time
            session(['last_activity' => time()]);
        }
        
        return $next($request);
    }
}