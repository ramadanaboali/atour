<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        if ($this->method() == 'PUT') {
            return [
                'title_ar' => 'required',
                'title_en' => 'required',
                'value' => 'required|numeric',
                'code_ar' => 'required',
                'code_en' => 'required',

            ];
        }else{
            return [
                'title_ar' => 'required',
                'title_en' => 'required',
                'value' => 'required|numeric',
                'code_ar' => 'required',
                'code_en' => 'required',
                'flag' => 'required',
            ];
        }
    }
}
