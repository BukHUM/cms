<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class AuthSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Add security headers
        $response = $next($request);
        
        // Content Security Policy
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
            "style-src 'self' 'unsafe-inline'; " .
            "img-src 'self' data: https:; " .
            "font-src 'self' data:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none';"
        );
        
        // Additional security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Rate limiting for auth routes
        if ($request->is('login', 'password/email', 'password/reset')) {
            $key = 'auth:' . $request->ip();
            
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                
                Log::warning('Auth rate limit exceeded', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->url(),
                    'seconds_remaining' => $seconds
                ]);
                
                return back()->withErrors([
                    'rate_limit' => "คุณพยายามเข้าสู่ระบบบ่อยเกินไป กรุณารอ {$seconds} วินาที"
                ])->withInput();
            }
            
            RateLimiter::hit($key, 300); // 5 minutes
        }
        
        return $response;
    }
}
