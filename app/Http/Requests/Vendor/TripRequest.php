<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
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
            // Translation fields (single language based on header)
            'title' => 'required|string|min:2',
            'description' => 'required|string|min:2',
            'start_point' => 'nullable|string|min:2',
            'end_point' => 'nullable|string|min:2',
            'program_time' => 'nullable|string|min:2',
            'steps_list' => 'nullable|array',
            'steps_list.*' => 'nullable|string',
            
            // Main model fields
            'price' => 'required|numeric',
            'cover' => 'required|image',
            'free_cancelation' => 'required|in:0,1',
            'active' => 'nullable|in:0,1',
            'pay_later' => 'required|in:0,1',
            'city_id' => 'required|exists:cities,id',
            'is_group' => 'required|in:0,1',
            'min_people' => 'required_if:is_group,0|integer|min:1',
            'max_people' => 'required_if:is_group,0|integer|gte:min_people',
            'start_long' => 'nullable|numeric',
            'start_lat' => 'nullable|numeric',
            'end_long' => 'nullable|numeric',
            'end_lat' => 'nullable|numeric',
            
            // Images and attachments
            'images' => 'required|array',
            'images.*' => 'required|image',
            
            // Availability
            'available_days' => 'required|array',
            'available_days.*' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'available_times' => 'required|array',
            'available_times.*.from_time' => 'required',
            'available_times.*.to_time' => 'required',
            
            // Relations
            'featur_ids' => 'nullable|array',
            'featur_ids.*' => 'required|exists:features,id',
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => 'required|exists:sub_categories,id',
            'requirement_ids' => 'nullable|array',
            'requirement_ids.*' => 'required|'. Rule::exists('requirements', 'id')->whereNull('deleted_at'),
        ];
    }
   
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
