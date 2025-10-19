<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if maintenance mode is enabled
            $maintenanceMode = Setting::get('maintenance_mode', false);
        
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
            $maintenanceMessage = Setting::get('maintenance_message', 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ');
            
            return response()->view('maintenance', [
                'message' => $maintenanceMessage,
                'siteName' => Setting::get('site_name', 'CMS Backend System'),
                'siteDescription' => Setting::get('site_description', 'ระบบจัดการเนื้อหาแบบครบวงจร'),
            ], 503);
        }
        } catch (\Exception $e) {
            // If there's an error accessing settings, continue normally
            // This prevents the middleware from breaking the entire application
            \Log::warning('Maintenance mode check failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}