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

        $user = User::where('email', $request->email)->where('type',User::TYPE_ADMIN)
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
}
