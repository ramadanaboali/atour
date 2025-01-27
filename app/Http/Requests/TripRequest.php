<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title_en' => 'required|string|min:3',
            'title_ar' => 'required|string|min:3',
            'start_point_en' => 'required|string',
            'start_point_ar' => 'required|string',
            'end_point_en' => 'required|string',
            'end_point_ar' => 'required|string',
            'program_time_en' => 'required|string',
            'program_time_ar' => 'required|string',
            'price' => 'required|numeric',
            'people' => 'required|numeric',
            'start_long' => 'required|numeric',
            'start_lat' => 'required|numeric',
            'end_long' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'city_id' => 'required|exists:cities,id',
            'requirement_ids' => 'required|array',
            'requirement_ids.*' => 'exists:requirements,id',
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => 'exists:sub_categories,id',
            'featur_ids' => 'required|array',
            'featur_ids.*' => 'exists:features,id',
            'free_cancelation' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'pay_later' => 'nullable|boolean',
            'steps_list' => 'required|array',
            'steps_list.*' => 'string',
            'available_times' => 'required|array',
            'available_times.from_time' => 'required|array',
            'available_times.to_time' => 'required|array',
            'available_times.from_time.*' => 'required|date_format:H:i',
            'available_times.to_time.*' => 'required|date_format:H:i',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'cover' => 'nullable|image',
            'images' => 'nullable|array',
            'images.*' => 'image',
            'available_days' => 'required|array',
            'available_days.*' => 'string',
        ];
    }
}
