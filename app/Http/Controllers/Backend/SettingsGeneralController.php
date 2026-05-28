<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsGeneralController extends BaseSettingsController
{
    protected $category = 'general';
    protected $viewPath = 'backend.settings-general';
    protected $routePrefix = 'backend.settings-general';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:settings.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:settings.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:settings.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:settings.delete', ['only' => ['destroy']]);
    }

    /**
     * Get available groups for general settings
     */
    protected function getCategoryGroups()
    {
        return [
            'site' => 'การตั้งค่าเว็บไซต์',
            'system' => 'การตั้งค่าระบบ',
            'email' => 'การตั้งค่าอีเมล',
            'security' => 'การตั้งค่าความปลอดภัย',
            'appearance' => 'การตั้งค่าการแสดงผล',
            'api' => 'การตั้งค่า API',
            'maintenance' => 'การตั้งค่าบำรุงรักษา',
        ];
    }
}
