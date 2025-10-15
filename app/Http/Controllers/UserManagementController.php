<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\CacheService;
use App\Services\PaginationService;

class UserManagementController extends Controller
{
    /**
     * Display the user management page with tabs
     */
    public function index()
    {
        // Get data for all tabs
        $users = User::with(['roles'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $roles = Role::with(['permissions', 'users'])
            ->ordered()
            ->paginate(15);

        $permissions = Permission::with(['roles'])
            ->ordered()
            ->paginate(15);

        return view('admin.user-management.index', compact('users', 'roles', 'permissions'));
    }

    // Users API Methods (optimized with pagination)
    public function getUsers(Request $request)
    {
        $paginationSettings = PaginationService::getPaginationSettings($request, 15);
        
        $users = User::with(['roles'])
            ->orderBy('created_at', 'desc')
            ->paginate($paginationSettings['per_page'], ['*'], 'page', $paginationSettings['page']);

        return response()->json([
            'success' => true,
            'data' => PaginationService::formatApiResponse($users)
        ]);
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:laravel_users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,pending,suspended',
            'roles' => 'array',
            'roles.*' => 'exists:laravel_roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);

            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            return response()->json([
                'success' => true,
                'message' => 'เพิ่มผู้ใช้เรียบร้อยแล้ว',
                'data' => $user->load('roles')
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถเพิ่มผู้ใช้ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::storeUser'
            );
        }
    }

    public function getUser($id)
    {
        $user = User::with(['roles'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'roles' => $user->roles->pluck('id')->toArray(),
                'all_roles' => Role::active()->ordered()->get()
            ]
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:laravel_users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,pending,suspended',
            'roles' => 'array',
            'roles.*' => 'exists:laravel_roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
            ]);

            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตผู้ใช้เรียบร้อยแล้ว',
                'data' => $user->load('roles')
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตผู้ใช้ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateUser'
            );
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting current user
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถลบบัญชีของตัวเองได้'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบผู้ใช้เรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถลบผู้ใช้ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::deleteUser'
            );
        }
    }

    public function updateUserStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,pending,suspended',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::findOrFail($id);
            $user->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสถานะเรียบร้อยแล้ว',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตสถานะได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateUserStatus'
            );
        }
    }

    public function updateUserRoles(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'array',
            'roles.*' => 'exists:laravel_roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::findOrFail($id);
            $user->syncRoles($request->roles ?? []);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตบทบาทผู้ใช้เรียบร้อยแล้ว',
                'data' => $user->load('roles')
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตบทบาทได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateUserRoles'
            );
        }
    }

    public function exportUsers()
    {
        $users = User::with(['roles'])->get();
        
        $csvData = "ID,Name,Email,Phone,Status,Roles,Created At\n";
        
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $csvData .= "{$user->id},{$user->name},{$user->email},{$user->phone},{$user->status},{$roles},{$user->created_at}\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users_export.csv"');
    }

    // Roles API Methods
    public function getRoles(Request $request)
    {
        $roles = Role::with(['permissions', 'users'])
            ->ordered()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:laravel_roles,slug',
            'description' => 'nullable|string',
            'color' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'permissions' => 'array',
            'permissions.*' => 'exists:laravel_permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $role = Role::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'color' => $request->color,
                'is_active' => $request->is_active ?? true,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'เพิ่มบทบาทเรียบร้อยแล้ว',
                'data' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถเพิ่มบทบาทได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::storeRole'
            );
        }
    }

    public function getRole($id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'role' => $role,
                'permissions' => $role->permissions->pluck('id')->toArray(),
                'all_permissions' => Permission::active()->ordered()->get()
            ]
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:laravel_roles,slug,' . $id,
            'description' => 'nullable|string',
            'color' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'permissions' => 'array',
            'permissions.*' => 'exists:laravel_permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $role->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'color' => $request->color,
                'is_active' => $request->is_active ?? true,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตบทบาทเรียบร้อยแล้ว',
                'data' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตบทบาทได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateUserRoles'
            );
        }
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Prevent deleting super-admin role
            if ($role->slug === 'super-admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถลบบทบาท Super Admin ได้'
                ], 400);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบบทบาทเรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถลบบทบาทได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::deleteRole'
            );
        }
    }

    public function updateRoleStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $role = Role::findOrFail($id);
            $role->update(['is_active' => $request->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสถานะเรียบร้อยแล้ว',
                'data' => $role
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตสถานะได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateRoleStatus'
            );
        }
    }

    public function getRolePermissions($id)
    {
        $role = Role::with(['permissions'])->findOrFail($id);
        $permissions = Permission::active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'role' => $role,
                'permissions' => $role->permissions->pluck('id')->toArray(),
                'all_permissions' => $permissions
            ]
        ]);
    }

    public function updateRolePermissions(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:laravel_permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $role = Role::findOrFail($id);
            $role->syncPermissions($request->permissions ?? []);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสิทธิ์เรียบร้อยแล้ว',
                'data' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตสิทธิ์ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateRolePermissions'
            );
        }
    }

    // Permissions API Methods
    public function getPermissions(Request $request)
    {
        $permissions = Permission::with(['roles'])
            ->ordered()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    public function storePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:laravel_permissions,slug',
            'description' => 'nullable|string',
            'group' => 'required|string|max:255',
            'action' => 'nullable|string|max:255',
            'resource' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'group' => $request->group,
                'action' => $request->action,
                'resource' => $request->resource,
                'is_active' => $request->is_active ?? true,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'เพิ่มสิทธิ์เรียบร้อยแล้ว',
                'data' => $permission
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถเพิ่มสิทธิ์ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::storePermission'
            );
        }
    }

    public function getPermission($id)
    {
        $permission = Permission::with(['roles'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:laravel_permissions,slug,' . $id,
            'description' => 'nullable|string',
            'group' => 'required|string|max:255',
            'action' => 'nullable|string|max:255',
            'resource' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $permission->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'group' => $request->group,
                'action' => $request->action,
                'resource' => $request->resource,
                'is_active' => $request->is_active ?? true,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสิทธิ์เรียบร้อยแล้ว',
                'data' => $permission
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตสิทธิ์ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateRolePermissions'
            );
        }
    }

    public function deletePermission($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            // Prevent deleting super-admin permission
            if ($permission->slug === 'super-admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถลบสิทธิ์ Super Admin ได้'
                ], 400);
            }

            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบสิทธิ์เรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถลบสิทธิ์ได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::deletePermission'
            );
        }
    }

    public function updatePermissionStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $permission = Permission::findOrFail($id);
            $permission->update(['is_active' => $request->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสถานะเรียบร้อยแล้ว',
                'data' => $permission
            ]);

        } catch (\Exception $e) {
            return getSafeApiErrorResponse(
                $e,
                'ไม่สามารถอัปเดตสถานะได้ กรุณาลองใหม่อีกครั้ง',
                'UserManagementController::updateRoleStatus'
            );
        }
    }

    public function getPermissionGroups()
    {
        $groups = Permission::select('group')
            ->distinct()
            ->whereNotNull('group')
            ->orderBy('group')
            ->pluck('group');

        return response()->json([
            'success' => true,
            'data' => $groups
        ]);
    }
}
