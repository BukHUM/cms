<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'User not authenticated'
                ], 401);
            }
            
            return redirect()->route('backend.dashboard')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $user = auth()->user();

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden',
                    'error' => 'Insufficient permissions',
                    'required_permission' => $permission
                ], 403);
            }
            
            // For web requests, redirect to dashboard with error message
            return redirect()->route('backend.dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}
