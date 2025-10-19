<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = setting('maintenance_mode', false);
        
        if ($maintenanceMode) {
            // Allow access to backend routes
            if ($request->is('backend/*') || $request->is('admin/*')) {
                return $next($request);
            }
            
            // Allow access to login/logout routes
            if ($request->is('login') || $request->is('logout') || $request->is('auth/*')) {
                return $next($request);
            }
            
            // Allow access to API routes if needed
            if ($request->is('api/*')) {
                return $next($request);
            }
            
            // Show maintenance page for all other routes
            $maintenanceMessage = setting('maintenance_message', 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ');
            
            return response()->view('maintenance', [
                'message' => $maintenanceMessage,
                'siteName' => setting('site_name', 'CMS Backend System'),
                'siteDescription' => setting('site_description', 'ระบบจัดการเนื้อหาแบบครบวงจร'),
            ], 503);
        }

        return $next($request);
    }
}