<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ConfirmResetRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\NewEmailRequest;
use App\Http\Resources\UserResource;
use App\Mail\SendCodeResetPassword;
use App\Models\User;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', User::TYPE_CLIENT)->first();

        if ($user) {
            if ($user->active == 0) {
                return apiResponse(false, null, __('api.user_not_active'), null, Response::HTTP_UNPROCESSABLE_ENTITY);

            }
            if (!Auth::attempt(["email" => $request->username, "password" => $request->password])) {
                if (!Auth::attempt(["phone" => $request->username, "password" => $request->password])) {
                    return apiResponse(false, null, __('api.check_username_passowrd'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } else {
            return apiResponse(false, null, __('api.check_username_passowrd'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->last_login = Carbon::now();
        $user->save();
        $dataR['user'] = auth()->user();
        $dataR['user_permissions'] = auth()->user()->getAllPermissions();
        $dataR['access_token'] = auth()->user()->createToken('auth_token')->plainTextToken;
        return $this->successResponse($dataR, Response::HTTP_CREATED);
    }

    public function register(RegisterRequest $request)
    {
        $userInput = [
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'type' => User::TYPE_CLIENT,
            'password' => Hash::make($request->password),
        ];
        $user = User::where('email', $request->email)->first();
        $user->update($userInput);

        return $this->successResponse($user, Response::HTTP_CREATED);

    }

    public function sendCode(SendCodeRequest $request)
    {
        try {
            $user = User::findOrFail(auth()->user()->id);
            $MsgID = rand(100000, 999999);
            $user->update(['reset_code' => $MsgID]);
            if ($request->filled('username')) {
                if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($request->username)->send(new SendCodeResetPassword($request->username, $MsgID));
                } else {
                    Mail::to($user->email)->send(new SendCodeResetPassword($user->email, $MsgID));
                }
            }
            return apiResponse(true, [$MsgID], __('api.reset_password_code_send'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    public function sendOtp(NewEmailRequest $request)
    {
        try {
            $MsgID = rand(100000, 999999);
            $data = [
                'email' => $request->email,
                'reset_code' => $MsgID,
                'status' => 'pendding',
                'active' => false,
                'type' => User::TYPE_CLIENT,
            ];
            $user = User::create($data);
            Mail::to($user->email)->send(new SendCodeResetPassword($user->email, $MsgID));
            return apiResponse(true, [$MsgID], __('api.verification_code'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function verifyOtp(VerifyRequest $request)
    {

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            if ($user->reset_code == $request->code) {
                $user->reset_code = null;
                $user->save();
                return apiResponse(true, $user, __('api.code_success'), null, 200);
            }
            return apiResponse(false, null, __('api.code_error'), null, 201);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    public function resetPassword(ResetRequest $request)
    {
        try {

            $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', User::TYPE_CLIENT)->first();
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }

            $MsgID = rand(100000, 999999);
            $user->update(['reset_code' => $MsgID]);
            Mail::to($user->email)->send(new SendCodeResetPassword($user->email, $MsgID));
            return apiResponse(true, [$MsgID], __('api.reset_password_code_send'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    public function checkCode(CheckCodeRequest $request)
    {
        try {
            $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', User::TYPE_CLIENT)->first();
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            if ($user->reset_code == $request->code) {
                $user->reset_code = null;
                $user->save();
                return apiResponse(true, null, __('api.code_success'), null, 200);
            }
            return apiResponse(false, null, __('api.code_error'), null, 201);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function confirmReset(ConfirmResetRequest $request)
    {
        try {
            $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', User::TYPE_CLIENT)->first();
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            $user->update(['password' => Hash::make($request->password),'reset_code' => null]);
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return apiResponse(false, null, __('api.current_password_invalid'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user->update(['password' => Hash::make($request->password)]);
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function profile(Request $request)
    {

        $user = auth()->user();
        // $data = new UserResource($user);
        return apiResponse(true, $user, null, null, 200);

    }

    public function updateProfile(ProfileRequest $request)
    {
        try {
            $currentUser = User::findOrFail(auth()->user()->id);
            $inputs = [
                'name' => $request->name ?? $currentUser->name,

            ];
            $image = $request->file('image');
            if ($image) {
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $request->image->move(public_path('storage/users'), $fileName);
                $inputs['image'] = $fileName;
            }
            $data = $currentUser->update($inputs);
            if ($data) {
                return apiResponse(true, null, __('api.update_success'), null, 200);
            } else {
                return apiResponse(false, null, __('api.cant_update'), null, 401);
            }
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function updateEmail(EmailRequest $request)
    {
        try {
            if (auth()->user()->reset_code != $request->code) {
                return apiResponse(true, null, __('api.code_success'), null, 200);
            }
            $currentUser = User::findOrFail(auth()->user()->id);
            $inputs = [
                'email' => $request->email,
                'reset_code' => null
            ];
            $data = $currentUser->update($inputs);
            if ($data) {
                return apiResponse(true, null, __('api.update_success'), null, 200);
            } else {
                return apiResponse(false, null, __('api.cant_update'), null, 401);
            }
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function updatePhone(PhoneRequest $request)
    {
        try {
            if (auth()->user()->reset_code != $request->code) {
                return apiResponse(true, null, __('api.code_success'), null, 200);
            }
            $currentUser = User::findOrFail(auth()->user()->id);
            $inputs = [
                'phone' => $request->phone,
                'reset_code' => null
            ];
            $data = $currentUser->update($inputs);
            if ($data) {
                return apiResponse(true, null, __('api.update_success'), null, 200);
            } else {
                return apiResponse(false, null, __('api.cant_update'), null, 401);
            }
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();
        $data['message'] = 'Logout successfully';
        return $this->successResponse($data, Response::HTTP_CREATED);
    }
}
