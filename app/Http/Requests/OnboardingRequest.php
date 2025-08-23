<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnboardingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        if ($this->method() == 'PUT') {
            return [
                           'translations' => 'required|array',
            'translations.*.locale' => 'required|string|in:' . implode(',', array_keys(config('languages.available'))),
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:1000',
            ];
        } else {
            return [
                        'translations' => 'required|array',
            'translations.*.locale' => 'required|string|in:' . implode(',', array_keys(config('languages.available'))),
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:1000',
                'image' => 'required',
            ];
        }
    }
}
