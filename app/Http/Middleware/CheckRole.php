<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'กรุณาเข้าสู่ระบบก่อน'
                ], 401);
            }
            return redirect()->route('login');
        }

        // ตรวจสอบบทบาท
        if (!auth()->user()->hasAnyRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'คุณไม่มีสิทธิ์เข้าถึงฟังก์ชันนี้'
                ], 403);
            }
            
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงฟังก์ชันนี้');
        }

        return $next($request);
    }
}