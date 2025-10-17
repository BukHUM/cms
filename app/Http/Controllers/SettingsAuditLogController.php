<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SettingsAuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with(['user', 'auditable'])
            ->orderBy('created_at', 'desc');

        // Filter by event type
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by auditable type
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by user or content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('auditable_type', 'like', "%{$search}%")
                ->orWhere('event', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->paginate(20);

        // Get filter options
        $eventTypes = AuditLog::distinct()->pluck('event')->sort();
        $auditableTypes = AuditLog::distinct()->pluck('auditable_type')->sort();

        return view('backend.settings-auditlog.index', compact(
            'auditLogs',
            'eventTypes',
            'auditableTypes'
        ));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog): View
    {
        $auditLog->load(['user', 'auditable']);

        return view('backend.settings-auditlog.show', compact('auditLog'));
    }

    /**
     * Get audit logs for AJAX requests.
     */
    public function getAuditLogs(Request $request): JsonResponse
    {
        $query = AuditLog::with(['user', 'auditable'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('auditable_type', 'like', "%{$search}%")
                ->orWhere('event', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $auditLogs,
            'html' => view('backend.settings-auditlog.partials.audit-logs-table', compact('auditLogs'))->render()
        ]);
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        $query = AuditLog::with(['user', 'auditable'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('auditable_type', 'like', "%{$search}%")
                ->orWhere('event', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'วันที่/เวลา',
                'ผู้ใช้',
                'เหตุการณ์',
                'ประเภทข้อมูล',
                'ข้อมูลที่เปลี่ยนแปลง',
                'ค่าเดิม',
                'ค่าใหม่',
                'IP Address',
                'URL'
            ]);

            // CSV Data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->event,
                    $log->auditable_type,
                    $log->auditable_id,
                    $log->old_values ? json_encode($log->old_values, JSON_UNESCAPED_UNICODE) : '',
                    $log->new_values ? json_encode($log->new_values, JSON_UNESCAPED_UNICODE) : '',
                    $log->ip_address,
                    $log->url
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
