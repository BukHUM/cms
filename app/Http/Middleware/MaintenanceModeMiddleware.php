<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = SettingsHelper::get('maintenance_mode', false);
        
        if ($maintenanceMode) {
            // Allow access to admin settings to disable maintenance mode
            $allowedPaths = [
                'admin/settings',
                'admin/settings/general',
                'login',
                'logout'
            ];
            
            $currentPath = $request->path();
            
            // Check if current path is allowed
            $isAllowed = false;
            foreach ($allowedPaths as $allowedPath) {
                if (str_starts_with($currentPath, $allowedPath)) {
                    $isAllowed = true;
                    break;
                }
            }
            
            // Also allow admin users to access admin panel
            if (session('admin_logged_in')) {
                $isAllowed = true;
            }
            
            // If not allowed, show maintenance page
            if (!$isAllowed) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ระบบกำลังอยู่ในโหมดบำรุงรักษา กรุณาลองใหม่อีกครั้งในภายหลัง',
                        'maintenance_mode' => true
                    ], 503);
                }
                
                return response()->view('maintenance', [], 503);
            }
        }
        
        return $next($request);
    }
}
