<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsPerformanceController extends BaseSettingsController
{
    protected $category = 'performance';
    protected $viewPath = 'backend.settings-performance';
    protected $routePrefix = 'backend.settings-performance';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:performance.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:performance.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:performance.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:performance.delete', ['only' => ['destroy']]);
    }

    /**
     * Get available groups for performance settings
     */
    protected function getCategoryGroups()
    {
        return [
            'cache' => 'การตั้งค่า Cache',
            'database' => 'การตั้งค่าฐานข้อมูล',
            'memory' => 'การตั้งค่าหน่วยความจำ',
            'session' => 'การตั้งค่า Session',
            'queue' => 'การตั้งค่า Queue',
            'logging' => 'การตั้งค่า Logging',
            'optimization' => 'การตั้งค่าการปรับปรุงประสิทธิภาพ',
        ];
    }
}
