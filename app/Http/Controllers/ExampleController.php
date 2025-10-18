<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Example of using settings in controller
     */
    public function index()
    {
        // Get individual settings
        $siteName = setting('site_name');
        $siteDescription = setting('site_description');
        $maxUploadSize = setting('max_upload_size', 10); // with default value
        
        // Get all general settings
        $generalSettings = settings('general');
        
        // Get boolean settings
        $maintenanceMode = setting('maintenance_mode', false);
        $enableRegistration = setting('enable_registration', true);
        
        return view('example', compact(
            'siteName',
            'siteDescription', 
            'maxUploadSize',
            'generalSettings',
            'maintenanceMode',
            'enableRegistration'
        ));
    }
    
    /**
     * Example of updating settings
     */
    public function updateSettings(Request $request)
    {
        // Update individual settings
        set_setting('site_name', $request->site_name, 'string', 'general');
        set_setting('site_description', $request->site_description, 'string', 'general');
        set_setting('maintenance_mode', $request->maintenance_mode, 'boolean', 'general');
        
        // Clear cache to ensure changes take effect
        clear_settings_cache();
        
        return response()->json(['message' => 'Settings updated successfully']);
    }
    
    /**
     * Example of checking maintenance mode
     */
    public function checkMaintenance()
    {
        $maintenanceMode = setting('maintenance_mode', false);
        
        if ($maintenanceMode) {
            $message = setting('maintenance_message', 'System is under maintenance');
            return response()->json([
                'maintenance' => true,
                'message' => $message
            ]);
        }
        
        return response()->json(['maintenance' => false]);
    }
}
