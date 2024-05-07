<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules['name']      = 'required';
        $rules['role_id']      = 'required';
        $rules['phone']     = 'required|unique:users,phone,' . $this->id;
        $rules['email']     = 'required|email|unique:users,email,' . $this->id;
        if ($this->method() == "POST") {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        return $rules;
    }
}
