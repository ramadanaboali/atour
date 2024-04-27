<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {


        $data = [
                   'name' => 'required|string|min:2',
                   'phone' => 'required|string|min:2||unique:users,phone,'.$this->id,
                   'email' => 'required|unique:users,email,'.$this->id,
                   'address' => 'required|string|min:2',
                   'code' => 'required|unique:users,code,'.$this->id,
                   'birthdate' => 'required|string|min:2',
                   'joining_date_from' => 'nullable|date',
                   'joining_date_to' => 'nullable|date|after:joining_date_from',
                   'city_id' => 'nullable|exists:cities,id',
                   'image' => 'nullable|image',
                ];
        if ($this->method() == 'POST') {
            $data = [
                'password' => 'required|string|min:8|confirmed',
            ];
        }
        return $data;


    }
}
