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
        // dd(request()->all());
        return [
           'translations' => 'required|array',
            'translations.*.locale' => 'required|string|in:' . implode(',', array_keys(config('languages.available'))),
            'translations.*.title' => 'required|string|max:255',
            'translations.*.start_point' => 'required|string|max:255',
            'translations.*.end_point' => 'required|string|max:255',
            'translations.*.program_time' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:1000',
            'price' => 'required|numeric',
            'min_people' => 'required|numeric',
            'max_people' => 'required|numeric|gte:min_people',
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
            'available_times' => 'required|array',
            'available_times.from_time' => 'required|array',
            'available_times.to_time' => 'required|array',
            'available_times.from_time.*' => 'required|date_format:H:i',
            'available_times.to_time.*' => 'required|date_format:H:i',
            'cover' => 'nullable|image',
            'images' => 'nullable|array',
            'images.*' => 'image',
            'available_days' => 'required|array',
            'available_days.*' => 'string',
        ];
    }
}
