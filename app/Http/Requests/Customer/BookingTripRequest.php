<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingTripRequest extends FormRequest
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
            // 'booking_date'=>'required|date|after_or_equal:'.date('Y-m-d'),
            'people_number'=>'required|integer',
            'children_number'=>'required|integer',
            'payment_way'=>'required|in:online,cash',
            'trip_id'=>'required|'. Rule::exists('trips', 'id')->whereNull('deleted_at'),
            ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(apiResponse(false, null, 'Validation Error', $errors, 401));
    }
}
