<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ConfirmResetRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\Vendor\Setup1Request;
use App\Http\Requests\Vendor\Setup2Request;
use App\Http\Requests\Vendor\Setup3Request;
use App\Http\Requests\Vendor\Setup4Request;
use App\Http\Requests\Vendor\Setup5Request;
use App\Http\Resources\UserResource;
use App\Mail\SendCodeResetPassword;
use App\Models\Attachment;
use App\Models\User;
use App\Models\Supplier;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Http\Requests\VerifyRequest;
use App\Http\Requests\NewEmailRequest;
use App\Models\SupplierService;
use Carbon\Carbon;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', User::TYPE_SUPPLIER)->first();

        if($user) {
            if (!Auth::attempt(["email" => $request->username, "password" => $request->password])) {
                if (!Auth::attempt(["phone" => $request->username, "password" => $request->password])) {
                    return apiResponse(false, null, __('api.check_username_passowrd'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } else {
            return apiResponse(false, null, __('api.check_username_passowrd'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if($user->active == 0) {
            return apiResponse(false, null, __('api.user_not_active'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->last_login = Carbon::now();
        $user->save();
        $dataR['user'] = auth()->user();
        $dataR['user_permissions'] = auth()->user()->getAllPermissions();
        $dataR['access_token'] = auth()->user()->createToken('auth_token')->plainTextToken;
        return $this->successResponse($dataR, Response::HTTP_CREATED);

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
                'type' =>User::TYPE_SUPPLIER,
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
            if($user->reset_code == $request->code) {
                $user->reset_code = null;
                $user->save();
                return apiResponse(true, $user, __('api.code_success'), null, 200);
            }
            return apiResponse(false, null, __('api.code_error'), null, 201);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    public function setup1(Setup1Request $request)
    {
        try {
            DB::beginTransaction();
            $userInput = [
                'email' => $request->email,
                'name' => $request->name,
                'general_name' => $request->general_name,
                'nationality' => $request->nationality,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
                'joining_date_from' => date('Y-m-d'),
                'active' => false,
                'status' => 'pendding',
                'type' => User::TYPE_SUPPLIER,
                'password' => Hash::make($request->password),
            ];
            $user = User::where('email',$request->email)->first();
            $user->update($userInput);
            $role = Role::firstOrCreate(['name' => 'supplier'], ['name' => 'supplier','model_type' => 'supplier','can_edit' => 0]);

            if ($user && $role) {
                $user->syncRoles($role->id);
                $role->syncPermissions(Permission::whereIn('model_type', ['supplier','general'])->get());
                Artisan::call('cache:clear');
            }
            DB::commit();
            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    public function setup2(Setup2Request $request)
    {
        try {
            $inputs = [
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'streat' => $request->streat,
                'postal_code' => $request->postal_code,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'url' => $request->url,
                'user_id' => $request->user_id,

            ];
            DB::beginTransaction();
            $supplier=Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();
            // foreach ($request->category as $category) {
            //     SupplierService::create([
            //             'supplier_id'=>$supplier->id,
            //             'category'=>$category,
            //     ]);
            // }
            // foreach ($request->sub_category_id as $sub_category_id) {
            //     SupplierService::create([
            //             'supplier_id'=>$supplier->id,
            //             'sub_category_id'=>$sub_category_id,
            //     ]);
            // }
            DB::commit();
            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    public function setup3(Setup3Request $request)
    {
        try {
            $inputs = [
                'profission_guide' => $request->profission_guide,
                'job' => $request->job,
                'type' => $request->type,
                'experience_info' => $request->experience_info,
                'languages' => json_encode($request->languages),
                'user_id' => $request->user_id,
                'banck_name' => $request->banck_name,
                'banck_number' => $request->banck_number,
            ];
            Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();
            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function setup4(Setup4Request $request)
    {
        try {
            $inputs = [
                'tax_number' => $request->tax_number,
                'place_summary' => $request->place_summary,
                'place_content' => $request->place_content,
                'expectations' => $request->expectations,
                'user_id' => $request->user_id
            ];
            Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();
            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function setup5(Setup5Request $request)
    {
        // dd($request->attachments[0]);
        try {
            foreach($request->attachments as $file) {
                $fileName = time() . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/files'), $fileName);
                $input = [
                    'model_id' => $request->user_id,
                    'attachment' => $fileName,
                    'title' => 'cirtified',
                    'model_type' => 'user',
                ];
                Attachment::create($input);
            }
            foreach($request->images as $image) {
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/files'), $fileName);
                $input = [
                    'model_id' => $request->user_id,
                    'attachment' => $fileName,
                    'title' => 'images',
                    'model_type' => 'user',
                ];
                Attachment::create($input);
            }
            $user = User::with(['supplier','attachments'])->findOrFail($request->user_id);

            $fileNames = time() . rand(0, 999999999) . '.' . $request->file('profile')->getClientOriginalExtension();
            $request->file('profile')->move(public_path('storage/users'), $fileNames);
            $user->image = $fileNames;
            $user->save();

            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function sendCode(SendCodeRequest $request)
    {
        try {
            $user = User::findOrFail(auth()->user()->id);
            $MsgID = rand(100000, 999999);
            $user->update(['reset_code' => $MsgID]);
            if($request->filled('username')) {
                if(filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
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
    public function resetPassword(ResetRequest $request)
    {
        try {

            $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', 'supplier')->first();
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
            $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', 'supplier')->first();
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            if($user->reset_code == $request->code) {
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
            $user = User::where('email', $request->username)->orWhere('phone', $request->username)->where('type', 'supplier')->first();
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
            if(!Hash::check($request->current_password, $user->password)) {
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
        $data = new UserResource($user);
        return apiResponse(true, $data, null, null, 200);

    }

    public function updateProfile(ProfileRequest $request)
    {
        try {
            $currentUser = User::findOrFail(auth()->user()->id);
            $inputs = [
                'name' => $request->name,
            ];
            $image = $request->file('image');
            if($image) {
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
            if(auth()->user()->reset_code != $request->code) {
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
            if(auth()->user()->reset_code != $request->code) {
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
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        $data['message'] = 'Logout successfully';
        return $this->successResponse($data, Response::HTTP_CREATED);
    }
}
