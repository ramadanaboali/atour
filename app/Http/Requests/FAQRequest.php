<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class FAQRequest extends FormRequest
{
  
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|in:' . implode(',', array_keys(config('languages.available'))),
            'translations.*.question' => 'required|string|max:255',
            'translations.*.answer' => 'required|string|max:255',
        ];
    }


}
