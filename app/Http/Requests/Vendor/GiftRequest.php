<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class GiftRequest extends FormRequest
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
                        'cover' => 'required|image',
                        'free_cancelation' => 'required|in:0,1',
                        'active' => 'required|in:0,1',
                        'pay_later' => 'required|in:0,1',
                        'city_id' => 'required|exists:cities,id',
                        'images' => 'required|array',
                        'images.*' => 'required|image',
                        'sub_category_ids' => 'required|array',
                        'sub_category_ids.*' => 'required|exists:sub_categories,id',
                        ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'title_en' => 'required|string|min:2',
                        'title_ar' => 'required|string|min:2',
                        'description_en' => 'required|string|min:2',
                        'description_ar' => 'required|string|min:2',
                        'price' => 'required|numeric',
                        'cover' => 'required|image',
                        'free_cancelation' => 'required|in:0,1',
                        'active' => 'required|in:0,1',
                        'pay_later' => 'required|in:0,1',
                        'city_id' => 'required|exists:cities,id',
                        'images' => 'required|array',
                        'images.*' => 'required|image',
                        'sub_category_ids' => 'required|array',
                        'sub_category_ids.*' => 'required|exists:sub_categories,id',
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
