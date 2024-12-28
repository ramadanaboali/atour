<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class EffectivenesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'description_en' => 'required|string|min:2',
                        'description_ar' => 'required|string|min:2',
                        'title_en' => 'required|string|min:2',
                        'title_ar' => 'required|string|min:2',
                        'price' => 'required|numeric',
                        'date'=>'required',
                        'time'=>'required',
                        'lat'=>'required',
                        'long'=>'required',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {

                    return [
                        'description_en' => 'nullable|string|min:2',
                        'description_ar' => 'nullable|string|min:2',
                        'price' => 'nullable|numeric',
                        'date'=>'required',
                        'time'=>'required',
                        'lat'=>'required',
                        'long'=>'required',
                    ];

                }
            default:
                return [];
        }

    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
