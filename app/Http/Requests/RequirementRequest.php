<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequirementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        if ($this->method() == 'PUT') {
            return [
                'title_en' => 'required',
                'title_ar' => 'required'
            ];
        } else {
            return [
                'title_en' => 'required',
                'title_ar' => 'required',
            ];
        }
    }
}
