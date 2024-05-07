<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'description_ar' => 'required',
            'description_en' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'discount' => 'required',
            'user_id' => 'required',
            'services' => 'required|array',
        ];
    }
}
