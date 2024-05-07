<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
            'category_id'=>'required'
        ];
    }
}
