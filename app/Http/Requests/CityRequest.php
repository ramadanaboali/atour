<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title_ar' => 'required',
            'title_en' => 'required',
            'country_id' => 'required',
        ];
    }
}
