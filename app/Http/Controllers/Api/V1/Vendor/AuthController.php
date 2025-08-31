<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ConfirmResetRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\VendorProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\PhoneRequest;
use App\Mail\SendCodeResetPassword;
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
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\NewEmailRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ActivationMail;
use App\Models\PendingRegistration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->findVendorByUsername($request->username);
            
            if (!$user) {
                return apiResponse(false, null, __('api.check_username_passowrd'), null, 422);
            }

            // Check if account is active
            if ($user->status === 'rejected') {
                return apiResponse(false, null, __('api.user_not_active'), null, 422);
            }

            // Attempt authentication
            $credentials = filter_var($request->username, FILTER_VALIDATE_EMAIL) 
                ? ['email' => $request->username, 'password' => $request->password]
                : ['phone' => $request->username, 'password' => $request->password];

            if (!Auth::attempt($credentials)) {
                return apiResponse(false, null, __('api.check_username_passowrd'), null, 422);
            }

            // Update last login
            $user->update(['last_login' => Carbon::now()]);
            
            $data = [
                'user' => auth()->user(),
                'access_token' => auth()->user()->createToken('auth_token')->plainTextToken
            ];
            
            return apiResponse(true, $data, null, null, 200);
            
        } catch (Exception $e) {
            Log::error('Vendor login error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 500);
        }
    }

    public function sendOtp(NewEmailRequest $request)
    {
        try {
            $otp = rand(100000, 999999);
            PendingRegistration::updateOrCreate(
                ['email' => $request->email],
                [
                        'otp_code' => $otp,
                        'expires_at' => now()->addMinutes(10),
                        'is_verified' => false,
                    ]
            );
            Mail::to($request->email)->send(new ActivationMail($otp));
            return apiResponse(true, [], __('api.verification_code'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, 400);
        }
    }
    public function verifyOtp(VerifyRequest $request)
    {
        try {
            $pending = PendingRegistration::where('email', $request->email)->first();
            if (!$pending || $pending->otp_code !== $request->code) {
                return apiResponse(false, null, __('api.invalid_otp'), null, 400);
            }
            if ($pending->expires_at < now()) {
                return apiResponse(false, null, __('api.otp_expired'), null, 400);
            }
            $pending->update(['is_verified' => true]);
            return apiResponse(true, null, __('api.verification_code_success'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, 400);
        }

    }
    /**
     * Generate unique vendor code
     */
    private function generateCode()
    {
        $baseCode = 2500001;
        $lastUser = User::where('type', User::TYPE_SUPPLIER)
            ->whereNotNull('code')
            ->orderBy('id', 'desc')
            ->first();
            
        return $lastUser && $lastUser->code ? intval($lastUser->code) + 1 : $baseCode;
    }
    
    /**
     * Generate 6-digit reset code
     */
    private function generateResetCode()
    {
        return rand(100000, 999999);
    }
    
    /**
     * Find vendor by username (email or phone)
     */
    private function findVendorByUsername($username)
    {
        return User::where('type', User::TYPE_SUPPLIER)
            ->where(function ($query) use ($username) {
                $query->where('email', $username)->orWhere('phone', $username);
            })
            ->first();
    }
    public function register(RegisterRequest $request)
    {
   
        $pending = PendingRegistration::where('email', $request->email)
               ->where('is_verified', true)
               ->first();
        if (!$pending) {
            return apiResponse(false, null, __('api.email_not_verified'), null, 400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'code' => $this->generateCode(),
            'status' => 'pendding',
            'active' => 0,
            'type' => User::TYPE_SUPPLIER
        ]);
        $pending->delete();
        return apiResponse(true, $user, __('api.register_success'), null, 200);
    }

    public function sendCode(SendCodeRequest $request)
    {
        try {
            $user = User::findOrFail(auth()->user()->id);
            $resetCode = $this->generateResetCode();
            $user->update(['reset_code' => $resetCode]);
            
            $email = $request->has('username') && filter_var($request->username, FILTER_VALIDATE_EMAIL) 
                ? $request->username 
                : $user->email;
                
            Mail::to($email)->send(new SendCodeResetPassword($email, $resetCode));
            
            // Don't expose the actual code in response
            return apiResponse(true, null, __('api.reset_password_code_send'), null, 200);
        } catch (Exception $e) {
            Log::error('Send code error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }
    public function resetPassword(ResetRequest $request)
    {
        try {
            $user = $this->findVendorByUsername($request->username);
            
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }

            $resetCode = $this->generateResetCode();
            $user->update(['reset_code' => $resetCode]);
            
            Mail::to($user->email)->send(new SendCodeResetPassword($user->email, $resetCode));
            
            // Don't expose the actual code in response
            return apiResponse(true, null, __('api.reset_password_code_send'), null, 200);
        } catch (Exception $e) {
            Log::error('Reset password error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }
    public function checkCode(CheckCodeRequest $request)
    {
        try {
            $user = $this->findVendorByUsername($request->username);
            
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            
            if ($user->reset_code && $user->reset_code == $request->code) {
                // Don't clear the code here, save it for password reset confirmation
                return apiResponse(true, null, __('api.code_success'), null, 200);
            }
            
            return apiResponse(false, null, __('api.code_error'), null, 422);
        } catch (Exception $e) {
            Log::error('Check code error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }

    public function confirmReset(ConfirmResetRequest $request)
    {
        try {
            $user = $this->findVendorByUsername($request->username);
            
            if (!$user) {
                return apiResponse(false, null, __('api.not_found'), null, 404);
            }
            
            // Verify the reset code if provided
            if ($request->has('code') && $user->reset_code !== $request->code) {
                return apiResponse(false, null, __('api.code_error'), null, 422);
            }
            
            $user->update([
                'password' => Hash::make($request->password),
                'reset_code' => null
            ]);
            
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } catch (Exception $e) {
            Log::error('Confirm reset error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return apiResponse(false, null, __('api.current_password_invalid'), null, 422);
            }
            $user->update(['password' => Hash::make($request->password)]);
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } catch (Exception $e) {
            return apiResponse(false, null, $e->getMessage(), null, 422);
        }
    }


    public function profile(Request $request)
    {
        $user = auth()->user();
        $user=$user->with('supplier')->first();
        return apiResponse(true, $user, null, null, 200);

    }

    public function updateProfile(VendorProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail(auth()->user()->id);
            $supplier = $user->supplier;
            
            // Prepare user table updates
            $userInputs = [];
            $userFields = [
                'name', 'phone', 'email', 'birthdate', 'address', 'nationality','city_id', 'country_id'
            ];
            
            foreach ($userFields as $field) {
                if ($request->filled($field)) {
                    $userInputs[$field] = $request->input($field);
                }
            }

            // Handle image upload for user
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/users'), $fileName);
                $userInputs['image'] = $fileName;
            }
            
            // Update user table
            if (!empty($userInputs)) {
                $userInputs['updated_by'] = auth()->id();
                $user->update($userInputs);
            }
            
            // Prepare supplier table updates
            $supplierInputs = [];
            $supplierFields = [
                'type', 'streat', 'postal_code',
                'description', 'short_description', 'url', 'job',
                'banck_name', 'banck_account','banck_iban', 'general_name', 'national_id','can_pay_later','can_cancel','pay_on_deliver','tax_number','rerequest_reason'
            ];
  
            if ($request->hasFile('licence_file')) {
                $licence_file = $request->file('licence_file');
                $fileName = time() . rand(0, 999999999) . '.' . $licence_file->getClientOriginalExtension();
                $licence_file->move(public_path('storage/users'), $fileName);
                $supplierInputs['licence_file'] = $fileName;
            }
            if ($request->hasFile('tax_file')) {
                $tax_file = $request->file('tax_file');
                $fileName = time() . rand(0, 999999999) . '.' . $tax_file->getClientOriginalExtension();
                $tax_file->move(public_path('storage/users'), $fileName);
                $supplierInputs['tax_file'] = $fileName;
            }
            if ($request->hasFile('commercial_register')) {
                $commercial_register = $request->file('commercial_register');
                $fileName = time() . rand(0, 999999999) . '.' . $commercial_register->getClientOriginalExtension();
                $commercial_register->move(public_path('storage/users'), $fileName);
                $supplierInputs['commercial_register'] = $fileName;
            }
            if ($request->hasFile('other_files')) {
                $other_files = $request->file('other_files');
                $fileName = time() . rand(0, 999999999) . '.' . $other_files->getClientOriginalExtension();
                $other_files->move(public_path('storage/users'), $fileName);
                $supplierInputs['other_files'] = $fileName;
            }
            if ($request->hasFile('national_id_file')) {
                $national_id_file = $request->file('national_id_file');
                $fileName = time() . rand(0, 999999999) . '.' . $national_id_file->getClientOriginalExtension();
                $national_id_file->move(public_path('storage/users'), $fileName);
                $supplierInputs['national_id_file'] = $fileName;
            }
            foreach ($supplierFields as $field) {
                if ($request->filled($field)) {
                    $supplierInputs[$field] = $request->input($field);
                }
            }        
         if (!empty($supplierInputs)) {
                $supplierInputs['updated_by'] = auth()->id();
                
                if ($supplier) {
                    $supplier->update($supplierInputs);
                } else {
                    $supplierInputs['user_id'] = $user->id;
                    $supplierInputs['created_by'] = auth()->id();
                    Supplier::create($supplierInputs);
                }
            }

            DB::commit();
            
            // Return updated user with supplier relationship
            $updatedUser = $user->fresh(['supplier']);
            
            return apiResponse(true, $updatedUser, __('api.update_success'), null, 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Vendor profile update error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }

    public function updateEmail(EmailRequest $request)
    {
        try {
            $user = auth()->user();
            
            // Fix: Correct logic for code validation
            if ($user->reset_code !== $request->code) {
                return apiResponse(false, null, __('api.code_error'), null, 422);
            }
            
            $user->update([
                'email' => $request->email,
                'reset_code' => null
            ]);
            
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } catch (Exception $e) {
            Log::error('Update email error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }
    public function updatePhone(PhoneRequest $request)
    {
        try {
            $user = auth()->user();
            
            // Fix: Correct logic for code validation
            if ($user->reset_code !== $request->code) {
                return apiResponse(false, null, __('api.code_error'), null, 422);
            }
            
            $user->update([
                'phone' => $request->phone,
                'reset_code' => null
            ]);
            
            return apiResponse(true, null, __('api.update_success'), null, 200);
        } catch (Exception $e) {
            Log::error('Update phone error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }

    public function logout(Request $request)
    {
        try {
            // For Sanctum tokens
            if (auth()->user()->currentAccessToken()) {
                auth()->user()->currentAccessToken()->delete();
            }
            
            // For Passport tokens (if still using)
            if (method_exists(auth()->user(), 'token')) {
                $accessToken = auth()->user()->token();
                if ($accessToken) {
                    DB::table('oauth_refresh_tokens')
                        ->where('access_token_id', $accessToken->id)
                        ->update(['revoked' => true]);
                    $accessToken->revoke();
                }
            }
            
            return apiResponse(true, null, __('api.logout_success'), null, 200);
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return apiResponse(false, null, __('api.validation_error'), null, 422);
        }
    }
}
