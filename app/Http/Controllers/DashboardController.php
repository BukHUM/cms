<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Check authentication
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }
        // Get user statistics
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        
        // Get visits today (simplified - you might want to implement proper analytics)
        $visitsToday = AuditLog::whereDate('created_at', today())
            ->where('action', 'login')
            ->where('status', 'success')
            ->count();
        
        // Get pending jobs (simplified - you might want to implement proper job queue)
        $pendingJobs = 0; // This would come from your job queue system
        
        // Get recent activities
        $recentActivities = AuditLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday', 
            'visitsToday',
            'pendingJobs',
            'recentActivities'
        ));
    }
}
