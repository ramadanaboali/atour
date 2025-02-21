<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'            => 'required|unique:users,phone',
            'name'       => 'required|min:3|max:20',
            'phone'            => 'required|numeric|unique:users,phone',
            'password'         => 'required|min:8|confirmed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'   => 'يجب ادخال الاسم  بشكل صحيح !!',
            'name.string'     => 'الاسم  يجب ان يكون حروف فقط !!',
            'name.max'        => 'الاسم  لا يمكن ان يكون اكبر من 20 حرف !! ',
            'name.min'        => 'الاسم  لا يمكن ان يكون اقل من 3 احرف !! ',

            'email.required'     => 'البريد الألكتروني مطلوب',
            'email.email'     => 'ادخل البريد الألكتروني بشكل صحيح',
            'email.unique'     => 'البريد الأليكتروني مسجل لدينا بالفعل',
            'email.max'     => 'البريد الأليكتروني يجب ان يكون اقل من 50 حرف',


            // 'country_id.required'     => 'رجاء اختيار الدولة',

            'password.required'     => 'كلمة المرور مطلوبة',
            'password.confirmed'     => 'كلمة المرور غير متطابقة',
            'password.min'     => 'كلمة المرور يجب ان تكون 8 احرف علي الاـقل',

            'phone.required'     => 'رقم الجوال مطلوب',
            'phone.unique'     => 'رقم الجوال مسجل لدينا بالفعل',
            'phone.numeric'     => 'رقم الجوال يجب ان يكون ارقام فقط',
            'phone.digits'     => 'رجاء ادخال رقم الجوال بالصيغة الدولية',

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'phone' => preg_replace('/\D/', '', $this->phone),
           ]);
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
