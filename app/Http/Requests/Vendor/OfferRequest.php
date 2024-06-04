<?php

namespace App\Http\Requests\Vendor;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class OfferRequest extends FormRequest
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
                    'name_ar'=>'required|string|min:2',
                    'name_en'=>'required|string|min:2',
                    'description_en'=>'required|string|min:2',
                    'description_ar'=>'required|string|min:2',
                    'section_id'=>'required|exists:sections,id',
                    'category_id'=>'required|exists:service_categories,id',
                    'sub_category_id'=>'required|exists:service_categories,id',
                    'service_price'=>'required|numeric',
                    'price_type'=>'required|in:service,free,special,discount',
                    'discount_percentage'=>'required_if:price_type,discount|numeric',
                    'offer_price'=>'required_if:price_type,special|numeric',
                    'service_id'=>'required|array',
                    'service_id.*'=>'required|exists:services,id'
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                $rules= [
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
