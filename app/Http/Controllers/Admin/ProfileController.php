<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $viewProfile = 'admin.pages.profile.index';
    private $viewChangePassword = 'admin.pages.profile.change_password';

    public function __construct()
    {
    }


    public function index(Request $request)
    {
        return view($this->viewProfile, get_defined_vars());
    }


    public function changePassword()
    {
        return view($this->viewChangePassword, get_defined_vars());
    }
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = User::findOrFail(auth()->user()->id);
        $data = [
            'password'=>Hash::make($request->password),
        ];
        $user->update($data);
        flash(__('api.update_success'))->success();
        Auth::logout();
        return to_route('admin.login');

    }
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|unique:users,phone,' . auth()->user()->id
        ]);

        $user = User::findOrFail(auth()->user()->id);
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
        ];
        $user->update($data);
        flash(__('api.update_success'))->success();

        return back();
    }


}
