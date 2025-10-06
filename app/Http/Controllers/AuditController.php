<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    /**
     * Get recent audit logs
     */
    public function getRecentLogs(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $logs = AuditLog::getRecentLogs($limit);

            return response()->json([
                'success' => true,
                'data' => $logs->map(function ($log) {
                    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
                    $userName = 'N/A';
                    if ($log->user_email) {
                        $user = DB::table('laravel_users')
                            ->where('email', $log->user_email)
                            ->first();
                        if ($user) {
                            $userName = $user->name ?? $user->email;
                        }
                    }

                    return [
                        'id' => $log->id,
                        'user_email' => $log->user_email,
                        'user_name' => $userName,
                        'action' => $log->action,
                        'formatted_action' => $log->formatted_action,
                        'description' => $log->description,
                        'ip_address' => $log->ip_address,
                        'status' => $log->status,
                        'formatted_status' => $log->formatted_status,
                        'status_badge' => $log->status_badge,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                        'created_at_human' => $log->created_at->diffForHumans()
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit logs with filters
     */
    public function getLogs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'integer|min:1|max:100',
                'page' => 'integer|min:1',
                'action' => 'string',
                'status' => 'string|in:success,failed,error',
                'user_email' => 'string',
                'date_from' => 'date',
                'date_to' => 'date|after_or_equal:date_from'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $query = AuditLog::query();

            // Apply filters
            if ($request->has('action')) {
                $query->where('action', $request->action);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('user_email')) {
                $query->where('user_email', 'like', '%' . $request->user_email . '%');
            }

            if ($request->has('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
            }

            $limit = $request->get('limit', 20);
            $page = $request->get('page', 1);

            $logs = $query->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => collect($logs->items())->map(function ($log) {
                    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
                    $userName = 'N/A';
                    if ($log->user_email) {
                        $user = DB::table('laravel_users')
                            ->where('email', $log->user_email)
                            ->first();
                        if ($user) {
                            $userName = $user->name ?? $user->email;
                        }
                    }

                    return [
                        'id' => $log->id,
                        'user_email' => $log->user_email,
                        'user_name' => $userName,
                        'action' => $log->action,
                        'formatted_action' => $log->formatted_action,
                        'description' => $log->description,
                        'ip_address' => $log->ip_address,
                        'status' => $log->status,
                        'formatted_status' => $log->formatted_status,
                        'status_badge' => $log->status_badge,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                        'created_at_human' => $log->created_at->diffForHumans()
                    ];
                }),
                'pagination' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'total' => $logs->total(),
                    'has_more' => $logs->hasMorePages()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลด Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $days = $request->get('days', 30);
            $statistics = AuditLog::getStatistics($days);

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถโหลดสถิติได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function exportLogs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'format' => 'string|in:csv,json',
                'date_from' => 'date',
                'date_to' => 'date|after_or_equal:date_from',
                'action' => 'string',
                'status' => 'string|in:success,failed,error'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $query = AuditLog::query();

            // Apply filters
            if ($request->has('action')) {
                $query->where('action', $request->action);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            $format = $request->get('format', 'csv');
            $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.' . $format;

            if ($format === 'csv') {
                // เพิ่ม BOM สำหรับ UTF-8 เพื่อให้ Excel อ่านภาษาไทยได้
                $csvData = "\xEF\xBB\xBF";
                $csvData .= "ID,User Email,Action,Description,IP Address,Status,Created At\n";
                
                foreach ($logs as $log) {
                    $csvData .= sprintf(
                        "%d,%s,%s,%s,%s,%s,%s\n",
                        $log->id,
                        $log->user_email ?? '',
                        $log->formatted_action,
                        $log->description ?? '',
                        $log->ip_address ?? '',
                        $log->formatted_status,
                        $log->created_at->format('Y-m-d H:i:s')
                    );
                }

                return response($csvData)
                    ->header('Content-Type', 'text/csv; charset=UTF-8')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            } else {
                return response()->json([
                    'success' => true,
                    'data' => $logs,
                    'filename' => $filename
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่งออกข้อมูลได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create audit log entry
     */
    public function createLog(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|string',
                'user_id' => 'nullable|string',
                'user_email' => 'nullable|email',
                'resource_type' => 'nullable|string',
                'resource_id' => 'nullable|string',
                'description' => 'nullable|string',
                'old_values' => 'nullable|array',
                'new_values' => 'nullable|array',
                'status' => 'nullable|string|in:success,failed,error',
                'error_message' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 400);
            }

            $log = AuditLog::createLog($request->all());

            return response()->json([
                'success' => true,
                'message' => 'สร้าง Audit Log สำเร็จ',
                'data' => $log
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถสร้าง Audit Log ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit log by ID
     */
    public function getLogById($id)
    {
        try {
            $log = AuditLog::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'user_email' => $log->user_email,
                    'action' => $log->action,
                    'formatted_action' => $log->formatted_action,
                    'resource_type' => $log->resource_type,
                    'resource_id' => $log->resource_id,
                    'description' => $log->description,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'status' => $log->status,
                    'formatted_status' => $log->formatted_status,
                    'status_badge' => $log->status_badge,
                    'error_message' => $log->error_message,
                    'session_id' => $log->session_id,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => $log->created_at->diffForHumans()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบ Audit Log ที่ระบุ'
            ], 404);
        }
    }

    /**
     * Delete old audit logs
     */
    public function cleanupOldLogs(Request $request)
    {
        try {
            $days = $request->get('days', 90);
            
            // ถ้า days = 0 ให้ลบทั้งหมด
            if ($days == 0) {
                $deletedCount = AuditLog::count();
                AuditLog::truncate();
                
                return response()->json([
                    'success' => true,
                    'message' => 'ลบ Audit Logs ทั้งหมดสำเร็จ',
                    'deleted_count' => $deletedCount
                ]);
            }
            
            $cutoffDate = Carbon::now()->subDays($days);
            $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

            return response()->json([
                'success' => true,
                'message' => "ลบ Audit Logs เก่ากว่า {$days} วัน สำเร็จ",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถลบ Audit Logs ได้: ' . $e->getMessage()
            ], 500);
        }
    }
}