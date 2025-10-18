<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Services\AuditLogService;
use App\Services\SecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $auditLogService;
    protected $securityService;

    public function __construct(AuditLogService $auditLogService, SecurityService $securityService)
    {
        $this->auditLogService = $auditLogService;
        $this->securityService = $securityService;
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // ถ้าผู้ใช้ login อยู่แล้ว ให้ redirect ไป dashboard
        if (Auth::check()) {
            return redirect()->route('backend.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Rate limiting
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "คุณพยายามเข้าสู่ระบบมากเกินไป กรุณารอ {$seconds} วินาที",
            ]);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'remember' => 'nullable|boolean',
        ], [
            'email.required' => 'กรุณาใส่อีเมล์',
            'email.email' => 'รูปแบบอีเมล์ไม่ถูกต้อง',
            'password.required' => 'กรุณาใส่รหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
            'remember.boolean' => 'ฟิลด์จดจำการเข้าสู่ระบบต้องเป็น true หรือ false',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($key);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email', 'remember'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // ตรวจสอบว่าผู้ใช้มีอยู่หรือไม่
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            RateLimiter::hit($key);
            $this->recordLoginAttempt($request, $credentials['email'], false, 'User not found');
            
            return redirect()->back()
                ->withErrors(['email' => 'อีเมล์หรือรหัสผ่านไม่ถูกต้อง'])
                ->withInput($request->only('email', 'remember'));
        }

        // ตรวจสอบสถานะผู้ใช้
        if (!$user->is_active) {
            RateLimiter::hit($key);
            $this->recordLoginAttempt($request, $credentials['email'], false, 'Account inactive');
            
            return redirect()->back()
                ->withErrors(['email' => 'บัญชีของคุณถูกปิดใช้งาน กรุณาติดต่อผู้ดูแลระบบ'])
                ->withInput($request->only('email', 'remember'));
        }

        // ตรวจสอบการ login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            // อัพเดตข้อมูลการ login
            $user->update([
                'last_login_at' => Carbon::now(),
                'last_login_ip' => $request->ip(),
            ]);

            // บันทึก audit log โดยใช้ user model เป็น auditable
            $this->auditLogService->log(
                'user_login', 
                $user, 
                null, 
                null, 
                $user, 
                $request, 
                'authentication'
            );

            // บันทึก login attempt ที่สำเร็จ
            $this->recordLoginAttempt($request, $credentials['email'], true, 'Login successful');

            // ตรวจสอบการตั้งค่าความปลอดภัย
            $this->securityService->checkSecuritySettings($user, $request);

            return redirect()->intended(route('backend.dashboard'))
                ->with('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ' . $user->name);
        }

        // Login ไม่สำเร็จ
        RateLimiter::hit($key);
        $this->recordLoginAttempt($request, $credentials['email'], false, 'Invalid password');

        return redirect()->back()
            ->withErrors(['email' => 'อีเมล์หรือรหัสผ่านไม่ถูกต้อง'])
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            // บันทึก audit log โดยใช้ user model เป็น auditable
            $this->auditLogService->log(
                'user_logout', 
                $user, 
                null, 
                null, 
                $user, 
                $request, 
                'authentication'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }

    /**
     * Record login attempt
     */
    private function recordLoginAttempt(Request $request, string $email, bool $success, string $reason = '')
    {
        try {
            LoginAttempt::create([
                'email' => $email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'success' => $success,
                'reason' => $reason,
                'attempted_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the login process
            \Log::error('Failed to record login attempt: ' . $e->getMessage());
        }
    }

    /**
     * Show password reset request form
     */
    public function showPasswordResetForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request
     */
    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:core_users,email',
        ], [
            'email.required' => 'กรุณาใส่อีเมล์',
            'email.email' => 'รูปแบบอีเมล์ไม่ถูกต้อง',
            'email.exists' => 'ไม่พบอีเมล์นี้ในระบบ',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // TODO: Implement password reset functionality
        // This would typically involve sending an email with reset link

        return redirect()->back()
            ->with('success', 'ลิงก์รีเซ็ตรหัสผ่านได้ถูกส่งไปยังอีเมล์ของคุณแล้ว');
    }

    /**
     * Check if user is authenticated
     */
    public function checkAuth()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }
}
