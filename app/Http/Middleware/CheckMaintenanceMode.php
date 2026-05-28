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
        // Default values
        $maintenanceMode = false;
        
        try {
            // Check if database is available
            if (\DB::connection()->getPdo()) {
                // Check if maintenance mode is enabled
                $maintenanceMode = Setting::get('maintenance_mode', false);
            }
        } catch (\Exception $e) {
            // If there's any error accessing settings, use default values
            \Log::warning('Maintenance mode check failed: ' . $e->getMessage());
            $maintenanceMode = false;
        }
        
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
            $maintenanceMessage = 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ';
            $siteName = 'CMS Backend System';
            $siteDescription = 'ระบบจัดการเนื้อหาแบบครบวงจร';
            
            try {
                if (\DB::connection()->getPdo()) {
                    $maintenanceMessage = Setting::get('maintenance_message', $maintenanceMessage);
                    $siteName = Setting::get('site_name', $siteName);
                    $siteDescription = Setting::get('site_description', $siteDescription);
                }
            } catch (\Exception $e) {
                // Use default values if settings can't be accessed
            }
            
            return response()->view('maintenance', [
                'message' => $maintenanceMessage,
                'siteName' => $siteName,
                'siteDescription' => $siteDescription,
            ], 503);
        }

        return $next($request);
    }
}