<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
                    'tourist_name'=>'required|string|min:2',
                    'tourist_email'=>'required|email',
                    'tourist_phone'=>'required|numeric|min:2',
                    'payment_type'=>'required|in:online,cash',
                    'order_date'=>'required|date',
                    'order_time'=>'required',
                    'total'=>'required|numeric',
                    'childrens'=>'required|numeric',
                    'adults'=>'required|numeric',
                    'program_id'=>'required|string|min:2',
                    'trip_id'=>'required|string|min:2',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {

                return [
                        'tourist_name'=>'required|string|min:2',
                        'tourist_email'=>'required|email',
                        'tourist_phone'=>'required|numeric|min:2',
                        'payment_type'=>'required|in:online,cash',
                        'order_date'=>'required|date',
                        'order_time'=>'required',
                        'total'=>'required|numeric',
                        'childrens'=>'required|numeric',
                        'adults'=>'required|numeric',
                        'program_id'=>'required|string|min:2',
                        'trip_id'=>'required|string|min:2',

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
