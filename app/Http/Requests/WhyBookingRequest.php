<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhyBookingRequest extends FormRequest
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
                'description_ar' => 'required',
                'description_en' => 'required',
            ];
        }else{
            return [
                'title_ar' => 'required',
                'title_en' => 'required',
                'description_ar' => 'required',
                'description_en' => 'required',
                'image' => 'required',
            ];
        }
    }
}
