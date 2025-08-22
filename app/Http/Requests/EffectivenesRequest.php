<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EffectivenesRequest extends FormRequest
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
            'translations.*.description' => 'required|string|max:1000',
            'price' => 'required|numeric',
            'from_date' => 'required',
            'to_date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ];
    }
}
