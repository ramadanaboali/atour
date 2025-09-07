<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        if ($this->method() == 'PUT') {
            return [
               
                'start_date' => 'required',
                'end_date' => 'required',
                'location' => 'required',
                'notification_recipients' => 'nullable|in:all,clients,vendors',
            ];
        }else{
            return [
              
                'start_date' => 'required',
                'end_date' => 'required',
                'location' => 'required',
                'image' => 'required|image',
                'notification_recipients' => 'nullable|in:all,clients,vendors',
            ];
        }
    }
}
