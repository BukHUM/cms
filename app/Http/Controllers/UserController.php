<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(15);

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json($users);
        }

        // Return view for web requests
        return view('backend.users-allusers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        return view('backend.users-allusers.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:core_users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:core_roles,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user->load('roles')
            ], 201);
        }

        return redirect()->route('backend.users.index')->with('success', 'ผู้ใช้งานถูกสร้างเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        
        if (request()->expectsJson()) {
            return response()->json($user);
        }
        
        return view('backend.users-allusers.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $user->load('roles');
        return view('backend.users-allusers.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:core_users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:core_roles,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? $user->is_active,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user->load('roles')
            ]);
        }

        return redirect()->route('backend.users.index')->with('success', 'ผู้ใช้งานถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Cannot delete your own account'], 422);
            }
            return redirect()->back()->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'User deleted successfully']);
        }

        return redirect()->route('backend.users.index')->with('success', 'ผู้ใช้งานถูกลบเรียบร้อยแล้ว');
    }

    /**
     * Get all roles for user assignment
     */
    public function roles()
    {
        $roles = Role::where('is_active', true)->get();
        return response()->json($roles);
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Cannot change your own status'], 422);
            }
            return redirect()->back()->with('error', 'ไม่สามารถเปลี่ยนสถานะของตัวเองได้');
        }

        $user->update(['is_active' => !$user->is_active]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User status updated successfully',
                'user' => $user->load('roles')
            ]);
        }

        return redirect()->back()->with('success', 'สถานะผู้ใช้งานถูกอัปเดตเรียบร้อยแล้ว');
    }
}
