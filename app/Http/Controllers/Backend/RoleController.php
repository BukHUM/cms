<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::with(['permissions', 'users']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        // Filter by system roles
        if ($request->filled('type')) {
            if ($request->type === 'system') {
                $query->system();
            } elseif ($request->type === 'custom') {
                $query->nonSystem();
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortBy, ['name', 'display_name', 'created_at', 'updated_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $roles = $query->paginate(15);

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json($roles);
        }

        // Return view for web requests
        return view('backend.users-roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::active()->orderBy('display_name')->get();
        
        return view('backend.users-roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:core_roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:core_permissions,id',
        ]);

        $role = Role::create($validated);

        // Sync permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role created successfully',
                'role' => $role->load('permissions')
            ], 201);
        }

        // Return redirect for web requests
        return redirect()->route('backend.roles.index')
                        ->with('success', 'บทบาทถูกสร้างเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Role $role)
    {
        $role->load(['permissions', 'users']);

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json($role);
        }

        // Return view for web requests
        return view('backend.users-roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::active()->orderBy('display_name')->get();
        $role->load('permissions');
        
        return view('backend.users-roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('core_roles', 'name')->ignore($role->id)
            ],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:core_permissions,id',
        ]);

        $role->update($validated);

        // Sync permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role updated successfully',
                'role' => $role->load('permissions')
            ]);
        }

        // Return redirect for web requests
        return redirect()->route('backend.roles.index')
                        ->with('success', 'บทบาทถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        // Check if role can be deleted
        if (!$role->canBeDeleted()) {
            $message = $role->is_system 
                ? 'ไม่สามารถลบบทบาทระบบได้' 
                : 'ไม่สามารถลบบทบาทที่มีผู้ใช้งานได้';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        $role->delete();

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Role deleted successfully']);
        }

        // Return redirect for web requests
        return redirect()->route('backend.roles.index')
                        ->with('success', 'บทบาทถูกลบเรียบร้อยแล้ว');
    }

    /**
     * Toggle role status
     */
    public function toggleStatus(Request $request, Role $role)
    {
        // Check if role is system role
        if ($role->is_system) {
            $message = 'ไม่สามารถเปลี่ยนสถานะบทบาทระบบได้';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        $role->update(['is_active' => !$role->is_active]);

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role status updated successfully',
                'role' => $role->load('permissions')
            ]);
        }

        // Return redirect for web requests
        return redirect()->back()->with('success', 'สถานะบทบาทถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Get roles for API
     */
    public function roles(Request $request)
    {
        $roles = Role::active()->orderBy('display_name')->get(['id', 'name', 'display_name']);

        return response()->json($roles);
    }
}