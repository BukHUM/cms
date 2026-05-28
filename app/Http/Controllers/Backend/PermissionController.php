<?php

namespace App\Http\Controllers\Backend;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Permission::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('group', 'like', "%{$search}%");
            });
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->get('group'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status') === 'active';
            $query->where('is_active', $status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['name', 'display_name', 'group', 'is_active', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Get pagination setting or use default
        $paginationPerPage = \App\Models\Setting::get('default_pagination', 15, 'general');
        $permissions = $query->paginate($paginationPerPage)->withQueryString();

        // Get all groups for filter dropdown
        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();

        return view('backend.users-permissions.index', compact('permissions', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get all groups for dropdown
        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();
        
        return view('backend.users-permissions.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:core_permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'ชื่อสิทธิ์จำเป็นต้องระบุ',
            'name.unique' => 'ชื่อสิทธิ์นี้มีอยู่แล้ว',
            'display_name.required' => 'ชื่อแสดงจำเป็นต้องระบุ',
            'group.required' => 'กลุ่มสิทธิ์จำเป็นต้องระบุ',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
                'is_active' => $request->boolean('is_active', true),
            ]);

            DB::commit();

            return redirect()->route('permissions.index')
                ->with('success', 'สร้างสิทธิ์การเข้าถึงเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการสร้างสิทธิ์: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): View
    {
        // Load roles that have this permission
        $roles = $permission->roles()->with('users')->get();
        
        return view('backend.users-permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): View
    {
        // Get all groups for dropdown
        $groups = Permission::distinct()->pluck('group')->filter()->sort()->values();
        
        return view('backend.users-permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:core_permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'ชื่อสิทธิ์จำเป็นต้องระบุ',
            'name.unique' => 'ชื่อสิทธิ์นี้มีอยู่แล้ว',
            'display_name.required' => 'ชื่อแสดงจำเป็นต้องระบุ',
            'group.required' => 'กลุ่มสิทธิ์จำเป็นต้องระบุ',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $permission->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
                'is_active' => $request->boolean('is_active', true),
            ]);

            DB::commit();

            return redirect()->route('permissions.index')
                ->with('success', 'อัปเดตสิทธิ์การเข้าถึงเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการอัปเดตสิทธิ์: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            // Check if permission is being used by any roles
            if ($permission->roles()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'ไม่สามารถลบสิทธิ์นี้ได้ เนื่องจากถูกใช้งานโดยบทบาทอื่น');
            }

            DB::beginTransaction();

            $permission->delete();

            DB::commit();

            return redirect()->route('permissions.index')
                ->with('success', 'ลบสิทธิ์การเข้าถึงเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการลบสิทธิ์: ' . $e->getMessage());
        }
    }

    /**
     * Toggle permission status
     */
    public function toggleStatus(Permission $permission): RedirectResponse
    {
        try {
            $permission->update([
                'is_active' => !$permission->is_active
            ]);

            $status = $permission->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
            
            return redirect()->back()
                ->with('success', "{$status}สิทธิ์เรียบร้อยแล้ว");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:core_permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'กรุณาเลือกสิทธิ์และเลือกการดำเนินการ');
        }

        try {
            DB::beginTransaction();

            $permissions = Permission::whereIn('id', $request->permissions);

            switch ($request->action) {
                case 'activate':
                    $permissions->update(['is_active' => true]);
                    $message = 'เปิดใช้งานสิทธิ์ที่เลือกเรียบร้อยแล้ว';
                    break;
                
                case 'deactivate':
                    $permissions->update(['is_active' => false]);
                    $message = 'ปิดใช้งานสิทธิ์ที่เลือกเรียบร้อยแล้ว';
                    break;
                
                case 'delete':
                    // Check if any permission is being used
                    $usedPermissions = $permissions->whereHas('roles')->count();
                    if ($usedPermissions > 0) {
                        return redirect()->back()
                            ->with('error', 'ไม่สามารถลบสิทธิ์ที่ถูกใช้งานได้');
                    }
                    
                    $permissions->delete();
                    $message = 'ลบสิทธิ์ที่เลือกเรียบร้อยแล้ว';
                    break;
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการดำเนินการ: ' . $e->getMessage());
        }
    }
}