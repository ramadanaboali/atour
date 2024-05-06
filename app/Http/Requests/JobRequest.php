<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'location' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'department_id' => 'required',
        ];
    }
}
