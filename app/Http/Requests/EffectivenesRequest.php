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
                  'description_en' => 'required|string|min:2',
                        'description_ar' => 'required|string|min:2',
                        'title_en' => 'required|string|min:2',
                        'title_ar' => 'required|string|min:2',
                        'price' => 'required|numeric',
                        'from_date'=>'required',
                        'to_date'=>'required',
                        'from_time'=>'required',
                        'to_time'=>'required',
                        'lat'=>'required',
                        'long'=>'required',
        ];
    }
}
