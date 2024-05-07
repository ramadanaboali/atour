<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'email' => 'required',
            'password' => 'required',
        ]);

        $remember = $request->has('remember') ? true : false;

        $user = User::where('email', $request->email)
            ->first();

        if (!$user) {
            return back()->with('email', __('auth.failed'));
        }

        if (Hash::check($request->password, $user->password)) {
            Auth::login($user, $remember);
            return to_route('admin.home');
        }
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => __('auth.failed')]);
    }


    public function logout(): RedirectResponse
    {
        Auth::logout();
        return to_route('admin.login');
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
