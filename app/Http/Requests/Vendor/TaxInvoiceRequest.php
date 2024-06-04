<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class TaxInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
                        'type' => 'required|in:service,product',
                        'cost' => 'required|numeric|min:1',
                        'name' => 'sometimes|string|min:2',
                        'phone' => 'sometimes|string|min:2',
                        'email' => 'sometimes|email',
                        'product_id' => 'required_if:type,product|integer',
                        'booking_id' => 'required_if:type,service|exists:bookings,id'
                    ];
                }
            case 'PATCH':
            case 'PUT':
            default:
                return [
                    'type' => 'required|in:service,product',
                    'cost' => 'required|numeric|min:1',
                    'name' => 'sometimes|string|min:2',
                    'phone' => 'sometimes|string|min:2',
                    'email' => 'sometimes|email',
                    'product_id' => 'required_if:type,product|integer',
                    'booking_id' => 'required_if:type,service|exists:bookings,id',
                    'is_active' => 'required|in:0,1',
                ];
        }

    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
