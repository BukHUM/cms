<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function index()
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ไม่พบข้อมูลผู้ใช้');
        }

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ไม่พบข้อมูลผู้ใช้');
        }

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ไม่พบข้อมูลผู้ใช้');
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:laravel_users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'name.max' => 'ชื่อต้องไม่เกิน 255 ตัวอักษร',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้มีผู้ใช้แล้ว',
            'phone.max' => 'เบอร์โทรศัพท์ต้องไม่เกิน 20 ตัวอักษร',
            'address.max' => 'ที่อยู่ต้องไม่เกิน 500 ตัวอักษร',
            'bio.max' => 'ข้อมูลส่วนตัวต้องไม่เกิน 1000 ตัวอักษร',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update user data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'bio' => $request->bio,
                'updated_at' => now(),
            ]);

            // Update session data
            session([
                'admin_user_name' => $user->name,
                'admin_user_email' => $user->email,
            ]);

            // Log the activity
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'profile_update',
                'description' => 'อัปเดตข้อมูลส่วนตัว',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return redirect()->route('admin.profile.index')
                ->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for changing password
     */
    public function changePassword()
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ไม่พบข้อมูลผู้ใช้');
        }

        return view('admin.profile.change-password', compact('user'));
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ไม่พบข้อมูลผู้ใช้');
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'กรุณากรอกรหัสผ่านปัจจุบัน',
            'new_password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'new_password.min' => 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร',
            'new_password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'รหัสผ่านปัจจุบันไม่ถูกต้อง')
                ->withInput();
        }

        try {
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);

            // Log the activity
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'password_change',
                'description' => 'เปลี่ยนรหัสผ่าน',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return redirect()->route('admin.profile.index')
                ->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get user's activity log
     */
    public function activityLog(Request $request)
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'ไม่พบข้อมูลผู้ใช้');
        }

        // Get user's activity logs
        $activities = AuditLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.profile.activity-log', compact('user', 'activities'));
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(Request $request)
    {
        // Check if user is logged in
        if (!session('admin_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณาเข้าสู่ระบบก่อน'
            ], 401);
        }

        $userId = session('admin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลผู้ใช้'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'กรุณาเลือกรูปภาพ',
            'avatar.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'avatar.mimes' => 'รูปภาพต้องเป็นไฟล์ jpeg, png, jpg หรือ gif',
            'avatar.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Handle file upload
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('avatars', $filename, 'public');

                // Update user avatar
                $user->update([
                    'avatar' => $path,
                    'updated_at' => now(),
                ]);

                // Log the activity
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'avatar_update',
                    'description' => 'อัปเดตรูปโปรไฟล์',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'อัปเดตรูปโปรไฟล์เรียบร้อยแล้ว',
                    'avatar_url' => asset('storage/' . $path)
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตรูปภาพ: ' . $e->getMessage()
            ], 500);
        }
    }
}