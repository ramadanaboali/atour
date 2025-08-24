<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
     ];

    }
}
