<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->type == 'privacy') {
            $rules['privacy_content'] = 'required';
        } elseif ($this->type == 'terms') {
            $rules['terms_content'] = 'required';
        } elseif ($this->type == 'about') {
            $rules['about_content'] = 'required';
        } else {
            $rules['general_company_name'] = 'required';
            $rules['general_latest_app_version'] = '';
            $rules['general_vdocipher_api_secret'] = '';
            $rules['general_email'] = '';
            $rules['general_phone'] = '';
            $rules['general_whatsapp'] = '';
            $rules['general_address'] = '';
            $rules['general_live_url'] = '';
        }
        return $rules;
    }
}
