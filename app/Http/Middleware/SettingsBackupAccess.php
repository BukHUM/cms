<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SettingsBackupAccess
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

        // Check if user has admin role or specific backup permission
        $hasAccess = false;

        // Check for admin role
        if ($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'administrator')) {
            $hasAccess = true;
        }

        // Check for specific backup permission
        if (!$hasAccess) {
            $hasAccess = $user->hasPermissionTo('settings-backup-access') || 
                        $user->hasPermissionTo('backup-manage') ||
                        $user->hasPermissionTo('system-backup');
        }

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'คุณไม่มีสิทธิ์เข้าถึงการตั้งค่าการสำรองข้อมูล'
                ], 403);
            }

            return redirect()->route('backend.dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงการตั้งค่าการสำรองข้อมูล');
        }

        return $next($request);
    }
}
