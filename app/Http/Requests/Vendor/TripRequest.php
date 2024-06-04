<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class TripRequest extends FormRequest
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
    public function rules(): array
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
                    'title_en' => 'required|string|min:2',
                    'title_ar' => 'required|string|min:2',
                    'description_en' => 'required|string|min:2',
                    'description_ar' => 'required|string|min:2',
                    'price' => 'required|numeric',
                    'phone' => 'required|numeric',
                    'start_point' => 'required',
                    'end_point' => 'required',
                    'cover' => 'required|image',
                    'free_cancelation' => 'required|in:0,1',
                    'cancelation_policy' => 'required_if:free_cancelation,1',
                    'start_point_descriprion_en' => 'required|string|min:2',
                    'end_point_descriprion_en' => 'required|string|min:2',
                    'start_point_descriprion_ar' => 'required|string|min:2',
                    'end_point_descriprion_ar' => 'required|string|min:2',
                    'active' => 'required|in:0,1',
                    'pay_later' => 'required|in:0,1',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {

                return [
                    'title_en' => 'nullable|string|min:2',
                    'title_ar' => 'nullable|string|min:2',
                    'description_en' => 'nullable|string|min:2',
                    'description_ar' => 'nullable|string|min:2',
                    'price' => 'nullable|numeric',
                    'phone' => 'nullable|numeric',
                    'start_point' => 'nullable',
                    'end_point' => 'nullable',
                    'cover' => 'nullable|image',
                    'free_cancelation' => 'nullable|in:0,1',
                    'start_point_descriprion_en' => 'nullable|string|min:2',
                    'end_point_descriprion_en' => 'nullable|string|min:2',
                    'start_point_descriprion_ar' => 'nullable|string|min:2',
                    'end_point_descriprion_ar' => 'nullable|string|min:2',
                    'active' => 'nullable|in:0,1',
                    'pay_later' => 'nullable|in:0,1',
                    ];

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
