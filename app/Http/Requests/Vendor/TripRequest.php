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
            'available_days' => 'required|array',
            'available_days.*' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'featur_ids' => 'required|array',
            'featur_ids.*' => 'required|exists:features,id',
            'available_times' => 'required|array',
            'available_times.*.from_time' => 'required',
            'available_times.*.to_time' => 'required',
            'steps_list' => 'nullable|array',
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => 'required|exists:sub_categories,id',
            'requirement_ids' => 'required|array',
            'requirement_ids.*' => 'required|exists:requirements,id',
        ];


    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
