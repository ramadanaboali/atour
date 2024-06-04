<?php

namespace App\Http\Requests\Vendor;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'first_name' => 'required|string|min:2',
                    'last_name' => 'required|string|min:2',
                    'birthdate' => 'required|date',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|numeric|unique:users,phone',
                    'country_id' => 'required|exists:countries,id',
                    'region_id' => 'required|exists:regions,id',
                    'city_id' => 'required|exists:cities,id',
                    'image' => 'nullable|image|mimes:png,jpg,jpeg',
                    'branch_id' => 'required|array',
                    'branch_id.*' => 'required|exists:branches,id',
                    'role_id' => 'required|exists:roles,id',
                    'password' => 'required|confirmed',
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                $rules= [
                    'phone'=>'unique:users,phone,'.request()->user->id,
                    'email'=>'unique:users,email,'.request()->user->id,
                ];
                return $rules;
            }
            default:
                return [];
        }

    }
     protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
