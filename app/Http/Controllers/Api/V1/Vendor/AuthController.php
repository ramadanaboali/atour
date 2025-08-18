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
use App\Http\Requests\Vendor\Setup6Request;
use App\Http\Requests\Vendor\Setup7Request;
use App\Http\Resources\UserResource;
use App\Mail\SendCodeResetPassword;
use App\Models\User;
use App\Models\Supplier;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\NewEmailRequest;
use App\Mail\ActivationMail;
use App\Models\SupplierService;
use Carbon\Carbon;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        $user = User::where('type', User::TYPE_SUPPLIER)->where(function ($query) use ($request) {
            $query->where('email', $request->username)->orWhere('phone', $request->username);
        })->first();

        if ($user) {
            if (!Auth::attempt(["email" => $request->username, "password" => $request->password])) {
                if (!Auth::attempt(["phone" => $request->username, "password" => $request->password])) {
                    return apiResponse(false, null, __('api.check_username_passowrd'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } else {
            return apiResponse(false, null, __('api.check_username_passowrd'), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($user->status == 'pendding') {
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
                'temperory_email' => $request->email,
                'reset_code' => $MsgID,
                'status' => 'pendding',
                'active' => false,
                'type' => User::TYPE_SUPPLIER,
            ];
            $user = User::updateOrCreate(['temperory_email' => $request->email], $data);
            // Mail::to($request->email)->send(new SendCodeResetPassword($request->email, $MsgID));

            Mail::to($request->email)->send(new ActivationMail($user->name, $MsgID));

            return apiResponse(true, [$MsgID], __('api.verification_code'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function verifyOtp(VerifyRequest $request)
    {

        try {
            $user = User::where('temperory_email', $request->email)->first();
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            if ($user->reset_code == $request->code) {
                $user->reset_code = null;
                $user->code = $this->generateCode();
                $user->password = Hash::make($request->code);
                $user->save();
                return apiResponse(true, $user, __('api.code_success'), null, 200);
            }
            return apiResponse(false, null, __('api.code_error'), null, 201);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }
    private function generateCode()
    {

        $code = 2500001;
        $user = User::where('type', User::TYPE_SUPPLIER)->whereNotNull('code')->orderby('id', 'desc')->first();
        if ($user && $user->code != null) {
            return intval($user->code) + 1;
        }
        return $code;
    }
    public function setup1(Setup1Request $request)
    {
        try {
            Log::info('setup1');

            DB::beginTransaction();
            $userInput = [
                'name' => $request->name,
                'temperory_phone' => $request->phone,
                'address' => $request->address,
                'national_id' => $request->national_id,
                'active' => false,
                'status' => 'pendding',
                'type' => User::TYPE_SUPPLIER,
            ];
            $user = User::with('supplier')->where('temperory_email', $request->email)->first();
            if ($request->has('cover')) {
                $fileNames = time() . rand(0, 999999999) . '.' . $request->file('cover')->getClientOriginalExtension();
                $request->file('cover')->move(public_path('storage/users'), $fileNames);
                $userInput['image'] = $fileNames;
            }
            $user->update($userInput);

            $supplier = Supplier::updateOrCreate(['user_id' => $user->id], ['nationality' => $request->nationality,'postal_code' => $request->postal_code]);

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
            Log::info('setup2');

            $inputs = [
                'user_id' => $request->user_id,
                'general_name' => $request->general_name,
                'description' => $request->description,
                'url' => $request->url,


            ];
            DB::beginTransaction();
            if ($request->has('profile')) {
                $fileNames = time() . rand(0, 999999999) . '.' . $request->file('profile')->getClientOriginalExtension();
                $request->file('profile')->move(public_path('storage/users'), $fileNames);
                $inputs['profile'] = $fileNames;
            }
            $supplier = Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();
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
            Log::info('setup3');

            $inputs = [
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'streat' => $request->streat,
            ];
            if ($request->has('licence_image')) {
                $fileNames = time() . rand(0, 999999999) . '.' . $request->file('licence_image')->getClientOriginalExtension();
                $request->file('licence_image')->move(public_path('storage/users'), $fileNames);
                $inputs['licence_image'] = $fileNames;
            }
            Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();

            if ($user) {
                $user_data = [
                    'banck_name' => $request->bank_name,
                    'banck_account' => $request->bank_account,
                    'bank_iban' => $request->bank_iban,
                    'tax_number' => $request->tax_number,
                    'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                ];
                $user->update($user_data);

            }

            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function setup4(Setup4Request $request)
    {
        try {
            Log::info('setup4');

            $inputs = [
                'type' => $request->type,
                'user_id' => $request->user_id
            ];
            Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();
            Log::info('user'.json_encode($user));
            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function setup5(Setup5Request $request)
    {
        try {
            Log::info('user_id'.json_encode($request->all()));
            Log::info('setup5');

            $supplier = Supplier::where('user_id', $request->user_id)->first();
            if ($supplier) {
                foreach ($request->sub_category_id as $sub_category_id) {
                    SupplierService::create([
                        'supplier_id' => $supplier->id,
                        'sub_category_id' => $sub_category_id
                    ]);
                }
            }

            $user = User::with('supplier')->where('id', $request->user_id)->first();

            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function setup6(Setup6Request $request)
    {
        try {
            Log::info('setup6');
            Log::info('user_id'.json_encode($request->all()));

            $inputs = [
                         'nationality' => $request->nationality,
                         'job' => $request->job,
                         'experience_info' => $request->experience_info,
                     ];

            Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->where('id', $request->user_id)->first();
            Log::info(json_encode($user));
            if ($user) {
                $userinputs = [
                             'name' => $request->name ?? $user->name,
                             'email' =>  $user->temperory_email,
                             'phone' =>  $user->temperory_phone,
                             'address' => $request->address ?? $user->address,
                             'city_id' => $request->city_id ?? $user->city_id,
                         ];

                $image = $request->file('image');
                if ($image) {
                    $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                    $request->image->move(public_path('storage/users'), $fileName);
                    $userinputs['image'] = $fileName;
                }

                $user->update($userinputs);
            }
            return apiResponse(true, $user, __('api.register_success'), null, Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return apiResponse(false, null, $e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function setup7(Setup7Request $request)
    {
        try {
            Log::info('setup7');

            $inputs = [
                'languages' => json_encode($request->languages),
            ];
            Supplier::updateOrCreate(['user_id' => $request->user_id], $inputs);
            $user = User::with('supplier')->find($request->user_id);
            if ($user) {
                $user->email = $user->temperory_email;
                $user->phone = $user->temperory_phone;
                $user->save();
            }
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
            if ($request->has('username')) {
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
    public function resetPassword(ResetRequest $request)
    {
        try {

            $user = User::where('type', User::TYPE_SUPPLIER)->where(function ($query) use ($request) {
                $query->where('email', $request->username)->orWhere('phone', $request->username);
            })->first();
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
            $user = User::where('type', User::TYPE_SUPPLIER)->where(function ($query) use ($request) {
                $query->where('email', $request->username)->orWhere('phone', $request->username);
            })->first();
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
            $user = User::where('type', User::TYPE_SUPPLIER)->where(function ($query) use ($request) {
                $query->where('email', $request->username)->orWhere('phone', $request->username);
            })->first();
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
            $inputs = [];

            if ($request->birthdate) {
                $inputs['birthdate'] = $request->birthdate;
            }

            if ($request->phone) {
                $inputs['phone'] = $request->phone;
            }


            if ($request->name) {
                $inputs['name'] = $request->name;
            }
            if ($request->nationality) {
                $inputs['nationality'] = $request->nationality;
            }

            if ($request->password) {
                $inputs['password'] = Hash::make($request->password);
            }

            $image = $request->file('image');
            if ($image) {
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $request->image->move(public_path('storage/users'), $fileName);
                $inputs['image'] = $fileName;
            }

            if (count($inputs) > 0) {
                $currentUser->update($inputs);
            }

            return apiResponse(true, null, __('api.update_success'), null, 200);

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
