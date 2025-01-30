<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GiftRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title_en' => 'required|string|min:2',
            'title_ar' => 'required|string|min:2',
            'description_en' => 'required|string|min:2',
            'description_ar' => 'required|string|min:2',
            'price' => 'required|numeric',
            'cover' => 'nullable|image',
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
}
