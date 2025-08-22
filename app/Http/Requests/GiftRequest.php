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
           'translations' => 'required|array',
            'translations.*.locale' => 'required|string|in:' . implode(',', array_keys(config('languages.available'))),
            'translations.*.title' => 'required|string|max:255',
            'translations.*.location' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:1000',
            'price' => 'required|numeric',
        'cover' => 'nullable|image',
        'active' => 'required|in:0,1',
        'pay_later' => 'required|in:0,1',
        'city_id' => 'required|exists:cities,id',
        'sub_category_ids' => 'required|array',
        'sub_category_ids.*' => 'required|exists:sub_categories,id',
        ];
    }
}
