<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = array(
            'email' => 'required|email',
            'password' => 'required|string'
        );
        $validatedData = Validator::make($request->all(), $validate);
        if ($validatedData->fails()) {
            return apiResponse(false, null, __('api.not_found'), $validatedData->errors()->all(), 201);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return apiResponse(false, null, __('api.not_authorized'), null, 401);
        }
        $user = Auth::user();
//        if(in_array($user->type, [User::TYPE_CLIENT])) {
            $user['token'] = $user->createToken('auth_token')->plainTextToken;

            return apiResponse(true, new UserResource($user), __('success'), null, 200);
//        }

        return apiResponse(false, null, __('api.not_authorized'), null, 401);

    }
    public function updateProfile(Request $request)
    {

        $currentUser = User::findOrFail(auth()->user()->id);

        $data = $currentUser->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
        if ($data) {
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } else {
            return apiResponse(false, null, __('api.cant_update'), null, 401);
        }
    }

    public function updateimage(Request $request)
    {

        $currentUser = User::findOrFail(auth()->user()->id);
        $image = $request->file('image');
        if($image) {
            $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
            $request->image->move(public_path('storage/users'), $fileName);
            $currentUser->image = $fileName;
        }

        if ($currentUser->save()) {
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } else {
            return apiResponse(false, null, __('api.cant_update'), null, 401);
        }
    }

    public function register(Request $request)
    {

        $request['phone'] = convertArabicNumbers($request->phone);
        $validate = array(
            'name' => 'required',
            'phone' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        );
        $validatedData = Validator::make($request->all(), $validate);
        if ($validatedData->fails()) {
            return apiResponse(false, null, __('api.validation_error'), $validatedData->errors()->all(), 201);
        }

        $user = new User();
        $user->name      = $request->name;
        $user->password  = Hash::make($request->password);
        $user->type      = User::TYPE_CLIENT;
        $user->phone     = $request->phone;


        if ($user->save()) {

            $user['token'] = $user->createToken('auth_token')->plainTextToken;
            return apiResponse(true, new UserResource($user), __('success'), null, 200);

        }
    }
    public function resetPassword(Request $request)
    {
        $validate = array(
            'phone' => 'required|string',
        );
        $validatedData = Validator::make($request->all(), $validate);
        if ($validatedData->fails()) {
            return apiResponse(false, null, __('api.validation_error'), $validatedData->errors()->all(), 201);
        }
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return apiResponse(false, null, __('api.not_found'), null, 404);
        }

        $MsgID = rand(1000, 9999);
        $msg = "reset code .".$MsgID;

        $user->update(['reset_code' => $MsgID]);

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
        $headers .= 'From: name' . "\r\n";
        mail($user->email, 'Check Code', $msg, $headers);
        return apiResponse(true, [$MsgID], __('api.reset_link_will_send'), null, 200);
    }
    public function checkCode(Request $request)
    {
        $validate = array(
            'phone' => 'required',
            'code' => 'required',
        );
        $validatedData = Validator::make($request->all(), $validate);
        if ($validatedData->fails()) {
            return apiResponse(false, null, __('api.validation_error'), $validatedData->errors()->all(), 201);
        }
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return apiResponse(false, null, __('api.not_found'), null, 404);
        }
        if($user->reset_code == $request->code) {
            $user->verifaid = 1;
            $user->save();
            return apiResponse(true, null, __('api.code_success'), null, 200);
        }
        return apiResponse(false, null, __('api.code_error'), null, 201);
    }
    public function confirmReset(Request $request)
    {
        $validate = array(
            'phone' => 'required',
            'password' => 'required|min:6|confirmed',
        );
        $validatedData = Validator::make($request->all(), $validate);
        if ($validatedData->fails()) {
            return apiResponse(false, null, __('api.validation_error'), $validatedData->errors()->all(), 201);
        }
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return apiResponse(false, null, __('api.not_found'), null, 404);
        }
        $user->update(['password' => Hash::make($request->password),'reset_code' => null]);
        return apiResponse(true, null, __('api.update_success'), null, 200);
    }
    public function logout(Request $request)
    {
        try {
            return 555;
        } catch (\Exception $e) {
        }
    }

    public function deleteAccount()
    {
        $user = User::find(auth()->id());
        if ($user->delete()) {
            $user = request()->user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

            return apiResponse(true, null, null, null, 200);
        }
        return apiResponse(false, null, null, null, 400);
    }
    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->fcm_token]);
            return response()->json([
                'success' => true
            ]);
        } catch(\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }
}
