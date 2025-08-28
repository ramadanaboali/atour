<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class VendorProfileRequest extends FormRequest
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
        $userId = auth()->id();
        
        return [
            // Users table fields
            'name' => 'nullable|string|min:2|max:255',
            'phone' => 'nullable|string|unique:users,phone,' . $userId,
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'birthdate' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'nationality' => 'nullable|string|max:100',
            'city_id' => 'nullable|exists:cities,id',
            'country_id' => 'nullable|exists:countries,id',
            'bank_account' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_iban' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            
            // Suppliers table fields
            'tour_guid' => 'nullable|boolean',
            'profission_guide' => 'nullable|boolean',
            'type' => ['nullable', Rule::in(['company', 'indivedual'])],
            'streat' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:5000',
            'short_description' => 'nullable|string|max:1000',
            'url' => 'nullable|url|max:255',
            'job' => 'nullable|string|max:255',
            'experience_info' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'banck_name' => 'nullable|string|max:100',
            'banck_number' => 'nullable|string|max:100',
            'place_summary' => 'nullable|string|max:500',
            'place_content' => 'nullable|string|max:1000',
            'expectations' => 'nullable|string|max:1000',
            'general_name' => 'nullable|string|max:255',
            'supplier_nationality' => 'nullable|string|max:100',
            'national_id' => 'nullable|string|max:50',
            'rerequest_reason' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.min' => __('api.name_min_length'),
            'phone.unique' => __('api.phone_exists'),
            'email.unique' => __('api.email_exists'),
            'email.email' => __('api.invalid_email'),
            'image.image' => __('api.invalid_image'),
            'image.mimes' => __('api.image_format'),
            'image.max' => __('api.image_size'),
            'birthdate.date' => __('api.invalid_date'),
            'birthdate.before' => __('api.birthdate_future'),
            'city_id.exists' => __('api.city_not_found'),
            'country_id.exists' => __('api.country_not_found'),
            'type.in' => __('api.invalid_supplier_type'),
            'url.url' => __('api.invalid_url'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, __('api.validation_error'), $errors, 422));
    }
}
