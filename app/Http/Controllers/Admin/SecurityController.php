<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\UserActivityLog;
use App\Models\User;
use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SecurityController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorAuthService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function auditTrail(Request $request): View
    {
        $users = User::select('id', 'name', 'email')->get();
        $actionTypes = UserActivityLog::distinct()->pluck('action_type');
        
        return view('admin.pages.security.audit-trail', compact('users', 'actionTypes'));
    }

    public function auditTrailData(Request $request)
    {
        $query = UserActivityLog::with('user:id,name,email')
            ->select('user_activity_logs.*');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($item) {
                return $item->user ? $item->user->name : __('security.na');
            })
            ->addColumn('user_email', function ($item) {
                return $item->user ? $item->user->email : __('security.na');
            })
            ->addColumn('model_info', function ($item) {
                if ($item->model_type && $item->model_id) {
                    return class_basename($item->model_type) . ' #' . $item->model_id;
                }
                return __('security.na');
            })
            ->addColumn('changes', function ($item) {
                $changes = [];
                if ($item->old_values && $item->new_values) {
                    $old = $item->old_values;
                    $new = $item->new_values;
                    
                    foreach ($new as $key => $value) {
                        if (isset($old[$key]) && $old[$key] != $value) {
                            $changes[] = "<strong>{$key}:</strong> {$old[$key]} → {$value}";
                        }
                    }
                }
                return implode('<br>', $changes) ?: __('security.na');
            })
            ->addColumn('formatted_date', function ($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['changes'])

            ->make(true);
    }

    public function loginAttempts(Request $request): View
    {
        return view('admin.pages.security.login-attempts');
    }

    public function loginAttemptsData(Request $request)
    {
        $query = LoginAttempt::with('user:id,name,email')
            ->select('login_attempts.*');

        // Apply filters
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('successful')) {
            $query->where('successful', $request->successful === 'true');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attempted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attempted_at', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($item) {
                return $item->user ? $item->user->name : __('security.na');
            })
            ->addColumn('status', function ($item) {
                if ($item->successful) {
                    return '<span class="badge badge-success">' . __('security.success') . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . __('security.failed') . '</span>';
                }
            })
            ->addColumn('failure_info', function ($item) {
                return $item->failure_reason ?: __('security.na');
            })
            ->addColumn('formatted_date', function ($item) {
                return $item->attempted_at->format('Y-m-d H:i:s');
            })
            ->addColumn('location_info', function ($item) {
                $info = [];
                if ($item->ip_address) $info[] = "IP: {$item->ip_address}";
                if ($item->device_type) $info[] = "Device: {$item->device_type}";
                if ($item->location) $info[] = "Location: {$item->location}";
                return implode('<br>', $info);
            })
            ->rawColumns(['status', 'location_info'])
            ->make(true);
    }

    public function twoFactorSettings(): View
    {
        $user = auth()->user();
        return view('admin.pages.security.2fa-settings', compact('user'));
    }

    public function enable2FA(Request $request)
    {
        $user = auth()->user();
        
        if ($this->twoFactorService->sendTwoFactorCode($user)) {
            session(['2fa_setup_user_id' => $user->id]);
            return redirect()->route('admin.security.2fa.verify-setup')
                           ->with('success', __('security.2fa_code_sent'));
        }

        return back()->withErrors(['error' => __('security.2fa_code_send_failed')]);
    }

    public function show2FASetupForm()
    {
        // if (!session('2fa_setup_user_id')) {
        //     return redirect()->route('admin.security.2fa.settings');
        // }
        
        return view('admin.pages.security.2fa-setup');
    }

    public function verify2FASetup(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_setup_user_id');
        if (!$userId) {
            return redirect()->route('admin.security.2fa.settings');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.security.2fa.settings');
        }

        if ($this->twoFactorService->verifyCode($user, $request->code)) {
            $this->twoFactorService->enableTwoFactor($user);
            session()->forget('2fa_setup_user_id');
            
            return redirect()->route('admin.security.2fa.settings')
                           ->with('success', __('security.2fa_enabled_success'));
        }

        return back()->withErrors(['code' => __('security.2fa_invalid_code')]);
    }

    public function disable2FA(Request $request)
    {
        $user = auth()->user();
        $this->twoFactorService->disableTwoFactor($user);
        
        return redirect()->route('admin.security.2fa.settings')
                       ->with('success', __('security.2fa_disabled_success'));
    }

    public function securityDashboard(): View
    {
        $stats = [
            'total_login_attempts' => LoginAttempt::count(),
            'failed_login_attempts' => LoginAttempt::where('successful', false)->count(),
            'successful_login_attempts' => LoginAttempt::where('successful', true)->count(),
            'total_activities' => UserActivityLog::count(),
            'users_with_2fa' => User::where('two_factor_enabled', true)->count(),
            'locked_accounts' => User::where('locked_until', '>', now())->count(),
        ];

        // Recent failed login attempts
        $recentFailedAttempts = LoginAttempt::where('successful', false)
            ->with('user:id,name,email')
            ->orderBy('attempted_at', 'desc')
            ->limit(10)
            ->get();

        // Recent activities
        $recentActivities = UserActivityLog::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Login attempts by day (last 7 days)
        $loginAttemptsByDay = LoginAttempt::selectRaw('DATE(attempted_at) as date, COUNT(*) as total, SUM(successful) as successful')
            ->where('attempted_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.pages.security.dashboard', compact(
            'stats', 
            'recentFailedAttempts', 
            'recentActivities', 
            'loginAttemptsByDay'
        ));
    }
}
