<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingGiftRequest extends FormRequest
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
        return [
            'payment_way' => 'required|in:online,cash',
            'gift_id' => 'required|'. Rule::exists('gifts', 'id')->whereNull('deleted_at'),
            'quantity' =>'required|numeric',
            'delivery_way' =>'required|in:delivery,myself',
            'delivery_address'=>'required_if:delivery_way,delivery',
            'delivery_number'=>'required_if:delivery_way,delivery',
            'location'=>'required_if:delivery_way,delivery'
            ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
