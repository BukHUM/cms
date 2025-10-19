<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Check maintenance status
     */
    public function status()
    {
        $maintenanceMode = setting('maintenance_mode', false);
        
        if ($maintenanceMode) {
            $message = setting('maintenance_message', 'ระบบกำลังปรับปรุง กรุณาติดต่อผู้ดูแลระบบ');
            
            return response()->json([
                'maintenance' => true,
                'message' => $message,
                'site_name' => setting('site_name', 'CMS Backend System'),
                'site_description' => setting('site_description', 'ระบบจัดการเนื้อหาแบบครบวงจร'),
            ]);
        }
        
        return response()->json([
            'maintenance' => false,
            'message' => 'System is operational'
        ]);
    }
}