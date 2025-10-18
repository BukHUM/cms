<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'recent_logins' => AuditLog::where('event', 'user_login')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'failed_logins' => AuditLog::where('event', 'login_failed')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        $recent_activities = AuditLog::with(['user', 'auditable'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $login_attempts_chart = AuditLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN event = "user_login" THEN 1 ELSE 0 END) as successful'),
                DB::raw('SUM(CASE WHEN event = "login_failed" THEN 1 ELSE 0 END) as failed')
            )
            ->whereIn('event', ['user_login', 'login_failed'])
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_activities' => $recent_activities,
            'login_attempts_chart' => $login_attempts_chart,
        ]);
    }
}
