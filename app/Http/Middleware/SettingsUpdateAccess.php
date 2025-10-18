<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SettingsUpdateAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $user = Auth::user();

        // Check if user has admin role or specific permission
        if (!$user->hasRole('admin') && !$user->hasPermission('settings_update_manage')) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงการจัดการการอัพเดตระบบ');
        }

        // Additional security checks for production
        if (app()->environment('production')) {
            // Check if user is in admin IP whitelist (if configured)
            $adminIps = config('app.admin_ips', []);
            if (!empty($adminIps) && !in_array($request->ip(), $adminIps)) {
                abort(403, 'การเข้าถึงจาก IP นี้ไม่ได้รับอนุญาต');
            }

            // Check if user has recent activity (within last 30 minutes)
            if (!$user->last_activity_at || $user->last_activity_at->diffInMinutes(now()) > 30) {
                return redirect()->route('login')->with('error', 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่');
            }
        }

        // Log access attempt
        \Log::info('Settings Update Access', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route' => $request->route()->getName(),
            'timestamp' => now()
        ]);

        return $next($request);
    }
}
