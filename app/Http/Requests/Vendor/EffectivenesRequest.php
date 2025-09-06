<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class EffectivenesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


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
                        // Translation fields (single language based on header)
                        'title' => 'required|string|min:2',
                        'description' => 'required|string|min:2',
                        'is_group' => 'required|in:0,1',
                        'min_people' => 'required_if:is_group,0|integer|min:1',
                        'max_people' => 'required_if:is_group,0|integer|gte:min_people',
                        // Main model fields
                        'price' => 'required|numeric',
                        'from_date' => 'required|date',
                        'to_date' => 'required|date',
                        'from_time' => 'required',
                        'to_time' => 'required',
                        'lat' => 'required|numeric',
                        'long' => 'required|numeric',
                        'location' => 'nullable|string',
                        'city_id' => 'required|exists:cities,id',
                        'cover' => 'required|image',
                        'images' => 'required|array',
                        'images.*' => 'required|image',
                        'free_cancelation' => 'nullable|in:0,1',
                        'pay_later' => 'nullable|in:0,1',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        // Translation fields (single language based on header)
                        'title' => 'nullable|string|min:2',
                        'description' => 'nullable|string|min:2',
                        'is_group' => 'required|in:0,1',
                        'min_people' => 'required_if:is_group,0|integer|min:1',
                        'max_people' => 'required_if:is_group,0|integer|gte:min_people',
                        // Main model fields
                        'price' => 'nullable|numeric',
                        'from_date' => 'nullable|date',
                        'to_date' => 'nullable|date',
                        'from_time' => 'nullable',
                        'to_time' => 'nullable',
                        'lat' => 'nullable|numeric',
                        'long' => 'nullable|numeric',
                        'location' => 'nullable|string',
                        'city_id' => 'nullable|exists:cities,id',
                        'cover' => 'nullable|image',
                        'images' => 'nullable|array',
                        'images.*' => 'required|image',
                        'free_cancelation' => 'nullable|in:0,1',
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
