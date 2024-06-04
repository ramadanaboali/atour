<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ServiceRequest extends FormRequest
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
                        'name_en' => 'required|string|min:2',
                        'name_ar' => 'required|string|min:2',
                        'description_en' => 'required|string|min:2',
                        'description_ar' => 'required|string|min:2',
                        'section_id' => 'required|exists:sections,id',
                        'category_id' => 'required|exists:service_categories,id',
                        'sub_category_id' => 'required|exists:service_categories,id',
                        'price' => 'required|numeric',
                        'service_time' => 'required|numeric',
                        'extra_time' => 'nullable|numeric',
                        'featured' => 'required|in:0,1',
                        'branch_id' => 'required|array',
                        'branch_id.*' => 'required|exists:branches,id',
                        // 'employee_id' => 'required|array',
                        // 'employee_id.*' => 'required|exists:employees,id',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    $rules = [
                        'discount_type' =>'nullable|in:0,1',
                        'discount' =>'nullable|numeric'
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
