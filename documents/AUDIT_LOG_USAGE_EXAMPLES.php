<?php

namespace App\Http\Controllers;

use App\Services\AuditLogService;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ตัวอย่างการใช้งาน AuditLogService
 * 
 * ไฟล์นี้แสดงวิธีการใช้งาน AuditLogService ใน Controller ต่างๆ
 */
class AuditLogExampleController extends Controller
{
    /**
     * ตัวอย่างการบันทึก Audit Log เมื่อสร้าง User ใหม่
     */
    public function storeUser(Request $request)
    {
        // สร้าง User ใหม่
        $user = User::create($request->validated());
        
        // บันทึก Audit Log สำหรับการสร้าง User
        AuditLogService::logCreated(
            $user,                    // Model ที่ถูกสร้าง
            auth()->user(),           // ผู้ใช้ที่ทำการสร้าง
            $request,                 // Request object
            'user_management'         // Tag สำหรับจัดกลุ่ม
        );
        
        return response()->json(['success' => true]);
    }

    /**
     * ตัวอย่างการบันทึก Audit Log เมื่อแก้ไข User
     */
    public function updateUser(Request $request, User $user)
    {
        // เก็บค่าเดิมก่อนการแก้ไข
        $oldValues = $user->toArray();
        
        // อัพเดต User
        $user->update($request->validated());
        
        // บันทึก Audit Log สำหรับการแก้ไข User
        AuditLogService::logUpdated(
            $user,                    // Model ที่ถูกแก้ไข
            $oldValues,              // ค่าเดิม
            $user->fresh()->toArray(), // ค่าใหม่
            auth()->user(),           // ผู้ใช้ที่ทำการแก้ไข
            $request,                 // Request object
            'user_management'         // Tag สำหรับจัดกลุ่ม
        );
        
        return response()->json(['success' => true]);
    }

    /**
     * ตัวอย่างการบันทึก Audit Log เมื่อลบ User
     */
    public function deleteUser(User $user)
    {
        // บันทึก Audit Log สำหรับการลบ User
        AuditLogService::logDeleted(
            $user,                    // Model ที่ถูกลบ
            auth()->user(),           // ผู้ใช้ที่ทำการลบ
            request(),                // Request object
            'user_management'         // Tag สำหรับจัดกลุ่ม
        );
        
        // ลบ User
        $user->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * ตัวอย่างการบันทึก Audit Log แบบกำหนดเอง
     */
    public function customAction(Request $request)
    {
        $user = auth()->user();
        
        // บันทึก Audit Log แบบกำหนดเอง
        AuditLogService::log(
            'custom_action',          // Event type
            $user,                    // Auditable model
            ['status' => 'inactive'], // Old values
            ['status' => 'active'],   // New values
            $user,                    // User who performed action
            $request,                 // Request object
            'custom_actions'          // Tag
        );
        
        return response()->json(['success' => true]);
    }

    /**
     * ตัวอย่างการดึงข้อมูล Audit Log ด้วย Service
     */
    public function getUserAuditLogs(User $user)
    {
        // ดึง Audit Log ของ User เฉพาะ
        $auditLogs = AuditLogService::getLogsByUser($user, 20);
        
        return response()->json($auditLogs);
    }

    /**
     * ตัวอย่างการดึงสถิติ Audit Log
     */
    public function getAuditLogStatistics(Request $request)
    {
        $filters = [];
        
        // เพิ่มตัวกรองตามช่วงวันที่
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $filters['date_from'] = $request->date_from;
            $filters['date_to'] = $request->date_to;
        }
        
        // ดึงสถิติ
        $statistics = AuditLogService::getStatistics($filters);
        
        return response()->json($statistics);
    }

    /**
     * ตัวอย่างการส่งออก Audit Log
     */
    public function exportAuditLogs(Request $request)
    {
        $filters = $request->only(['event', 'auditable_type', 'date_from', 'date_to', 'search']);
        
        // ส่งออกเป็น CSV
        $filePath = AuditLogService::exportToCsv($filters);
        
        return response()->download($filePath);
    }

    /**
     * ตัวอย่างการทำความสะอาด Audit Log เก่า
     */
    public function cleanupOldAuditLogs()
    {
        // ลบ Audit Log ที่เก่ากว่า 365 วัน
        $deletedCount = AuditLogService::cleanupOldLogs(365);
        
        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'message' => "ลบ Audit Log เก่า {$deletedCount} รายการ"
        ]);
    }

    /**
     * ตัวอย่างการดึงข้อมูลสำหรับ Dashboard
     */
    public function getDashboardData()
    {
        // ดึงข้อมูลสรุปสำหรับ Dashboard
        $summary = AuditLogService::getDashboardSummary();
        
        return response()->json($summary);
    }
}

/**
 * ตัวอย่างการใช้งานใน Model Observer
 */
class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        AuditLogService::logCreated(
            $user,
            auth()->user(),
            request(),
            'user_management'
        );
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user)
    {
        // ต้องเก็บค่าเดิมก่อนการอัพเดต
        // สามารถใช้ $user->getOriginal() หรือ $user->getChanges()
        $oldValues = $user->getOriginal();
        $newValues = $user->getChanges();
        
        if (!empty($newValues)) {
            AuditLogService::logUpdated(
                $user,
                $oldValues,
                $newValues,
                auth()->user(),
                request(),
                'user_management'
            );
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user)
    {
        AuditLogService::logDeleted(
            $user,
            auth()->user(),
            request(),
            'user_management'
        );
    }
}

/**
 * ตัวอย่างการใช้งานใน Middleware
 */
class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        
        // บันทึก Audit Log สำหรับการเข้าถึงหน้าเฉพาะ
        if ($request->is('backend/users/*') && $request->method() !== 'GET') {
            $user = auth()->user();
            
            AuditLogService::log(
                'page_access',
                $user,
                null,
                ['page' => $request->path(), 'method' => $request->method()],
                $user,
                $request,
                'page_access'
            );
        }
        
        return $response;
    }
}
