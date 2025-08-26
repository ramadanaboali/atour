<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Services\TwoFactorAuthService;
use App\Services\ActivityLogService;
use Illuminate\View\View;
use Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $twoFactorService;
    protected $activityLogService;

    public function __construct(TwoFactorAuthService $twoFactorService, ActivityLogService $activityLogService)
    {
        $this->twoFactorService = $twoFactorService;
        $this->activityLogService = $activityLogService;
    }
    public function login(): View | RedirectResponse
    {
        //Auth::shouldUse('admin');
        if (auth()->check()) {
            return to_route('admin.home');
        }
        return view('admin.pages.auth.login');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;
        $remember = $request->has('remember');
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        $user = User::where('email', $email)->first();

        // Log failed attempt if user doesn't exist
        if (!$user) {
            LoginAttempt::logAttempt($email, $ipAddress, $userAgent, false, 'User not found');
            return back()->withInput($request->only('email', 'remember'))
                        ->withErrors(['email' => __('auth.failed')]);
        }

        // Check if account is locked
        if ($user->isAccountLocked()) {
            LoginAttempt::logAttempt($email, $ipAddress, $userAgent, false, 'Account locked');
            return back()->withInput($request->only('email', 'remember'))
                        ->withErrors(['email' => __('security.account_locked')]);
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            $user->incrementFailedAttempts();
            LoginAttempt::logAttempt($email, $ipAddress, $userAgent, false, 'Invalid password', $user->id);
            return back()->withInput($request->only('email', 'remember'))
                        ->withErrors(['email' => __('auth.failed')]);
        }

        // Check if 2FA is enabled
        if ($this->twoFactorService->requiresTwoFactor($user)) {
            // Send 2FA code
            if ($this->twoFactorService->sendTwoFactorCode($user)) {
                session(['2fa_user_id' => $user->id, '2fa_remember' => $remember]);
                return redirect()->route('admin.2fa.verify');
            } else {
                return back()->withErrors(['email' => __('security.2fa_code_send_failed')]);
            }
        }

        // Successful login
        $user->resetFailedAttempts();
        $user->update(['last_login' => now()]);
        LoginAttempt::logAttempt($email, $ipAddress, $userAgent, true, null, $user->id);
        
        Auth::login($user, $remember);
        $this->activityLogService->logLogin($user->id);
        
        return to_route('admin.home');
    }


    public function logout(): RedirectResponse
    {
        if (Auth::check()) {
            $this->activityLogService->logLogout(Auth::id());
        }
        
        Auth::logout();
        Session::flush();
        
        return to_route('admin.login');
    }

    public function show2FAForm(): View
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('admin.login');
        }
        
        return view('admin.pages.auth.2fa-verify');
    }

    public function verify2FA(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_user_id');
        $remember = session('2fa_remember', false);
        
        if (!$userId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($this->twoFactorService->verifyCode($user, $request->code)) {
            $user->update(['last_login' => now()]);
            
            Auth::login($user, $remember);
            $this->activityLogService->logLogin($user->id);
            
            session()->forget(['2fa_user_id', '2fa_remember']);
            
            return to_route('admin.home');
        }

        return back()->withErrors(['code' => __('security.2fa_invalid_code')]);
    }

    public function resend2FA(): RedirectResponse
    {
        $userId = session('2fa_user_id');
        
        if (!$userId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($this->twoFactorService->sendTwoFactorCode($user)) {
            return back()->with('success', __('security.2fa_code_sent'));
        }

        return back()->withErrors(['code' => __('security.2fa_code_send_failed')]);
    }
    public function resetPassword(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {

            flash(__('api.not_found'))->error();
            return back();
        }

        $MsgID = rand(1000, 9999);
        $msg = "reset code  ".$MsgID;

        $user->update(['reset_code' => $MsgID]);

        mail($user->email, "Travel Agency", $msg);

        // mail($user->email, 'Reset Password', $msg);

        return to_route('password.confirm');
    }
    public function resetConfirm(Request $request)
    {
        $token = $request->route()->parameter('token');
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }
    public function savePassword(Request $request)
    {

        $this->validate($request, [
               'code' => 'required',
               'password' => 'required|confirmed',
           ]);

        $user = User::where('reset_code', $request->code)->first();
        if(!$user) {
            flash('الكود غير صحيح')->error();
            return back();
        }
        $data = [
            'password' => Hash::make($request->password),
            'reset_code' => null
        ];
        $user->update($data);
        flash(__('api.update_success'))->success();

        return to_route('admin.login');

    }
}
